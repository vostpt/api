<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\EventFilter;
use VOSTPT\Http\Requests\Event\Create;
use VOSTPT\Http\Requests\Event\Delete;
use VOSTPT\Http\Requests\Event\Index;
use VOSTPT\Http\Requests\Event\Update;
use VOSTPT\Http\Requests\Event\View;
use VOSTPT\Http\Serializers\EventSerializer;
use VOSTPT\Models\Event;
use VOSTPT\Repositories\Contracts\EventRepository;

class EventController extends Controller
{
    /**
     * Index Events.
     *
     * @param Index           $request
     * @param EventFilter     $filter
     * @param EventRepository $eventRepository
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Index $request, EventFilter $filter, EventRepository $eventRepository): JsonResponse
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

        if ($request->has('types')) {
            $filter->withTypes(...$request->input('types', []));
        }

        if ($request->has('parishes')) {
            $filter->withParishes(...$request->input('parishes', []));
        }

        if ($request->has('latitude', 'longitude')) {
            $filter->withCoordinates(
                (float) $request->input('latitude'),
                (float) $request->input('longitude'),
                (int) $request->input('radius', 10)
            );
        }

        return response()->paginator($eventRepository->getPaginator($filter), new EventSerializer());
    }

    /**
     * Create an Event.
     *
     * @param Create $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Create $request): JsonResponse
    {
        $event = new Event();

        $event->type()->associate($request->input('type'));
        $event->parish()->associate($request->input('parish'));

        $event->name        = $request->input('name');
        $event->description = $request->input('description');

        $event->latitude  = $request->input('latitude');
        $event->longitude = $request->input('longitude');

        $event->started_at = $request->input('started_at');
        $event->ended_at   = $request->input('ended_at');

        $event->save();

        return response()->resource($event, new EventSerializer(), [
            'type',
            'parish',
        ], 201);
    }

    /**
     * View an Event.
     *
     * @param View  $request
     * @param Event $event
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(View $request, Event $event): JsonResponse
    {
        return response()->resource($event, new EventSerializer(), [
            'type',
            'parish',
        ]);
    }

    /**
     * Update an Event.
     *
     * @param Update $request
     * @param Event  $event
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Update $request, Event $event): JsonResponse
    {
        if ($request->has('type')) {
            $event->type()->associate($request->input('type'));
        }

        if ($request->has('parish')) {
            $event->parish()->associate($request->input('parish'));
        }

        if ($request->has('name')) {
            $event->name = $request->input('name');
        }

        if ($request->has('description')) {
            $event->description = $request->input('description');
        }

        if ($request->has('latitude')) {
            $event->latitude = $request->input('latitude');
        }

        if ($request->has('longitude')) {
            $event->longitude = $request->input('longitude');
        }

        if ($request->has('longitude')) {
            $event->longitude = $request->input('longitude');
        }

        if ($request->has('started_at')) {
            $event->started_at = $request->input('started_at');
        }

        if ($request->has('ended_at')) {
            $event->ended_at = $request->input('ended_at');
        }

        $event->save();

        return response()->resource($event, new EventSerializer(), [
            'type',
            'parish',
        ]);
    }

    /**
     * Delete an Event.
     *
     * @param Delete $request
     * @param Event  $event
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(Delete $request, Event $event): JsonResponse
    {
        $event->delete();

        return response()->resource($event, new EventSerializer(), [
            'type',
            'parish',
        ]);
    }
}
