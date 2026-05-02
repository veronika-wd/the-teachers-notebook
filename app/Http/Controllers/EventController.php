<?php

// app/Http/Controllers/EventController.php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

// Фильтрация по дате для оптимизации
        if ($request->has('start') && $request->has('end')) {
            $query->whereBetween('date_start', [$request->start, $request->end]);
        }

        $events = $query->get()->map->toCalendarFormat();

        return response()->json($events);
    }

    public function getBirthdays(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $birthdays = User::selectRaw('DAY(birth_date) as day, name')
            ->whereMonth('birth_date', $month)
            ->get()
            ->groupBy('day')
            ->map(fn($p) => $p->pluck('name')->toArray());

        return response()->json($birthdays);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'description' => 'nullable|string',
            'color' => 'nullable|regex:/^#[0-9A-F]{6}$/i'
        ]);

        $event = Event::create([
            ...$validated,
            'user_id' => Auth::id(), // если нужна привязка
        ]);

        return response()->json($event->toCalendarFormat(), 201);
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'description' => 'nullable|string',
            'color' => 'nullable|regex:/^#[0-9A-F]{6}$/i'
        ]);

        $event->update($validated);

        return response()->json($event->toCalendarFormat());
    }

    public function destroy(Event $event)
    {
// Проверка прав: $event->user_id !== Auth::id()
        $event->delete();

        return response()->json(null, 204);
    }


    public function getCountsByDate(Request $request)
    {
        $validated = $request->validate([
            'year' => 'nullable|integer|min:2000|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
        ]);

        $year = $validated['year'] ?? now()->year;
        $month = $validated['month'] ?? now()->month;

        $eventsCount = Event::selectRaw('DATE(date_start) as date, COUNT(*) as count')
            ->whereYear('date_start', $year)
            ->whereMonth('date_start', $month)
            ->groupBy('date')
            ->pluck('count', 'date');

        return response()->json($eventsCount);
    }
}
