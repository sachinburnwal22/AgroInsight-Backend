<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GovernmentAlert;

class GovAlertController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();
        $state = $request->query('state');

        if (empty($state) && $user) {
            $state = $user->region;
        }

        $query = GovernmentAlert::orderBy('created_at', 'desc');

        if (!empty($state) && $state !== 'Global') {
            $query->where(function ($q) use ($state) {
                $q->where('state', 'like', "%{$state}%")
                  ->orWhere('state', 'All India');
            });
        } else {
            $query->where('state', 'All India');
        }

        // Limit to 10 alerts for efficiency
        $alerts = $query->take(10)->get();

        return response()->json([
            'status' => 'success',
            'state' => $state ?: 'All India',
            'data' => $alerts
        ]);
    }
}
