@extends('layout')
@section('title', 'Добавить изменения')
@section('content')
    <h2>Добавить изменения в расписание</h2>
    <hr>
    <form action="{{ route('schedule.store') }}" method="post"
          class="mb-3">
        @csrf
        <div class="row g-3">
            <div class="col-sm-12 col-lg-2">
                <label for="class">Выберите класс:</label>
                <select name="class" id="class" class="form-control">
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-lg-2">
                <label for="subject">Выберите предмет:</label>
                <select name="subject" id="subject" class="form-control">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-lg-2">
                <label for="user">Выберите учителя:</label>
            <select name="user" id="user" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
            <div class="col-sm-12 col-lg-2">
                <label for="cabinet">Введите кабинет:</label>
                <input type="number" name="cabinet" class="form-control" required>
            </div>
            <div class="col-sm-12 col-lg-2">
                <label for="number">Ввведите номер по порядку:</label>
                <input type="number" name="number" class="form-control" required>
            </div>
            <div class="col-sm-12 col-lg-2">
                <label for="date">Введите дату:</label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="col-sm-12 col-lg-2">
                <button type="submit" class="btn btn--primary">Создать изменение</button>
            </div>
        </div>
    </form>
    <h4>Все изменения</h4>
    <table class="table">
        <thead>
        <tr>
            <th>Класс</th>
            <th>Урок</th>
            <th>Кабинет</th>
            <th>Номер</th>
            <th>Дата</th>
        </tr>
        </thead>
        <tbody>
        @foreach($changes as $change)
            <tr>
                <td>{{ $change->schoolClass->name }}</td>
                <td>{{ $change->subject->name }}</td>
                <td>{{ $change->cabinet }}</td>
                <td>{{ $change->number }}</td>
                <td>{{ $change->date }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
