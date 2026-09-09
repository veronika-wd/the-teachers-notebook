@extends('layout')
@section('title', 'Контрольные работы ' . $schoolClass->name . ' класс')
@section('content')
    <div class="row">
        <div class="col-lg-6 col-sm-12">
            <h2>Контрольные работы</h2>
            <h3 class="text-secondary">{{ $schoolClass->name . ' класс' }}</h3>
        </div>
        <div class="col-lg-6 col-sm-12 d-flex justify-content-end align-items-center">
            <a href="{{ route('quizzes.index') }}" class="btn btn--outline">Назад</a>
        </div>
    </div>
    <hr>
    <h3>Справка</h3>
    @foreach($levels as $level)
        @if($level->description)
            <p>{{ $level->name . ' - ' . $level->description }}</p>
        @endif
    @endforeach
    <hr>
    <form action="{{ route('quizzes.store', $schoolClass) }}" method="post"
          class="mb-3">
        <h3>Добавить</h3>
        @csrf
        <div class="row g-3 d-flex align-items-end">
            <div class="col-sm-12 col-lg-2">
                <label for="subject">Выберите предмет:</label>
                <select name="subject" id="subject" class="form-control">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-lg-2">
                <label for="date">Введите дату:</label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="col-sm-12 col-lg-2">
                <label for="cabinet">Выберите вид:</label>
                <select name="level" id="level" class="form-select" required>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-lg-2">
                <label for="cabinet">Выберите тип:</label>
                <select name="type" id="type" class="form-select" required>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-12 col-lg-2">
                <button type="submit" class="btn btn--primary">Добавить контрольную</button>
            </div>
        </div>
    </form>
    <h4>Все изменения</h4>
    <table class="table">
        <thead>
        <tr>
            <th>Предмет</th>
            <th>Дата</th>
            <th>Вид</th>
            <th>Тип</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($quizzes as $quiz)
            <tr>
                <td>{{ $quiz->subject->name }}</td>
                <td>{{ $quiz->date->format('d.m.Y') }}</td>
                <td>{{ $quiz->quizLevel->name }}</td>
                <td>{{ $quiz->quizType->name }}</td>
                <td>
                    <form action="{{ route('quizzes.destroy', $quiz) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
