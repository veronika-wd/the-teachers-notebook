@extends('layout')
@section('title', $competition->title)

@section('content')
    <h2>{{ $competition->title }}</h2>
    <hr>

    <!-- Сообщения об успехе/ошибке -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <p>{{ $competition->description }}</p>

            <h3>Участники</h3>
            <hr>
            <h4>Добавить ребенка</h4>
            <form action="{{ route('competitions.addStudent', $competition) }}" method="post" class="w-50 d-flex gap-3 align-items-center justify-content-start mb-3">
                @csrf
                <select name="student" id="student" class="form-control w-75">
                    @foreach($studentsAll as $student)
                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn--primary w-25">Добавить ребенка</button>
            </form>
            <div class="row g-2">
                @foreach($students as $student)
                    <div class="col-lg-2 d-flex gap-3 align-items-center justify-content-center">
                        <a href="{{ route('students.show', $student) }}" class="btn btn--outline">{{ $student->name }}</a>
                        <form action="{{ route('competitions.removeStudent', $competition) }}" method="post">
                            @csrf
                            <input type="hidden" value="{{ $student->id }}" name="student">
                            <button type="submit" class="btn btn-outline-danger">x</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-sm-12 col-lg-6">
            <h3>Расписание</h3>
            <p>Регистрация: <br>
                {{  $competition->register_start . ' - ' . $competition->register_end }}
            </p>
            <p>Конкурс: <br>
                {{ $competition->competition_start . ' - ' . $competition->competition_end }}
            </p>
        </div>
    </div>

    <!-- Секция файлов конкурса -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-folder2-open me-2" style="color: #8B46A8;"></i>
                        Файлы конкурса
                    </h5>
                </div>
                <div class="card-body">
                    @admin
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">
                                <i class="bi bi-cloud-upload me-2"></i>
                                Загрузить документ
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('competitions.uploadFile', $competition) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row align-items-center">
                                    <div class="col-md-8 mb-3 mb-md-0">
                                        <label for="file" class="form-label">Выберите файл</label>
                                        <input type="file"
                                               class="form-control form-control-lg @error('file') is-invalid @enderror"
                                               id="file"
                                               name="file"
                                               accept=".doc,.docx,.odt,.xls,.xlsx,.csv,.pdf,.txt,.rtf"
                                               required>
                                        <div class="form-text">
                                            Допустимые форматы: DOC, DOCX, ODT, XLS, XLSX, CSV, PDF, TXT, RTF.
                                            Максимальный размер: 10 МБ
                                        </div>
                                        @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn--primary btn-lg w-100">
                                            Загрузить
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endadmin
                    <!-- Список файлов -->
                    @if($files->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th style="width: 50%;">Название файла</th>
                                    <th style="width: 20%;">Размер</th>
                                    <th style="width: 20%;">Дата загрузки</th>
                                    <th style="width: 10%;" class="text-center">Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($files as $file)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-text fs-5 me-3" style="color: #8B46A8;"></i>
                                                <div>
                                                    <div class="fw-semibold">{{ $file->original_name }}</div>
                                                    <small class="text-muted">{{ $file->mime_type }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                            <span class="badge bg-light text-dark">
                                @if($file->size >= 1048576)
                                    {{ number_format($file->size / 1048576, 2) }} МБ
                                @else
                                    {{ number_format($file->size / 1024, 2) }} КБ
                                @endif
                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $file->created_at->format('d.m.Y') }}</div>
                                            <small class="text-muted">{{ $file->created_at->format('H:i') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('competitions.downloadFile', $file->id) }}"
                                               class="btn btn-sm btn--primary me-1"
                                               title="Скачать">
                                                Скачать
                                            </a>

                                            <!-- Простая кнопка удаления без подтверждения -->
                                            <form action="{{ route('competitions.deleteFile', $file->id) }}"
                                                  method="POST"
                                                  style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Удалить">
                                                    X
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-folder display-4 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">Файлы не загружены</p>
                        </div>
                    @endif
        </div>
    </div>

        <style>
            .btn--primary {
                background-color: #8B46A8;
                border-color: #8B46A8;
                color: white;
            }

            .btn--primary:hover {
                background-color: #753a8f;
                border-color: #753a8f;
                color: white;
            }

            .btn--outline {
                border: 2px solid #8B46A8;
                color: #8B46A8;
                background: transparent;
            }

            .btn--outline:hover {
                background-color: #8B46A8;
                color: white;
            }
        </style>
@endsection
