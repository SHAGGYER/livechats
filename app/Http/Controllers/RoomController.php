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

    public function decreaseActive(Request $request)
    {
        $room = Room::find($request->room_id);

        if ($room->active > 0)
        {
            $room->active--;
            $room->save();
        }
    }

    public function updateActive(Request $request)
    {
        $room = Room::find($request->room_id);
        $room->active = $request->active;
        $room->save();
    }
}
