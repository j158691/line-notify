<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    /**
     * @var User
     */
    protected $user;

    public function __construct(
        User $user
    )
    {
        $this->user = $user;
    }

    /**
     * @param $userIds
     * @return Collection
     */
    public function findUsers($userIds)
    {
        return $this->user->newModelQuery()->findMany($userIds);
    }

    /**
     * @param $account
     * @param $password
     * @param $lineNotify
     * @return User|Builder|Model
     */
    public function createUser($account, $password, $lineNotify)
    {
        $create = [
            'account' => $account,
            'password' => $password,
            'line_notify' => $lineNotify,
        ];

        return $this->user->newModelQuery()->create($create);
    }
}
