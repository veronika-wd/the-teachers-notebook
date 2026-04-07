<?php

namespace App\Http\Controllers;

use App\Http\Services\UploadService;
use App\Models\Qualification;
use Illuminate\Http\Request;

class QualificationController extends Controller
{
    public function __construct(
        protected UploadService $uploadService
    )
    {
    }

    public function index()
    {
        $qualifications = Qualification::query()->where('user_id', auth()->id())->orderByDesc('id')->get();

        return view('qualifications.index', [
            'qualifications' => $qualifications,
        ]);
    }

    public function all()
    {
        return view('qualifications', [
            'qualifications' => Qualification::all(),
        ]);
    }

    public function store(Request $request)
    {
        Qualification::create([
            'title' => $request->title,
            'image' => $this->uploadService->uploadQualification($request),
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('qualifications.index');
    }
}
