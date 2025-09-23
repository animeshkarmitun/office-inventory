<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin,admin');
    }

    public function index()
    {
        $rooms = Room::with('floor')->orderBy('floor_id')->orderBy('room_number')->get();
        return view('pages.room.index', compact('rooms'));
    }

    public function create()
    {
        $floors = Floor::orderBy('name')->get();
        return view('pages.room.create', compact('floors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'name' => 'required|string|max:255',
            'room_number' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        // Check if room number already exists in the same floor
        $existingRoom = Room::where('floor_id', $request->floor_id)
            ->where('room_number', $request->room_number)
            ->first();

        if ($existingRoom) {
            return back()->withErrors(['room_number' => 'Room number already exists in this floor.'])->withInput();
        }

        Room::create($request->all());

        return redirect()->route('room.index')
            ->with(['message' => 'Room created successfully', 'alert' => 'alert-success']);
    }

    public function storeAjax(Request $request)
    {
        try {
            $request->validate([
                'floor_id' => 'required|exists:floors,id',
                'name' => 'required|string|max:255',
                'room_number' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive,maintenance',
            ]);

            // Check if room number already exists in the same floor
            $existingRoom = Room::where('floor_id', $request->floor_id)
                ->where('room_number', $request->room_number)
                ->first();

            if ($existingRoom) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room number already exists in this floor.',
                    'errors' => ['room_number' => ['Room number already exists in this floor.']]
                ], 422);
            }

            $room = Room::create($request->all());
            $room->load('floor');

            return response()->json([
                'success' => true,
                'message' => 'Room added successfully',
                'room' => [
                    'id' => $room->id,
                    'name' => $room->name,
                    'room_number' => $room->room_number,
                    'description' => $room->description,
                    'status' => $room->status,
                    'floor_name' => $room->floor->name
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the room: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Room $room)
    {
        $room->load('floor');
        return view('pages.room.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $floors = Floor::orderBy('name')->get();
        return view('pages.room.edit', compact('room', 'floors'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'name' => 'required|string|max:255',
            'room_number' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        // Check if room number already exists in the same floor (excluding current room)
        $existingRoom = Room::where('floor_id', $request->floor_id)
            ->where('room_number', $request->room_number)
            ->where('id', '!=', $room->id)
            ->first();

        if ($existingRoom) {
            return back()->withErrors(['room_number' => 'Room number already exists in this floor.'])->withInput();
        }

        $room->update($request->all());

        return redirect()->route('room.index')
            ->with(['message' => 'Room updated successfully', 'alert' => 'alert-success']);
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('room.index')
            ->with(['message' => 'Room deleted successfully', 'alert' => 'alert-success']);
    }

    // Get rooms by floor (for AJAX requests)
    public function getByFloor($floorId)
    {
        $rooms = Room::where('floor_id', $floorId)->orderBy('room_number')->get();
        return response()->json($rooms);
    }
}
