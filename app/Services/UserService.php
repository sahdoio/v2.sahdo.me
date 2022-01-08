<?php

namespace App\Services;

use App\Helpers\APIResponse;
use App\Helpers\Log;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserService
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(string $username, string $name, string $email, string $password): JsonResponse
    {
        try {
            $user = new User();
            $user->username = $username;
            $user->name = $name;
            $user->email = $email;
            $plainPassword = $password;
            $user->password = app('hash')->make($plainPassword);
            $user->save();

            return APIResponse::success(__('user.created'), $user->toArray());

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return APIResponse::serverError(__('generic.internalServerError'));
        }
    }
}
