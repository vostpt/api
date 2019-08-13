<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use VOSTPT\Filters\Contracts\OccurrenceFilter;
use VOSTPT\Http\Requests\Occurrence\DownloadReport;
use VOSTPT\Http\Requests\Occurrence\GenerateReport;
use VOSTPT\Jobs\Report\ReportGenerator;
use VOSTPT\Reports\OccurrenceReport;
use VOSTPT\Repositories\Contracts\OccurrenceRepository;

class OccurrenceReportController extends Controller
{
    /**
     * Create an Occurrence report.
     *
     * @param GenerateReport       $request
     * @param OccurrenceRepository $occurrenceRepository
     * @param OccurrenceFilter     $filter
     * @param BusDispatcher        $dispatcher
     *
     * @throws \UnexpectedValueException
     * @throws \OutOfBoundsException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(
        GenerateReport $request,
        OccurrenceRepository $occurrenceRepository,
        OccurrenceFilter $filter,
        BusDispatcher $dispatcher
    ): JsonResponse {
        $filter->setSortColumn($request->input('sort', $filter->getSortColumn()))
            ->setSortOrder($request->input('order', $filter->getSortOrder()))
            ->setPageNumber((int) $request->input('page.number', $filter->getPageNumber()))
            ->setPageSize((int) $request->input('page.size', $filter->getPageSize()));

        if ($request->has('search')) {
            $filter->withSearch($request->input('search'), (bool) $request->input('exact', false));
        }

        if ($request->has('events')) {
            $filter->withEvents(...$request->input('events', []));
        }

        if ($request->has('types')) {
            $filter->withTypes(...$request->input('types', []));
        }

        if ($request->has('statuses')) {
            $filter->withStatuses(...$request->input('statuses', []));
        }

        if ($request->has('districts')) {
            $filter->withDistricts(...$request->input('districts', []));
        }

        if ($request->has('counties')) {
            $filter->withCounties(...$request->input('counties', []));
        }

        if ($request->has('parishes')) {
            $filter->withParishes(...$request->input('parishes', []));
        }

        if ($startedAt = $request->get('started_at')) {
            $filter->withStartedAt(Carbon::parse($startedAt));
        }

        if ($endedAt = $request->get('ended_at')) {
            $filter->withEndedAt(Carbon::parse($endedAt));
        }

        $report = new OccurrenceReport($occurrenceRepository, $filter, Storage::disk('local'));

        $dispatcher->dispatch(new ReportGenerator($report));

        return response()->meta([
            'report' => [
                'signature' => $report->getSignature(),
                'ready'     => $report->isReadyForDownload(),
            ],
        ], 202);
    }

    /**
     * Download an Occurrence report.
     *
     * @param DownloadReport $request
     * @param string         $signature
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \UnexpectedValueException
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(DownloadReport $request, string $signature): BinaryFileResponse
    {
        $zipFileName = OccurrenceReport::getFileName($signature, true);

        if (! Storage::disk('local')->exists($zipFileName)) {
            throw new NotFoundHttpException('Occurrence Report Not Found');
        }

        return response()->download(Storage::disk('local')->url($zipFileName), \sprintf(
            'occurrence_report_%s.zip',
            \date('Y_m_d_H_i_s')
        ));
    }
}
