@extends('layout')

@section('title', 'Выбор класса')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Табели столовой</h1>

        <div class="row">
            @forelse($classes as $class)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $class->name }}</h5>
                            <p class="card-text text-muted">
                                {{ $class->students()->count() }} учеников
                            </p>
                            <a href="{{ route('attendance.show', $class) }}"
                               class="btn btn--primary">
                                Открыть табель
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        Классы еще не созданы
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
