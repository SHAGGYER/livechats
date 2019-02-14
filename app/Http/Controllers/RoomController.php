<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function getRooms()
    {
        $rooms = Room::all();

        return response()->json($rooms, 200);
    }

    public function getRoomById($id)
    {
        $room = Room::find($id);
        if (!isset($room->id))
        {
            return response()->json(['error' => 404], 200);
        }

        return response()->json($room, 200);
    }
}
