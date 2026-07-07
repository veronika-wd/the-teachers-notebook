@extends('layout')
@section('title', $guardian->name)
@section('content')
    <style>
        .hidden {
            display: none;
        }
    </style>
    <h2>Профиль родителя: {{ $guardian->full_name }}</h2>
    <hr>
    <h4>Данные:</h4>
    <button type="submit" id="edit" class="btn btn--primary mb-3">Изменить профиль</button>
    <form action="{{ route('guardian.destroy', $guardian) }}" method="post">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn--secondary mb-3">Удалить родителя</button>
    </form>
    <div id="dataWrapper" class="row g-3">
        <div class="col-sm-12 col-lg-12">
            <h2>{{ $guardian->full_name }}</h2>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="data__element">
                <p>Телефон: {{ $guardian->phone }}</p>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="data__element">

                <p>Статус семьи: {{ $guardian->status }}</p>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="data__element">

                <p>Место работы: {{ $guardian->job }}</p>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="data__element">

                <p>Адрес: {{ $guardian->address }}</p>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="data__element">

                <p>СНИЛС: {{ $guardian->snils }}</p>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="data__element">

                <p>ИНН: {{ $guardian->inn }}</p>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="data__element">

                <p>Паспортные данные: {{ $guardian->passport_data }}</p>
            </div>
        </div>

    </div>

    <form action="{{ route('guardians.update', $guardian) }}" method="post" id="formUpdate" class="row g-3 hidden">
        @csrf
        @method('PATCH')
        <div class="col-sm-12 col-lg-3">
            <label class="form-label">ФИО</label>
            <input type="text" name="full_name" value="{{ $guardian->full_name }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-3">
            <label class="form-label">Телефон</label>
            <input type="tel" name="phone" value="{{ $guardian->phone }}" class="form-control phone">
        </div>
        <div class="col-sm-12 col-lg-3">
            <label class="form-label">Статус</label>
            <input type="text" name="status" value="{{ $guardian->status }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-3">
            <label class="form-label">Место работы</label>
            <input type="text" name="job" value="{{ $guardian->job }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-3">
            <label class="form-label">Адрес</label>
            <input type="text" name="address" value="{{ $guardian->address }}" class="form-control" required>
        </div>
        <div class="col-sm-12 col-lg-3">
            <label for="surname" class="form-label">СНИЛС</label>
            <input type="text" name="snils" value="{{ $guardian->snils }}" class="form-control snils">
        </div>
        <div class="col-sm-12 col-lg-3">
            <label for="surname" class="form-label">ИНН</label>
            <input type="text" name="inn" value="{{ $guardian->inn }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-3">
            <label for="surname" class="form-label">Паспортные данные</label>
            <input type="text" name="passport_data" value="{{ $guardian->passport_data }}" class="form-control">
        </div>
        <button type="submit" class="btn btn--primary col-sm-12 col-lg-12">Отправить</button>
    </form>
    <h3 class="mt-3">Дети</h3>
    <hr>
    <h4>Добавить ребенка</h4>
    <form action="{{ route('guardians.students.store', $guardian) }}" method="post" class="w-50 d-flex gap-3 align-items-center justify-content-start mb-3">
        @csrf
        <select name="student" id="student" class="form-control w-50">
            @foreach($studentsAll as $student)
                <option value="{{ $student->id }}">{{ $student->surname . ' ' . $student->name . ' ' . $student->patronymic }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn--primary w-50">Добавить ребенка</button>
    </form>
    <div class="row g-2">
        @foreach($students as $student)
            <div class="col-lg-2">
                <a href="{{ route('students.show', $student) }}" class="btn btn--outline">{{ $student->surname . ' ' . $student->name . ' ' . $student->patronymic }}</a>
            </div>
        @endforeach
    </div>
    <script src="{{ asset('scripts/profile.js') }}"></script>
    <script src="{{ asset('scripts/validation.js') }}"></script>
@endsection
