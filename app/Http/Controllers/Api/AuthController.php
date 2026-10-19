<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle user login and issue Passport token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required_without:email|string',
            'email'    => 'required_without:username|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $identifier = $request->input('username') ?? $request->input('email');
        $res = $this->userService->findByUsernameOrEmail($identifier);

        if (!$res['success'] || !$res['data']) {
            return $this->sendError('Invalid credentials.', [], 401);
        }

        /** @var User $user */
        $user = $res['data'];

        if (!Hash::check($request->password, $user->password)) {
            return $this->sendError('Invalid credentials.', [], 401);
        }

        $tokenResult = $user->createToken('Personal Access Token');

        $data = [
            'token_type'   => 'Bearer',
            'access_token' => $tokenResult->accessToken,
            'user'         => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ];

        return $this->sendResponse('User logged in successfully.', $data);
    }

    /**
     * Get authenticated user profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function profile(Request $request): JsonResponse
    {
        return $this->sendResponse('Profile retrieved successfully.', $request->user());
    }

    /**
     * Logout authenticated user and revoke Passport token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()?->token();
        if ($token) {
            $token->revoke();
        }

        return $this->sendResponse('Logged out successfully.');
    }
}
