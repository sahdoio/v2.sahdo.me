<?php

namespace App\Http\Controllers;

use App\Helpers\APIResponse;
use App\Helpers\Log;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * User service
     *
     * @var UserService
     */
    private UserService $service;

    /**
     * Constructor method
     *
     * @param UserService $service
     *
     * @return void
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        $validate = Validator::make($request->input(), [
            'username' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validate->fails()) {
            Log::debug('Invalid parameters');
            return APIResponse::badRequest($validate->getMessageBag()->all());
        }

        return $this->service
            ->create(
                $request->input('username'),
                $request->input('name'),
                $request->input('email'),
                $request->input('password')
            );
    }

    /**
     * Get user info
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getUserDetails(Request $request): JsonResponse
    {
        $data = $request->user()->toArray();
        return APIResponse::success([], $data);
    }
}
