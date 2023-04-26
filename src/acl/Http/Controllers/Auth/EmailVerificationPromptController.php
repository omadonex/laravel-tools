<?php

namespace Omadonex\LaravelTools\Acl\Http\Controllers\Auth;

use Omadonex\LaravelTools\Common\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended('/')
                    : view('auth.verify-email');
    }
}
