<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TgsSession; // Updated from TgsRoom
use App\Models\Jamaah;
use App\Models\JamaahLocation;
use Illuminate\Validation\ValidationException;

class TgsController extends Controller
{
    // Mutawwif Starts a Session
    public function startSession(Request $request)
    {
        $request->validate([
            'packet_id' => 'required|exists:packets,id', // Based on Packet now
            'channel_name' => 'required|string', 
        ]);

        $user = $request->user();
        
        // Ensure user is authorized (Mutawwif)
        // if (!$user->hasRole('guide')) abort(403);

        $session = TgsSession::create([
            'packet_id' => $request->packet_id,
            'mutawwif_id' => $user->id,
            'channel_name' => $request->channel_name,
            'is_active' => true,
        ]);

        return response()->json(['message' => 'Session started', 'session' => $session]);
    }

    // Jamaah Joins a Session
    public function joinSession(Request $request) 
    {
        $user = $request->user(); // Jamaah

        // Logic: Check if Jamaah has a transaction for a packet that has an active TgsSession
        $activeSession = TgsSession::where('is_active', true)
            ->whereHas('packet.transactions.details', function($q) use ($user) {
                $q->where('jamaah_id', $user->id);
            })
            ->latest()
            ->first();

        if (!$activeSession) {
            return response()->json(['message' => 'No active TGS session found for your packet.'], 404);
        }

        return response()->json([
            'channel_name' => $activeSession->channel_name,
            'mutawwif' => $activeSession->mutawwif_id,
        ]);
    }

    // GPS Tracking
    public function updateLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $user = $request->user(); // Authenticated Jamaah

        JamaahLocation::create([
            'jamaah_id' => $user->id,
            'lat' => $request->lat,
            'lng' => $request->lng,
            // 'recorded_at' defaults to current timestamp
        ]);

        return response()->json(['status' => 'Location updated']);
    }
}
