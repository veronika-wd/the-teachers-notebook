<?php

namespace App\Http\Controllers;

use App\Models\Change;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $primarySchoolQuery = Schedule::query()->where('class', '<=', 4)->orderBy('class');
        $highSchoolQuery = Schedule::query()->where('class', '>', 4)->orderBy('class');

        $now = Carbon::now()->dayOfWeek() < 6 ? Carbon::now()->dayOfWeek() : 5;

        $day = $request->filled('day') ? $request->day : $now;
        $primarySchoolQuery->where('day', $day);
        $highSchoolQuery->where('day', $day);
        $primarySchoolAll = $primarySchoolQuery->get();
        $highSchoolAll = $highSchoolQuery->get();

        $primarySchool = $primarySchoolAll->groupBy('number');
        $highSchool = $highSchoolAll->groupBy('number');

        return view('schedule', [
            'primarySchool' => $primarySchool,
            'highSchool' => $highSchool,
        ]);
    }

    public function edit()
    {
        $users = User::all();
        $subjects = Subject::all();
        $classes = SchoolClass::all();
        $changes = Change::all();

        return view('schedules.index', [
            'users' => $users,
            'subjects' => $subjects,
            'classes' => $classes,
            'changes' => $changes,
        ]);
    }

    public function store(Request $request)
    {
        Change::create([
            'school_class_id' => $request->class,
            'subject_id' => $request->subject,
            'user_id' => $request->user,
            'cabinet' => $request->cabinet,
            'number' => $request->number,
            'date' => $request->date,
        ]);

        return redirect()->route('schedule.edit');
    }
}
