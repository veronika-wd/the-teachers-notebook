@extends('layout') {{-- Или ваш основной layout --}}
@section('title', 'Документы')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Заголовок страницы -->
                <h2>Документы</h2>
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

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @admin
                <!-- Форма загрузки -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">
                            <i class="bi bi-cloud-upload me-2"></i>
                            Загрузить документ
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
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

                <!-- Список документов -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Список документов
                        </h5>
                        <span class="badge bg-purple">{{ $documents->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if($documents->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%;">Название файла</th>
                                        <th style="width: 15%;">Размер</th>
                                        <th style="width: 20%;">Дата загрузки</th>
                                        <th style="width: 25%;" class="text-center">Действия</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($documents as $doc)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-file-earmark-text fs-4 me-3 text-purple"></i>
                                                    <div>
                                                        <div class="fw-semibold">{{ $doc->original_name }}</div>
                                                        <small class="text-muted">{{ $doc->mime_type }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    @if($doc->size >= 1048576)
                                                        {{ number_format($doc->size / 1048576, 2) }} МБ
                                                    @else
                                                        {{ number_format($doc->size / 1024, 2) }} КБ
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <div>{{ $doc->created_at->format('d.m.Y') }}</div>
                                                <small class="text-muted">{{ $doc->created_at->format('H:i') }}</small>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('documents.download', $doc->id) }}"
                                                   class="btn btn-sm btn--primary me-1"
                                                   title="Скачать">Скачать
                                                </a>

                                                @admin
                                                <form action="{{ route('documents.destroy', $doc->id) }}"
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
                                                @endadmin
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-folder2-open display-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">Документы не загружены</h5>
                                <p class="text-muted">Загрузите первый документ, используя форму выше</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Стили для карточек */
        .card {
            border-radius: 10px;
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
            background-color: #fff;
        }

        /* Стили для таблицы */
        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
    </style>
@endsection
