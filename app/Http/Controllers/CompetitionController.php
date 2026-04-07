<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionFile;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::all();

        return view('competitions.index', [
            'competitions' => $competitions
        ]);
    }

    public function show(Competition $competition)
    {
        $students = $competition->students;
        $studentsAll = Student::all();
        $files = $competition->files()->latest()->get(); // Загружаем файлы конкурса

        return view('competitions.show', [
            'competition' => $competition,
            'students' => $students,
            'studentsAll' => $studentsAll,
            'files' => $files
        ]);
    }

    public function create()
    {
        return view('competitions.create');
    }

    public function store(Request $request)
    {
        $competition = Competition::create([
            'title' => $request->title,
            'description' => $request->description,
            'file' => $request->file,
            'register_start' => $request->register_start,
            'register_end' => $request->register_end,
            'competition_start' => $request->competition_start,
            'competition_end' => $request->competition_end,
        ]);

        return redirect()->route('competitions.show', $competition);
    }

    public function addStudent(Competition $competition, Request $request)
    {
        $student = Student::where('id', $request->student)->first();
        $competition->students()->attach($student);
        return redirect()->route('competitions.show', $competition);
    }

    public function removeStudent(Request $request, Competition $competition)
    {
        $student = Student::where('id', $request->student)->first();
        $competition->students()->detach($student);
        return redirect()->route('competitions.show', $competition);
    }

    // ===== НОВЫЕ МЕТОДЫ ДЛЯ РАБОТЫ С ФАЙЛАМИ КОНКУРСА =====

    /**
     * Загрузка файла к конкурсу
     */
    public function uploadFile(Request $request, Competition $competition)
    {
        $request->validate([
            'file' => 'required|file|mimes:doc,docx,odt,xls,xlsx,csv,pdf,txt,rtf,jpg,jpeg,png,zip,rar|max:20480', // max 20MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Сохраняем файл в папку competitions/{id}/
            $path = $file->store('competitions/' . $competition->id, 'public');

            // Создаём запись в БД
            CompetitionFile::create([
                'competition_id' => $competition->id,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            return redirect()->back()->with('success', 'Файл успешно загружен!');
        }

        return redirect()->back()->with('error', 'Ошибка при загрузке файла.');
    }

    /**
     * Скачивание файла конкурса
     */
    public function downloadFile(CompetitionFile $file)
    {
        // Проверяем существование файла
        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'Файл не найден на сервере');
        }

        // Отдаём файл с оригинальным именем
        return Storage::disk('public')->download($file->file_path, $file->original_name);
    }

    /**
     * Удаление файла конкурса
     */
    public function deleteFile(CompetitionFile $file)
    {
        // Удаляем физический файл
        Storage::disk('public')->delete($file->file_path);

        // Удаляем запись из БД
        $file->delete();

        return redirect()->back()->with('success', 'Файл успешно удалён!');
    }
}
