<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use VOSTPT\Http\Requests\Auth\Authenticate;
use VOSTPT\Http\Requests\Auth\Refresh;

class AuthController extends Controller
{
    /**
     * Authenticate a User.
     *
     * @param Authenticate $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Authenticate $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth()->attempt($credentials)) {
            throw new UnauthorizedHttpException('Newauth realm="VOST PT"', 'Invalid credentials');
        }

        return $this->accessTokenResponse($token);
    }

    /**
     * Refresh the access token of a User.
     *
     * @param Refresh $request
     *
     * @return JsonResponse
     */
    public function refresh(Refresh $request): JsonResponse
    {
        return $this->accessTokenResponse(auth()->refresh());
    }

    /**
     * Generate an access token response.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function accessTokenResponse(string $token): JsonResponse
    {
        return response()->meta([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ], 201, [
            'Cache-Control' => 'no-store',
            'Pragma'        => 'no-cache',
        ]);
    }
}
