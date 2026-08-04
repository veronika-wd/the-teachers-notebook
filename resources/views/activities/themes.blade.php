@extends('layout')

@section('title', "{$activity->name} — Темы занятий")

@section('content')
    <div class="container py-4">

        {{-- Заголовок --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-0">{{ $activity->name }}</h3>
                <small class="text-muted">
                    {{ $activity->schedule_label }}
                    @if($activity->room) | Каб. {{ $activity->room }} @endif
                </small>
            </div>
            <div>
                <form action="{{ route('activities.destroy', $activity) }}" method="post" onsubmit="return confirm('Удалить этот кружок?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger mb-3">Удалить кружок</button>
                </form>
                <a href="{{ route('activities.classes.index') }}" class="btn btn-secondary">
                    ← Назад
                </a>
            </div>
        </div>

        {{-- Вкладки --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('activities.attendance', $activity) }}">
                    Журнал посещаемости
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('activities.themes', $activity) }}">
                    Темы занятий
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('activities.enrollment', $activity) }}">
                    Управление записью
                </a>
            </li>
        </ul>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            {{-- Форма добавления темы --}}
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-white fw-semibold">
                        ➕ Добавить тему
                    </div>
                    <div class="card-body">
                        <form action="{{ route('activities.themes.store', $activity) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">
                                    Дата занятия <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="date"
                                       class="form-control form-control-sm @error('date') is-invalid @enderror"
                                       value="{{ old('date', date('Y-m-d')) }}"
                                       required>
                                @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Выберите дату, когда проводилось занятие
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">
                                    Название темы <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name"
                                       class="form-control form-control-sm @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="Например: Основы композиции"
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Описание</label>
                                <textarea name="description" rows="3"
                                          class="form-control form-control-sm"
                                          placeholder="Подробности занятия...">{{ old('description') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-purple btn-sm w-100">
                                Добавить тему
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Список тем --}}
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        Список тем ({{ $themes->total() }})
                    </div>
                    <div class="card-body p-0">
                        @forelse($themes as $theme)
                            <div class="d-flex align-items-start p-3 border-bottom hover-bg">
                                <div class="me-3 text-center" style="min-width: 60px;">
                                    <div class="badge bg-purple text-white rounded-pill">
                                        {{ $theme->date->format('d.m') }}
                                    </div>
                                    <div class="text-muted small mt-1">
                                        {{ $theme->date->format('Y') }}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">{{ $theme->name }}</h6>
                                    @if($theme->description)
                                        <p class="text-muted small mb-0">{{ $theme->description }}</p>
                                    @endif
                                </div>
                                <form action="{{ route('activities.themes.destroy', [$activity, $theme]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Удалить эту тему?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">
                                <p class="mb-0">Темы ещё не добавлены.</p>
                                <small>Используйте форму слева для добавления.</small>
                            </div>
                        @endforelse
                    </div>

                    @if($themes->hasPages())
                        <div class="card-footer bg-white">
                            {{ $themes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <style>
        .btn-purple {
            background-color: #7c3aed;
            color: white;
            border: none;
        }
        .btn-purple:hover {
            background-color: #6d28d9;
            color: white;
        }
        .bg-purple {
            background-color: #7c3aed !important;
        }
        .hover-bg:hover {
            background-color: #f8f9ff;
        }
    </style>
@endsection
