<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RegionController extends Controller
{
    /**
     * Display a listing of the regions with related data.
     */
    public function index(): JsonResponse
    {
        $regions = Region::with([
            'landHolding',
            'irrigations',
            'croppingPatterns.crop',
        ])->get();

        return response()->json([
            'data' => $regions,
        ]);
    }

    /**
     * Display the specified region with related data.
     */
    public function show(int $id): JsonResponse
    {
        $region = Region::with([
            'landHolding',
            'irrigations',
            'croppingPatterns.crop',
        ])->findOrFail($id);

        return response()->json([
            'data' => $region,
        ]);
    }

    /**
     * Return aggregated analytics data for regions.
     */
    public function analytics(): JsonResponse
    {
        $regions = Region::with([
            'landHolding',
            'irrigations',
            'croppingPatterns.crop',
        ])->get();

        $totalLandSize = 0;
        $landCount = 0;
        $totalIrrigationCoverage = 0;
        $irrigationCount = 0;
        $cropAreaTotals = [];

        foreach ($regions as $region) {
            if ($region->landHolding) {
                $totalLandSize += $region->landHolding->avg_land_size;
                $landCount++;
            }

            foreach ($region->irrigations as $irrigation) {
                $totalIrrigationCoverage += $irrigation->coverage_percentage;
                $irrigationCount++;
            }

            foreach ($region->croppingPatterns as $pattern) {
                $cropName = $pattern->crop?->name ?? 'Unknown';
                if (! isset($cropAreaTotals[$cropName])) {
                    $cropAreaTotals[$cropName] = 0;
                }
                $cropAreaTotals[$cropName] += $pattern->area_percentage;
            }
        }

        $averageLandSize = $landCount > 0 ? round($totalLandSize / $landCount, 2) : 0;
        $averageIrrigationCoverage = $irrigationCount > 0 ? round($totalIrrigationCoverage / $irrigationCount, 2) : 0;

        arsort($cropAreaTotals);
        $mostCommonCrop = null;
        $mostCommonCropArea = 0;

        if (! empty($cropAreaTotals)) {
            $mostCommonCrop = array_key_first($cropAreaTotals);
            $mostCommonCropArea = $cropAreaTotals[$mostCommonCrop];
        }

        return response()->json([
            'data' => [
                'average_land_size' => $averageLandSize,
                'average_irrigation_coverage' => $averageIrrigationCoverage,
                'most_common_crop' => [
                    'name' => $mostCommonCrop,
                    'total_area_percentage' => $mostCommonCropArea,
                ],
            ],
        ]);
    }
}
