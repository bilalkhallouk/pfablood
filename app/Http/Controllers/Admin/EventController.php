<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['center', 'participants'])
            ->orderBy('date', 'desc')
            ->paginate(9);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $centers = Center::all();
        return view('admin.events.create', compact('centers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'center_id' => 'required|exists:centers,id',
            'capacity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_featured' => 'boolean'
        ]);

        $data = $request->except('image');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $data['image'] = $path;
        }

        // Combine date and time
        $data['date'] = Carbon::parse($request->date . ' ' . $request->time);
        
        Event::create($data);

        return redirect()->route('admin.events')
            ->with('success', 'Événement créé avec succès.');
    }

    public function edit(Event $event)
    {
        $centers = Center::all();
        return view('admin.events.edit', compact('event', 'centers'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'center_id' => 'required|exists:centers,id',
            'capacity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_featured' => 'boolean'
        ]);

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $path = $request->file('image')->store('events', 'public');
            $data['image'] = $path;
        }

        // Combine date and time
        $data['date'] = Carbon::parse($request->date . ' ' . $request->time);

        $event->update($data);

        return redirect()->route('admin.events')
            ->with('success', 'Événement mis à jour avec succès.');
    }

    public function destroy(Event $event)
    {
        // Delete image if exists
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Événement supprimé avec succès.']);
        }

        return redirect()->route('admin.events')
            ->with('success', 'Événement supprimé avec succès.');
    }
} 