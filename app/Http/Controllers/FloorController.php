<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FloorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin,admin');
    }

    public function index()
    {
        $floors = Floor::with('rooms')->orderBy('name')->get();
        return view('pages.floor.index', compact('floors'));
    }

    public function create()
    {
        return view('pages.floor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $floorData = $request->except(['serial_number']);
        $floorData['serial_number'] = Floor::generateSerialNumber();

        Floor::create($floorData);

        return redirect()->route('floor.index')
            ->with(['message' => 'Floor created successfully', 'alert' => 'alert-success']);
    }

    public function storeAjax(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $floorData = $request->except(['serial_number']);
            $floorData['serial_number'] = Floor::generateSerialNumber();

            $floor = Floor::create($floorData);

            return response()->json([
                'success' => true,
                'message' => 'Floor added successfully',
                'floor' => [
                    'id' => $floor->id,
                    'name' => $floor->name,
                    'serial_number' => $floor->serial_number,
                    'description' => $floor->description
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
                'message' => 'An error occurred while adding the floor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Floor $floor)
    {
        $floor->load('rooms');
        return view('pages.floor.show', compact('floor'));
    }

    public function edit(Floor $floor)
    {
        return view('pages.floor.edit', compact('floor'));
    }

    public function update(Request $request, Floor $floor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $updateData = $request->except(['serial_number']);
        $floor->update($updateData);

        return redirect()->route('floor.index')
            ->with(['message' => 'Floor updated successfully', 'alert' => 'alert-success']);
    }

    public function destroy(Floor $floor)
    {
        // Check if floor has rooms
        if ($floor->rooms()->count() > 0) {
            return redirect()->route('floor.index')
                ->with(['message' => 'Cannot delete floor with existing rooms', 'alert' => 'alert-danger']);
        }

        $floor->delete();

        return redirect()->route('floor.index')
            ->with(['message' => 'Floor deleted successfully', 'alert' => 'alert-success']);
    }
}
