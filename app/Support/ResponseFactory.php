<?php

declare(strict_types=1);

namespace VOSTPT\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;

class ResponseFactory extends \Illuminate\Routing\ResponseFactory
{
    /**
     * JSON API response.
     *
     * @param mixed $data
     * @param int   $status
     * @param array $headers
     * @param int   $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function json($data = [], $status = 200, array $headers = [], $options = 0): JsonResponse
    {
        $headers = \array_replace($headers, [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        return parent::json($data, $status, $headers, $options);
    }

    /**
     * JSON API Meta response.
     *
     * @param array $data
     * @param int   $status
     * @param array $headers
     * @param int   $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function meta(array $data = [], $status = 200, array $headers = [], $options = 0): JsonResponse
    {
        return $this->json([
            'meta' => $data,
        ], $status, $headers, $options);
    }

    /**
     * JSON API Resource response.
     *
     * @param Model              $resource
     * @param AbstractSerializer $serializer
     * @param array              $relationships
     * @param int                $status
     * @param array              $headers
     * @param int                $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resource(
        Model $resource,
        AbstractSerializer $serializer,
        array $relationships = [],
        int $status = 200,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        $resource = new Resource($resource, $serializer);
        $document = new Document($resource->with($relationships));

        return $this->json($document->toArray(), $status, $headers, $options);
    }

    /**
     * JSON API Collection response.
     *
     * @param EloquentCollection $collection
     * @param AbstractSerializer $serializer
     * @param array              $relationships
     * @param int                $status
     * @param array              $headers
     * @param int                $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function collection(
        EloquentCollection $collection,
        AbstractSerializer $serializer,
        array $relationships = [],
        int $status = 200,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        $collection = new Collection($collection, $serializer);
        $document   = new Document($collection->with($relationships));

        return $this->json($document->toArray(), $status, $headers, $options);
    }

    /**
     * JSON API Paginator response.
     *
     * @param LengthAwarePaginator $paginator
     * @param AbstractSerializer   $serializer
     * @param array                $relationships
     * @param int                  $status
     * @param array                $headers
     * @param int                  $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginator(
        LengthAwarePaginator $paginator,
        AbstractSerializer $serializer,
        array $relationships = [],
        int $status = 200,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        $collection = new Collection($paginator, $serializer);
        $document   = new Document($collection->with($relationships));

        $document->setLinks([
            'first' => $paginator->url(1),
            'last'  => $paginator->url($paginator->lastPage()),
        ]);

        if ($previous = $paginator->previousPageUrl()) {
            $document->addLink('prev', $previous);
        }

        if ($next = $paginator->nextPageUrl()) {
            $document->addLink('next', $next);
        }

        $document->setMeta([
            'per_page' => $paginator->perPage(),
            'total'    => $paginator->total(),
        ]);

        return $this->json($document->toArray(), $status, $headers, $options);
    }

    /**
     * JSON API HTTP error response.
     *
     * @param HttpException $exception
     * @param array         $headers
     * @param int           $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(HttpException $exception, array $headers = [], int $options = 0): JsonResponse
    {
        $errors = [
            'errors' => [
                [
                    'status' => $exception->getStatusCode(),
                    'detail' => $exception->getMessage() ?? Response::$statusTexts[$exception->getStatusCode()],
                ],
            ],
        ];

        return $this->json($errors, $exception->getStatusCode(), $headers, $options);
    }

    /**
     * JSON API validation error response.
     *
     * @param ValidationException $exception
     * @param array               $headers
     * @param int                 $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validation(ValidationException $exception, array $headers = [], int $options = 0): JsonResponse
    {
        $errors = [];

        foreach ($exception->errors() as $field => $details) {
            foreach ($details as $detail) {
                $errors['errors'][] = [
                    'detail' => $detail,
                    'meta'   => [
                        'field' => $field,
                    ],
                ];
            }
        }

        return $this->json($errors, $exception->status, $headers, $options);
    }
}
