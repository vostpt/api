<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Http\Requests\User\RetrieveRoles;
use VOSTPT\Http\Serializers\RoleSerializer;
use VOSTPT\Models\Role;

class UserRoleController extends Controller
{
    /**
     * Index User roles.
     *
     * @param RetrieveRoles $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RetrieveRoles $request): JsonResponse
    {
        return response()->collection(Role::all(), new RoleSerializer());
    }
}
