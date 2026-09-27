<?php

namespace App\Http\Controllers;

use App\Http\Services\UploadService;
use App\Models\Program;
use App\Models\Qualification;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::all();

        return view('programs', [
            'programs' => $programs,
        ]);
    }

    public function store(Request $request)
    {
        Program::create([
            'name' => $request->name,
            'link' => $request->link,
        ]);

        return redirect()->back();
    }

    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()->back();
    }
}
