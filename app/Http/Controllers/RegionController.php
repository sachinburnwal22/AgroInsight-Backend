<?php

namespace App\Http\Controllers;

use App\Models\Region;
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
        $irrigationTypeStats = [];
        $regionComparison = [];

        foreach ($regions as $region) {
            $regionLandSize = 0;
            $regionIrrigationCoverage = 0;
            $regionIrrigationCount = 0;
            $regionTotalArea = 0;
            $mainCrop = null;
            $mainCropArea = 0;

            if ($region->landHolding) {
                $regionLandSize = round($region->landHolding->avg_land_size, 2);
                $totalLandSize += $regionLandSize;
                $landCount++;
            }

            foreach ($region->irrigations as $irrigation) {
                $coverage = $irrigation->coverage_percentage;
                $totalIrrigationCoverage += $coverage;
                $irrigationCount++;
                $regionIrrigationCoverage += $coverage;
                $regionIrrigationCount++;

                $type = $irrigation->type ?? 'Unknown';
                if (! isset($irrigationTypeStats[$type])) {
                    $irrigationTypeStats[$type] = [
                        'type' => $type,
                        'count' => 0,
                        'total_coverage' => 0,
                    ];
                }
                $irrigationTypeStats[$type]['count']++;
                $irrigationTypeStats[$type]['total_coverage'] += $coverage;
            }

            foreach ($region->croppingPatterns as $pattern) {
                $cropName = $pattern->crop?->name ?? 'Unknown';
                $area = $pattern->area_percentage;
                $regionTotalArea += $area;

                if (! isset($cropAreaTotals[$cropName])) {
                    $cropAreaTotals[$cropName] = 0;
                }
                $cropAreaTotals[$cropName] += $area;

                if ($area > $mainCropArea) {
                    $mainCropArea = $area;
                    $mainCrop = $cropName;
                }
            }

            $regionComparison[] = [
                'region' => $region->name,
                'soil_type' => $region->soil_type,
                'climate' => $region->climate,
                'avg_land_size' => $regionLandSize,
                'average_irrigation_coverage' => $regionIrrigationCount > 0 ? round($regionIrrigationCoverage / $regionIrrigationCount, 2) : 0,
                'main_crop' => $mainCrop,
                'total_crop_area' => round($regionTotalArea, 2),
            ];
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

        $cropDistribution = array_map(
            function ($name, $value) {
                return [
                    'name' => $name,
                    'value' => round($value, 2),
                ];
            },
            array_keys($cropAreaTotals),
            $cropAreaTotals
        );

        $irrigationSummary = array_map(
            function ($stats) {
                return [
                    'type' => $stats['type'],
                    'count' => $stats['count'],
                    'average_coverage' => $stats['count'] > 0 ? round($stats['total_coverage'] / $stats['count'], 2) : 0,
                ];
            },
            array_values($irrigationTypeStats)
        );

        return response()->json([
            'data' => [
                'summary' => [
                    'total_regions' => $regions->count(),
                    'average_land_size' => $averageLandSize,
                    'average_irrigation_coverage' => $averageIrrigationCoverage,
                    'most_common_crop' => [
                        'name' => $mostCommonCrop,
                        'total_area_percentage' => $mostCommonCropArea,
                    ],
                ],
                'crop_distribution' => $cropDistribution,
                'region_comparison' => $regionComparison,
                'irrigation_summary' => $irrigationSummary,
            ],
        ]);
    }

    /**
     * Return all crops.
     */
    public function crops(): JsonResponse
    {
        $crops = \App\Models\Crop::all();
        return response()->json([
            'data' => $crops,
        ]);
    }

    /**
     * Return dashboard stats.
     */
    public function dashboard(): JsonResponse
    {
        $regions = Region::all();
        $crops = \App\Models\Crop::all();

        // Calculate average health score from regions
        $avgHealthScore = $regions->avg('health_score') ?? 0;

        // Calculate a dummy soil moisture based on rainfall range averages
        $totalRainfall = 0;
        foreach ($regions as $region) {
            if (preg_match('/(\d+)-(\d+)/', $region->rainfall_range, $matches)) {
                $totalRainfall += ($matches[1] + $matches[2]) / 2;
            }
        }
        $avgRainfall = $regions->count() > 0 ? $totalRainfall / $regions->count() : 0;
        $soilMoisture = min(95, max(40, round(($avgRainfall / 2300) * 100)));

        return response()->json([
            'data' => [
                'quick_stats' => [
                    [
                        'label' => 'Soil Moisture',
                        'value' => $soilMoisture,
                        'trend' => '+2%',
                        'color' => 'primary',
                        'icon' => 'Droplet'
                    ],
                    [
                        'label' => 'Total Regions',
                        'value' => $regions->count(),
                        'trend' => 'Active',
                        'color' => 'accent',
                        'icon' => 'Map'
                    ],
                    [
                        'label' => 'Crop Health',
                        'value' => round($avgHealthScore),
                        'trend' => '+5%',
                        'color' => 'secondary',
                        'icon' => 'Leaf'
                    ],
                    [
                        'label' => 'Total Crops',
                        'value' => $crops->count(),
                        'trend' => 'Monitored',
                        'color' => 'primary',
                        'icon' => 'Sprout'
                    ]
                ],
                'field_status' => $regions->map(function ($r) {
                    $color = $r->health_score > 80 ? 'primary' : ($r->health_score > 60 ? 'accent' : 'destructive');
                    $status = $r->health_score > 80 ? 'Optimal' : ($r->health_score > 60 ? 'Monitor' : 'Action Needed');
                    return [
                        'name' => $r->name,
                        'status' => $status,
                        'progress' => $r->health_score,
                        'color' => $color,
                    ];
                })
            ]
        ]);
    }
}
