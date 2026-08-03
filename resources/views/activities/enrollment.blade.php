@extends('layout')

@section('title', "{$activity->name} — Управление записью")

@section('content')
    <div class="container py-4">

        {{-- Заголовок --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-0">{{ $activity->name }}</h3>
                <small class="text-muted">
                    {{ $activity->schedule_label }}
                    | Зачислено: {{ $enrolledIds ? count($enrolledIds) : 0 }}
                </small>
            </div>
            <a href="{{ route('activities.classes.index') }}" class="btn btn-secondary btn-sm">
                ← Назад
            </a>
        </div>

        {{-- Вкладки --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('activities.attendance', $activity) }}">
                    Журнал посещаемости
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('activities.themes', $activity) }}">
                    Темы занятий
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('activities.enrollment', $activity) }}">
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

        {{-- Таблица учеников --}}
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>ФИО ученика</th>
                        <th>Класс</th>
                        <th class="text-center" style="width: 150px;">Статус</th>
                        <th class="text-center" style="width: 120px;">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($students as $index => $student)
                        <tr class="{{ $student->is_enrolled ? 'table-success' : '' }}">
                            <td class="text-muted">{{ $index + 1 }}</td>
                            <td class="fw-semibold">
                                {{ $student->surname }}
                                {{ $student->name }}
                                {{ $student->patronymic }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $student->schoolClass->name }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($student->is_enrolled)
                                    <span class="badge bg-success">Зачислен</span>
                                @else
                                    <span class="badge bg-secondary">Не записан</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('activities.enrollment.toggle', $activity) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    @if($student->is_enrolled)
                                        <button type="submit"
                                                class="btn btn-outline-danger"
                                                onclick="return confirm('Отчислить ученика из кружка?')">
                                            Удалить
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-success btn-sm">
                                            Зачислить
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Ученики не найдены
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
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
    </style>
@endsection
