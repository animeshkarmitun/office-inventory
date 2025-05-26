<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\AssetMovement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($itemId)
    {
        $item = Item::with(['movements.fromUser', 'movements.toUser', 'movements.movedBy'])->findOrFail($itemId);
        return view('pages.asset.movement-history', compact('item'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($itemId)
    {
        $item = Item::findOrFail($itemId);
        $users = User::all();
        return view('pages.asset.move', compact('item', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $itemId)
    {
        $request->validate([
            'movement_type' => 'required|in:assignment,transfer,return,maintenance',
            'to_user_id' => 'required_if:movement_type,assignment,transfer|exists:users,id',
            'to_location' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $item = Item::findOrFail($itemId);
        
        // Create movement record
        $movement = AssetMovement::create([
            'item_id' => $item->id,
            'from_user_id' => $item->assigned_to,
            'to_user_id' => $request->to_user_id,
            'from_location' => $item->location,
            'to_location' => $request->to_location,
            'movement_type' => $request->movement_type,
            'notes' => $request->notes,
            'moved_by' => Auth::id()
        ]);

        // Update item location and assignment
        $item->update([
            'location' => $request->to_location,
            'assigned_to' => $request->to_user_id,
            'status' => $request->movement_type === 'maintenance' ? 'maintenance' : 'in_use'
        ]);

        return redirect()->route('item')
            ->with(['message' => 'Asset movement recorded successfully', 'alert' => 'alert-success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movement = AssetMovement::with(['item', 'fromUser', 'toUser', 'movedBy'])->findOrFail($id);
        return view('pages.asset.movement-details', compact('movement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
