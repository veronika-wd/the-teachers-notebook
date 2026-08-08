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
        $qualifications = Qualification::query()->where('date_end', '<' ,'2090-01-01')->get();

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

    public function degree()
    {
        $qualifications = Qualification::query()->where('date_end', '2090-01-01')->get();
        return view('qualifications.diploms', [
            'qualifications' => $qualifications,
        ]);
    }

    public function store(Request $request)
    {
        Qualification::create([
            'title' => $request->title,
            'image' => $request->link,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end ? $request->date_end : '2090-01-01',
            'user_id' => auth()->id(),
        ]);

        return redirect()->back();
    }

    public function destroy(Qualification $qualification)
    {
        $qualification->delete();

        return redirect()->back();
    }
}
