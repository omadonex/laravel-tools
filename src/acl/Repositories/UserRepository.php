<?php

namespace Omadonex\LaravelTools\Acl\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Omadonex\LaravelTools\Acl\Http\Resources\UserResource;
use Omadonex\LaravelTools\Acl\Models\User;
use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsUserLabel;
use Omadonex\LaravelTools\Support\Repositories\ModelRepository;

class UserRepository extends ModelRepository
{
    protected ILocaleService $localeService;

    public function __construct(User $user, ILocaleService $localeService)
    {
        parent::__construct($user, UserResource::class);
        $this->localeService = $localeService;
    }

    public function pluckExt(string $name = 'name', string $id = 'id'): Collection
    {
        $userList = $this->model->get()
            ->map(function (User $user) {
                return [
                    'id'   => $user->id,
                    'name' => UtilsUserLabel::getFromModel($user),
                ];
            });

        return $userList->pluck($name, $id);
    }

    public function grid($options = [])
    {
        $lang = $this->localeService->getLocaleCurrent();

        $sql = /* @lang MySQL */ "
        SELECT
            u.id,
            u.username,
            u.email,
            u.email_verified_at,
            u.phone_code,
            u.phone,
            u.phone_verified_at,
            u.display_name,
            u.first_name,
            u.last_name,
            u.opt_name,
            u.avatar,
            r.roles_ids,
            r.roles_ids_label
        FROM
            users AS u
                LEFT JOIN (select
                        pru.user_id,
                        json_arrayagg(r.id) as roles_ids,
                        json_arrayagg(t.name) as roles_ids_label
                    from
                        acl_pivot_role_user pru
                        JOIN acl_role AS r on r.id = pru.role_id
                        JOIN acl_role_translate AS t ON t.model_id = r.id AND t.lang = '{$lang}'
                    group by
                        pru.user_id) as r on r.user_id = u.id
        ";

        return $this->list($options, DB::table(DB::raw("({$sql}) as temp")));
    }
}
