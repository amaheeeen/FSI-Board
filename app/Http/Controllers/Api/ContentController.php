<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function itinerary($id)
    {
        // Mocking Itinerary Data (In real app, fetch from Packet relationship)
        // $packet = Packet::findOrFail($id);
        return response()->json([
            'days' => [
                ['day' => 1, 'activity' => 'Arrival & Check-in Hotel', 'time' => '14:00'],
                ['day' => 2, 'activity' => 'Umrah Wajib (Tawaf, Sai, Tahallul)', 'time' => '08:00'],
                ['day' => 3, 'activity' => 'City Tour Makkah', 'time' => '07:30'],
            ]
        ]);
    }

    public function prayers()
    {
        return response()->json([
            ['title' => 'Doa Masuk Masjid', 'arabic' => '...', 'translation' => '...'],
            ['title' => 'Doa Tawaf', 'arabic' => '...', 'translation' => '...'],
            ['title' => 'Doa Sai', 'arabic' => '...', 'translation' => '...'],
        ]);
    }

    public function gallery()
    {
        return response()->json([
            ['url' => 'https://example.com/kaaba.jpg', 'caption' => 'Moment at Kaaba'],
            ['url' => 'https://example.com/group.jpg', 'caption' => 'Group Photo'],
        ]);
    }
}
