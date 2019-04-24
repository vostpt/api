<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Http\Requests\Occurrence\View;
use VOSTPT\Http\Serializers\ProCivOccurrenceSerializer;
use VOSTPT\Models\ProCivOccurrence;

class ProCivOccurrenceController extends Controller
{
    /**
     * View a ProCivOccurrence.
     *
     * @param View             $request
     * @param ProCivOccurrence $proCivOccurrence
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(View $request, ProCivOccurrence $proCivOccurrence): JsonResponse
    {
        return response()->resource($proCivOccurrence, new ProCivOccurrenceSerializer(), [
            'type',
            'status',
        ]);
    }
}
