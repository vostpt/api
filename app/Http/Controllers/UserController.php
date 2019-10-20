<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use VOSTPT\Filters\Contracts\UserFilter;
use VOSTPT\Http\Requests\User\Create;
use VOSTPT\Http\Requests\User\Index;
use VOSTPT\Http\Requests\User\Update;
use VOSTPT\Http\Requests\User\View;
use VOSTPT\Http\Serializers\UserSerializer;
use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Repositories\Contracts\UserRepository;

class UserController extends Controller
{
    /**
     * Index Users.
     *
     * @param Index          $request
     * @param UserFilter     $filter
     * @param UserRepository $userRepository
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Index $request, UserFilter $filter, UserRepository $userRepository): JsonResponse
    {
        $filter->setSortColumn($request->input('sort', $filter->getSortColumn()))
            ->setSortOrder($request->input('order', $filter->getSortOrder()))
            ->setPageNumber((int) $request->input('page.number', $filter->getPageNumber()))
            ->setPageSize((int) $request->input('page.size', $filter->getPageSize()));

        if ($request->has('ids')) {
            $filter->withIds(...$request->input('ids', []));
        }

        if ($request->has('search')) {
            $filter->withSearch($request->input('search'), (bool) $request->input('exact', false));
        }

        if ($request->has('roles')) {
            $filter->withRoles(...$request->input('roles', []));
        }

        return response()->paginator($userRepository->getPaginator($filter), new UserSerializer());
    }

    /**
     * Create a User.
     *
     * @param Create $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Create $request): JsonResponse
    {
        $user = DB::transaction(function () use ($request) {
            $user = new User();

            $user->name = $request->input('name');
            $user->surname = $request->input('surname');
            $user->email = $request->input('email');
            $user->password = $request->input('password');

            $user->save();

            // Newly registered users are set as contributors
            $user->assign(Role::CONTRIBUTOR);

            return $user;
        });

        return response()->resource($user, new UserSerializer(), [
            'roles',
        ], 201);
    }

    /**
     * View a User.
     *
     * @param View $request
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(View $request, User $user): JsonResponse
    {
        return response()->resource($user, new UserSerializer(), [
            'roles',
        ]);
    }

    /**
     * Update a User.
     *
     * @param Update $request
     * @param User   $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Update $request, User $user): JsonResponse
    {
        $user = DB::transaction(function () use ($request, $user) {
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

            if ($request->has('roles')) {
                // Remove current roles before new assignments
                $user->retract($user->getRoles());

                $user->assign($request->input('roles'));
            }

            return $user;
        });

        return response()->resource($user, new UserSerializer(), [
            'roles',
        ]);
    }
}
