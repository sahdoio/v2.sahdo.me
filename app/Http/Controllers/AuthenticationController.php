<?php

namespace App\Http\Controllers;

use App\Helpers\APIResponse;
use App\Helpers\Log;
use App\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    /**
     * Authentication service
     *
     * @var AuthenticationService
     */
    private AuthenticationService $service;

    /**
     * Constructor method
     *
     * @param AuthenticationService $service
     *
     * @return void
     */
    public function __construct(AuthenticationService $service)
    {
        $this->service = $service;
    }

    /**
     * Login method
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validate = Validator::make($request->input(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validate->fails()) {
            Log::debug('Invalid parameters');
            return APIResponse::badRequest($validate->getMessageBag()->all());
        }

        return $this->service
            ->login(
                $request->input('username'),
                $request->input('password')
            );
    }

    /**
     * Login method
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        return $this->service->logout($token);
    }
}
