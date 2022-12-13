<?php

namespace Omadonex\LaravelTools\Support\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsApp;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsResponseJson;
use Omadonex\LaravelTools\Support\Events\UserActivationResendEvent;
use Omadonex\LaravelTools\Support\Http\Requests\UserActivateRequest;
use Omadonex\LaravelTools\Support\Models\UserActivation;

class UserActivationController extends Controller
{
    public function activation($token)
    {
        $userActivation = UserActivation::where('token', $token)->first();

        if (!$userActivation) {
            abort(404);
        }

        $user = $userActivation->user;
        if (!$user->isRandom()) {
            $user->activate($userActivation);

            if (!auth()->check()) {
                Auth::login($user);
            }

            UtilsApp::addLiveNotify(trans('support::auth.activated'));

            return redirect('/');
        }

        $data = [
            ConstCustom::MAIN_DATA_PAGE => [
                'token' => $token,
                'email' => $userActivation->user->email,
            ],
        ];

        return view('layouts.pages', $data);
    }

    public function activate(UserActivateRequest $request)
    {
        $userActivation = UserActivation::where('token', $request->token)->first();
        if ($userActivation) {
            $user = $userActivation->user;
            $activationData = $request->all();
            $activationData['password'] = bcrypt($activationData['password']);

            $user->activate($userActivation, $activationData);

            if (!auth()->check()) {
                Auth::login($user);
            }

            UtilsApp::addLiveNotify(trans('support::auth.activated'));

            return UtilsResponseJson::okResponse([
                ConstCustom::REDIRECT_URL => route('content.lesson.index'),
            ], true);
        }

        return UtilsResponseJson::validationResponse([
            'activationToken' => [
                trans('support::auth.activationToken'),
            ],
        ]);
    }

    public function resendActivation()
    {
        $user = auth()->user();
        $userActivation = auth()->check() ? $user->userActivation : null;
        if (!auth()->check() || $user->isActivated() || !$userActivation) {
            return UtilsResponseJson::errorResponse([
                ConstCustom::ERROR_MESSAGE => trans('support::auth.activationResendError'),
            ]);
        }

        $now = Carbon::now();
        if ($now->diffInMinutes($userActivation->sent_at) < ConstCustom::ACTIVATION_EMAIL_REPEAT_MINUTES) {
            $seconds = ConstCustom::ACTIVATION_EMAIL_REPEAT_MINUTES * 60 - $now->diffInSeconds($userActivation->sent_at);

            return UtilsResponseJson::errorResponse([
                ConstCustom::ERROR_MESSAGE => trans('support::auth.activationResendTime', ['seconds' => $seconds]),
            ]);
        }

        $userActivation->update(['sent_at' => $now]);

        event(new UserActivationResendEvent($user, $userActivation));

        return UtilsResponseJson::okResponse([
            'message' => trans('support::auth.activationResendSuccess'),
        ]);
    }
}
