@extends('layout')
@section('title', $student->surname . ' ' . $student->name)
@section('content')
    <style>
        .hidden{
            display: none;
        }
    </style>
    <h2>Профиль ученика: {{ $student->surname . ' ' . $student->name . ' ' . $student->patronymic }}</h2>
    <hr>
    <h4>Данные:</h4>
    <button type="submit" id="edit" class="btn btn--primary mb-3">Изменить профиль</button>
    <form action="{{ route('students.destroy', $student) }}" method="post">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn--secondary mb-3">Удалить ученика</button>
    </form>
    <p>Родители:
    @foreach($guardians as $guardian)
            <a href="{{ route('guardians.show', $guardian) }}" class="btn btn--outline">{{ $guardian->full_name }}</a>
    @endforeach
    </p>
    <h4>Добавить родителя</h4>
    <form action="{{ route('students.guardian.store', $student) }}" method="post" class="w-50 d-flex gap-3 align-items-center justify-content-start mb-3">
        @csrf
        <select name="guardian" id="guardian" class="form-control  w-50">
            @foreach($guardiansAll as $guardian)
                <option value="{{ $guardian->id }}">{{ $guardian->full_name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn--primary w-50">Добавить родителя</button>
    </form>
    <div id="dataWrapper" class="row g-3">
        <div class="col-sm-12 col-lg-10">
            <h2>{{ $student->surname . ' ' . $student->name . ' ' . $student->patronymic}}</h2>
        </div>
        <div class="col-sm-12 col-lg-2">
            <div>
                <h4>Класс: {{ $student->class }}</h4>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4">
            <div class="data__element">

                <p>СНИЛС: {{ $student->snils }}</p>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4">
            <div class="data__element">

                <p>ИНН: {{ $student->inn }}</p>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4">
            <div class="data__element">

                <p>Паспорт: {{ $student->passport_data }}</p>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4">
            <div class="data__element">

                <p>Дата рождения: {{ $student->birth_date->format('Y-m-d') }}</p>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="data__element">

                <p>Адрес: {{ $student->address }}</p>
            </div>
        </div>
        <div class="col-sm-12 col-lg-2">
            <div class="data__element">
                <p>Статус: {{ $student->status }}</p>
            </div>

        </div>
    </div>

    <form action="{{ route('students.update', $student) }}" method="post" id="formUpdate" class="row g-3 hidden">
        @csrf
        @method('PATCH')
        <div class="col-sm-12 col-lg-3">
            <label for="surname" class="form-label">Фамилия</label>
            <input type="text" name="surname" value="{{ $student->surname }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-3">
            <label for="surname" class="form-label">Имя</label>
            <input type="text" name="name" value="{{ $student->name }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-3">
            <label for="surname" class="form-label">Отчество</label>
            <input type="text" name="patronymic" value="{{ $student->patronymic }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-3">
            <label for="class" class="form-label">Класс</label>
            <select name="class" id="class" class="form-select">
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $student->class == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-12 col-lg-2">
            <label for="surname" class="form-label">СНИЛС</label>
            <input type="text" name="snils" value="{{ $student->snils }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-2">
            <label for="surname" class="form-label">ИНН</label>
            <input type="text" name="inn" value="{{ $student->inn }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-2">
            <label for="surname" class="form-label">Паспортные данные</label>
            <input type="text" name="passport_data" value="{{ $student->passport_data }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-2">
            <label for="surname" class="form-label">Дата рождения</label>
            <input type="date" name="birth_date" value="{{ $student->birth_date->format('Y-m-d') }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-2">
            <label for="surname" class="form-label">Адрес</label>
            <input type="text" name="address" value="{{ $student->address }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-2">
            <label for="surname" class="form-label">Статус</label>
            <input type="text" name="status" value="{{ $student->status }}" class="form-control">
        </div>
        <button type="submit" class="btn btn--primary col-sm-12 col-lg-12">Отправить</button>
    </form>
    <h3 class="mt-3">Достижения</h3>
    <hr>
    <h4>Добавить достижение</h4>
    <form action="{{ route('students.achievements.store', $student) }}" method="post" class="d-flex gap-3 align-items-end mb-4"
          enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Введите наименование:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="file">Загрузить файл:</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn--primary">Добавить</button>
    </form>
    <h4>Все достижения ученика</h4>
    <div class="row g-3">
        @foreach($achievements as $achievement)
            <div class="col-sm-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <p>{{ $achievement->name }}</p>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($achievement->path) }}" class="img-fluid" alt="img">
                        <p>{{ date_format($achievement->created_at, 'd/m/Y') }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <script src="{{ asset('scripts/profile.js') }}"></script>
@endsection
