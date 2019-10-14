<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Manager as JWTManager;
use Tymon\JWTAuth\Token;
use VOSTPT\Http\Requests\Auth\Authenticate;
use VOSTPT\Http\Requests\Auth\Refresh;
use VOSTPT\Http\Requests\Auth\Verify;

class AuthController extends Controller
{
    /**
     * JWT manager.
     *
     * @var JWTManager
     */
    private $jwtManager;

    /**
     * @param JWTManager $jwtManager
     */
    public function __construct(JWTManager $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    /**
     * Authenticate a User.
     *
     * @param Authenticate $request
     * @param Auth         $auth
     *
     * @throws \Tymon\JWTAuth\Exceptions\TokenBlacklistedException
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Authenticate $request, Auth $auth): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (! $token = $auth->attempt($credentials)) {
            throw new UnauthorizedHttpException('Newauth realm="VOST PT"', 'Invalid credentials');
        }

        return $this->accessTokenResponse($token);
    }

    /**
     * Refresh the access token of a User.
     *
     * @param Refresh $request
     * @param Auth    $auth
     *
     * @throws \Tymon\JWTAuth\Exceptions\TokenBlacklistedException
     *
     * @return JsonResponse
     */
    public function refresh(Refresh $request, Auth $auth): JsonResponse
    {
        return $this->accessTokenResponse($auth->refresh());
    }

    /**
     * Verify the access token of a User.
     *
     * @param Verify $request
     *
     * @throws \Tymon\JWTAuth\Exceptions\TokenBlacklistedException
     *
     * @return JsonResponse
     */
    public function verify(Verify $request): JsonResponse
    {
        return $this->accessTokenResponse($request->bearerToken(), 200);
    }

    /**
     * Generate an access token response.
     *
     * @param string $token
     * @param int    $status
     *
     * @throws \Tymon\JWTAuth\Exceptions\TokenBlacklistedException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function accessTokenResponse(string $token, int $status = 201): JsonResponse
    {
        $payload = $this->jwtManager->decode(new Token($token));

        return response()->meta([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $payload->get('exp') - \time(),
        ], $status, [
            'Cache-Control' => 'no-store',
            'Pragma'        => 'no-cache',
        ]);
    }
}
