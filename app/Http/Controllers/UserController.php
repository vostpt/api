<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers\User;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\UserFilter;
use VOSTPT\Http\Controllers\Controller;
use VOSTPT\Http\Requests\User\Index;
use VOSTPT\Http\Requests\User\View;
use VOSTPT\Http\Serializers\UserSerializer;
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
            ->setPageNumber((int) $request->input('page.number', 1))
            ->setPageSize((int) $request->input('page.size', 50));

        if ($search = $request->input('search')) {
            $filter->withSearch($search);
        }

        if ($roles = $request->input('roles')) {
            $filter->withRoles($roles);
        }

        $paginator = $this->createPaginator(User::class, $userRepository->createQueryBuilder(), $filter);

        return response()->paginator($paginator, new UserSerializer());
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
}
