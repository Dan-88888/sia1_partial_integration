<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::paginate(15);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rooms',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:50',
            'capacity' => 'nullable|integer|min:1',
        ]);

        Room::create($request->all());
        return redirect()->route('admin.rooms.index')->with('success', 'Room created successfully.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rooms,name,' . $room->id,
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:50',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $room->update($request->all());
        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        if ($room->sections()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete room with existing sections.');
        }

        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted successfully.');
    }
}
