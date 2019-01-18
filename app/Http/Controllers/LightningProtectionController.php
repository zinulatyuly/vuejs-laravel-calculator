<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LightningProtectionCalculator as Calculator;


class LightningProtectionController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calc(Request $request)
    {
        $calculator = new Calculator();

        $result = $calculator->calc($request);

        if (isset($result['errors'])) {
            return response()->json($result, 422);
        } else {
            return response()->json($result);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Throwable
     */
    public function export(Request $request, Calculator $service)
    {
        $result = $service->calc($request);

        if (isset($result['errors'])) {
            return response()->json($result, 422);
        } else {
            $result = $service->makeOrderReport();

            return response()->json($result);
        }
    }
}