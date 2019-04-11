<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Http\Requests\User\UpdateProfile;
use VOSTPT\Http\Requests\User\ViewProfile;
use VOSTPT\Http\Serializers\UserSerializer;

class UserProfileController extends Controller
{
    /**
     * View User profile.
     *
     * @param ViewProfile $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(ViewProfile $request): JsonResponse
    {
        return response()->resource($request->user(), new UserSerializer(), [
            'roles',
        ]);
    }

    /**
     * Update User profile.
     *
     * @param UpdateProfile $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfile $request): JsonResponse
    {
        $user = $request->user();

        if ($request->has('name')) {
            $user->name = $request->input('name');
        }

        if ($request->has('surname')) {
            $user->surname = $request->input('surname');
        }

        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        if ($request->has('password')) {
            $user->password = $request->input('password');
        }

        $user->save();

        return response()->resource($user, new UserSerializer(), [
            'roles',
        ]);
    }
}
