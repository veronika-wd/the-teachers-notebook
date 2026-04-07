<?php

namespace App\Http\Controllers;

use App\Models\Achievement;

class AchievementController extends Controller
{
    public function all()
    {
        $achievements = Achievement::all();

        return view('achievements', [
            'achievements' => $achievements
        ]);
    }
}
