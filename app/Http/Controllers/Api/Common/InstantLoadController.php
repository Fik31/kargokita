<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use App\Models\Load;
use Illuminate\Http\Request;

class InstantLoadController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();

        if (! $user->is_common_verified) {
            return response()->json(['message' => 'Akun belum diverifikasi. Silakan upload KTP dan foto diri terlebih dahulu.'], 403);
        }

        $request->validate([
            'origin_lat' => 'required|numeric',
            'origin_lng' => 'required|numeric',
            'dest_lat' => 'required|numeric',
            'dest_lng' => 'required|numeric',
            'max_price' => 'required|numeric|min:1000',
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
        ]);

        $distance = $this->calculateDistance(
            $request->origin_lat, $request->origin_lng,
            $request->dest_lat, $request->dest_lng
        );

        if ($distance > 30) {
            return response()->json([
                'message' => 'Jarak pengiriman terlalu jauh. Maksimal jarak untuk layanan instant adalah 30 KM.',
                'distance_km' => round($distance, 2),
            ], 400);
        }

        $load = Load::create([
            'merchant_id' => $user->id,
            'is_instant' => true,
            'status' => 'open',
            'max_price' => $request->max_price,
            'origin_lat' => $request->origin_lat,
            'origin_lng' => $request->origin_lng,
            'dest_lat' => $request->dest_lat,
            'dest_lng' => $request->dest_lng,
            'type' => $request->type,
            'title' => $request->title,
            'item_name' => $request->item_name,
        ]);

        return response()->json(['message' => 'Instant Order berhasil dibuat', 'data' => $load, 'distance_km' => round($distance, 2)], 201);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));

        return $earth_radius * $c;
    }
}
