<?php

namespace App\Services;

use App\Helpers\APIResponse;
use App\Helpers\Log;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Exception;

/**
 * Authentication service class
 */
class AuthenticationService
{
    /**
     * User model
     *
     * @var User
     */
    private User $user;


    /**
     * Constructor method
     *
     * @param User      $user
     * @param UserToken $userToken
     * @param LDAP      $LDAP
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Login method
     *
     * @param string $username
     * @param string $password
     * @param string $clientIp
     * @param string $userAgent
     *
     * @return JsonResponse
     */
    public function login(
        string $username,
        string $password
    ): JsonResponse {
        try {
            $credentials = [
                'username' => $username,
                'password' => $password
            ];

            $token = auth()->attempt($credentials);

            if (!$token) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            return APIResponse::success(__('authentication.welcome'), [], $token);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return APIResponse::serverError(__('generic.internalServerError'));
        }
    }

    /**
     * Logout method
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    public function logout(string $token): JsonResponse
    {
        try {
            Auth::logout();

            return APIResponse::success(__('authentication.logoutSuccessfully'));
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return APIResponse::serverError(__('generic.internalServerError'));
        }
    }
}
