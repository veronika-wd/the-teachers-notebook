@extends('layout')

@section('title', 'Выбор класса')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Классы</h1>

        <div class="row">
            @forelse($schoolClasses as $class)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $class->name }}</h5>
                            <p class="card-text text-muted">
                                {{ $class->students()->count() }} учеников
                            </p>
                            <a href="{{ route('quizzes.show', $class) }}"
                               class="btn btn--primary">
                                Открыть расписание
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

        <div class="row">
            <div class="col-sm-12 col-lg-6">
                <h3>Виды КР</h3>
                <form action="{{ route('quizzes.storeLevel') }}" method="post" class="d-flex justify-content-between align-items-end mb-3 gap-2">
                    @csrf
                    <div class="form-group w-100">
                        <label for="name" class="form-label">Добавить новый вид</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Введите название" required>
                        <input type="text" name="description" id="description" class="form-control mt-2" placeholder="Введите расшифровку (необязательно)" >
                    </div>
                    <button type="submit" class="btn btn--primary">Добавить</button>
                </form>
                <ul class="list-group">
                    @foreach($levels as $level)
                        <li class="list-group-item d-flex align-items-center">{{ $level->name }}
                            <form action="{{ route('quizzes.destroyLevel', $level) }}" method="post">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn delete-btn">X</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-sm-12 col-lg-6">
                <h3>Типы</h3>
                <form action="{{ route('quizzes.storeType') }}" method="post" class="d-flex justify-content-between align-items-end mb-3 gap-2">
                    @csrf
                    <div class="form-group w-100">
                        <label for="name" class="form-label">Добавить новый тип</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Введите название" required>
                    </div>
                    <button type="submit" class="btn btn--primary">Добавить</button>
                </form>
                <ul class="list-group">
                    @foreach($types as $type)
                        <li class="list-group-item d-flex align-items-center">{{ $type->name }}
                            <form action="{{ route('quizzes.destroyType', $type) }}" method="post">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn delete-btn">X</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
