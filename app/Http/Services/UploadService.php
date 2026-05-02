<?php

namespace App\Http\Services;

use App\Models\Plan;
use Illuminate\Http\Request;

class UploadService
{
    public function uploadImg(Request $request)
    {
        $image = $request->file('img');
        $imageName = uniqid('pic_') . '.' . $image->extension();
        $path = $image->storeAs('images', $imageName, 'public');

        return $path;
    }

    public function uploadFileCompetition(Request $request)
    {
        $image = $request->file('provision');
        $imageName = uniqid('provision_') . '.' . $image->extension();
        $path = $image->storeAs('provisions', $imageName, 'public');

        return $path;
    }

    public function uploadFileAchievement(Request $request)
    {
        $image = $request->file('file');
        $imageName = uniqid('achievement_') . '.' . $image->extension();
        $path = $image->storeAs('achievements', $imageName, 'public');

        return $path;
    }

    public function uploadQualification(Request $request)
    {
        $image = $request->file('img');
        $imageName = uniqid('qual_') . '.' . $image->extension();
        $path = $image->storeAs('qualifications', $imageName, 'public');

        return $path;
    }

    public function uploadPlan(Request $request)
    {
        $image = $request->file('file');
        $imageName = uniqid('plan_') . '.' . $image->extension();
        $path = $image->storeAs('plans', $imageName, 'public');

        return $path;
    }
}
