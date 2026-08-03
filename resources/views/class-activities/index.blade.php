
@extends('layout')

@section('title', 'Внеурочная деятельность')

@section('content')
    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <h2 class="mb-4 fw-bold">Внеурочная деятельность</h2>

        {{-- ===== БЛОК 1: Табели по классам ===== --}}
        <h4 class="mb-3 text-muted">Табели по классам</h4>
        <div class="row g-3 mb-5">
            @forelse($classes as $schoolClass)
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-primary">
                                {{ $schoolClass->name }}
                            </h5>
                            <p class="text-muted mb-3">
                                {{ $schoolClass->students_count }} учеников
                            </p>
                            <a href="{{ route('activities.classes.show', $schoolClass) }}"
                               class="btn btn-purple w-100">
                                Открыть табель
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">Нет данных о классах.</div>
                </div>
            @endforelse
        </div>

        {{-- ===== БЛОК 2: Ссылки на кружки ===== --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 text-muted">Кружки</h4>
            <a href="{{ route('activities.create') }}" class="btn btn-success btn-sm">
                + Создать кружок
            </a>
        </div>

        <div class="row g-3">
            @forelse($activities as $activity)
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title fw-bold">{{ $activity->name }}</h6>
                            <p class="text-muted small mb-1">
                                {{ $activity->schedule_label }}
                                ({{ $activity->frequency }} раз/нед.)
                            </p>
                            <p class="text-muted small mb-2">
                                {{ $activity->students_count }} учеников
                            </p>
                            @if($activity->room)
                                <p class="text-muted small mb-2">
                                    Каб. {{ $activity->room }}
                                </p>
                            @endif

                            <div class="d-grid gap-1">
                                <a href="{{ route('activities.attendance', $activity) }}"
                                   class="btn btn-purple btn-sm">
                                    Посещаемость
                                </a>
                                <a href="{{ route('activities.themes', $activity) }}"
                                   class="btn btn-outline-secondary btn-sm">
                                    Темы занятий
                                </a>
                                <a href="{{ route('activities.enrollment', $activity) }}"
                                   class="btn btn-outline-secondary btn-sm">
                                    Управление записью
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        Кружки ещё не созданы.
                        <a href="{{ route('activities.create') }}">Создать первый кружок</a>
                    </div>
                </div>
            @endforelse
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
        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15) !important;
            transition: box-shadow 0.2s;
        }
    </style>
@endsection
