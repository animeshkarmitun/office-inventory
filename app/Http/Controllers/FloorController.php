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
            'serial_number' => 'required|string|max:255|unique:floors,serial_number',
            'description' => 'nullable|string',
        ]);

        Floor::create($request->all());

        return redirect()->route('floor.index')
            ->with(['message' => 'Floor created successfully', 'alert' => 'alert-success']);
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
            'serial_number' => 'required|string|max:255|unique:floors,serial_number,' . $floor->id,
            'description' => 'nullable|string',
        ]);

        $floor->update($request->all());

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
