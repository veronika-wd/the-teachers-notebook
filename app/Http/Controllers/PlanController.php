<?php

namespace App\Http\Controllers;

use App\Http\Services\UploadService;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function __construct(
        protected UploadService $uploadService
    )
    {
    }
    public function addPlan(Request $request)
    {
        $plans = Plan::all();
        if($plans->count() > 0){
            foreach ($plans as $plan) {
                $plan->delete();
            }
        }
        Plan::create([
            'path' => $this->uploadService->uploadPlan($request),
        ]);
        return redirect()->back();
    }

    public function index()
    {
        $plan = Plan::first();
        return view('calendar', [
            'plan' => $plan
        ]);

    }
}
