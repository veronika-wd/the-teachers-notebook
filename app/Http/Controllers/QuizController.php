<?php

namespace App\Http\Controllers;

use App\Exports\QuizScheduleExport;
use App\Models\Quiz;
use App\Models\QuizLevel;
use App\Models\QuizType;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Monolog\Level;
use Nette\Utils\Type;

class QuizController extends Controller
{
    public function index()
    {
        $schoolClasses = SchoolClass::all()->sortBy(function($class) {
            preg_match('/^\d+/', $class->name, $matches);
            return $matches[0] ?? 0;
        })->values();
        $levels = QuizLevel::all()->sortBy('name');
        $types = QuizType::all();

        return view('quiz.index', [
            'schoolClasses' => $schoolClasses,
            'levels' => $levels,
            'types' => $types,
        ]);
    }

    public function show(SchoolClass $schoolClass)
    {
        $quizzes = Quiz::query()->where('school_class_id', $schoolClass->id)->get();
        $subjects = Subject::all()->sortBy('name');
        $levels = QuizLevel::all()->sortBy('name');
        $types = QuizType::all();

        return view('quiz.show', [
            'schoolClass' => $schoolClass,
            'quizzes' => $quizzes,
            'subjects' => $subjects,
            'levels' => $levels,
            'types' => $types,
        ]);
    }

    public function store(SchoolClass $schoolClass, Request $request)
    {
        Quiz::create([
            'school_class_id' => $schoolClass->id,
            'subject_id' => $request->subject,
            'date' => $request->date,
            'quiz_level_id' => $request->level,
            'quiz_type_id' => $request->type,
        ]);

        return redirect()->route('quizzes.show', $schoolClass);
    }

    public function storeLevel(Request $request)
    {
        QuizLevel::create([
            'name' => $request->name,
            'description' => $request->description ?? null,
        ]);

        return redirect()->route('quizzes.index');
    }

    public function storeType(Request $request)
    {
        QuizType::create([
            'name' => $request->name,
        ]);

        return redirect()->back();
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->back();
    }

//    public function exportExcel()
//    {
//        // 1. Получаем данные с обязательной загрузкой связей
//        $quizzes = Quiz::with(['subject', 'schoolClass'])
//            ->orderBy('school_class_id')
//            ->orderBy('date')
//            ->get();
//
//        // 2. Генерируем и скачиваем файл
//        return Excel::download(
//            new QuizScheduleExport($quizzes),
//            'расписание_контрольных_' . now()->format('Y-m-d') . '.xlsx'
//        );
//    }
    public function destroyLevel(QuizLevel $level)
    {
        $level->delete();

        return redirect()->back();
    }

    public function destroyType(QuizType $type)
    {
        $type->delete();

        return redirect()->back();
    }
}
