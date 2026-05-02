<?php

namespace App\Http\Controllers;

use App\Http\Services\UploadService;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{

    // Показать страницу со списком документов
    public function index()
    {
        $documents = Document::latest()->get(); // Получаем все документы, новые сверху
        return view('documents', compact('documents'));
    }

    // Загрузка файла
    public function store(Request $request)
    {
        // Валидация
        $request->validate([
            'file' => 'required|file|mimes:doc,docx,odt,xls,xlsx,csv,pdf|max:10240', // max 10MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Генерируем уникальное имя для хранения, чтобы файлы не перезаписывали друг друга
            $path = $file->store('documents', 'public'); // Сохраняем в storage/app/public/documents

            // Создаем запись в БД
            Document::create([
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            return redirect()->back()->with('success', 'Файл успешно загружен!');
        }

        return redirect()->back()->with('error', 'Ошибка при загрузке файла.');
    }

    // Скачивание файла
    public function download(Document $document)
    {
        // Проверка существования файла на диске
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'Файл не найден на сервере');
        }

        // Возвращаем файл для скачивания с оригинальным именем
        return Storage::disk('public')->download($document->file_path, $document->original_name);
    }

    // (Опционально) Удаление файла
    public function destroy(Document $document)
    {

        // Удаляем физический файл
        Storage::disk('public')->delete($document->file_path);

        // Удаляем запись из БД
        $document->delete();

        return redirect()->back()->with('success', 'Файл удален.');
    }
}
