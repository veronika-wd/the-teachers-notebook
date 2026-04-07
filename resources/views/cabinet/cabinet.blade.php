@extends('layout')
@section('title', 'Мой кабинет')
@section('content')
    <style>
        .hidden{
            display: none;
        }
    </style>
    <h2>Мой личный кабинет</h2>
    <hr>
    <button type="submit" id="edit" class="btn btn--primary mb-3">Изменить профиль</button>
    <a href="{{ route('cabinet.achievements.upload') }}" class="btn btn--primary mb-3">Мои достижения</a>
    <a href="{{ route('qualifications.index') }}" class="btn btn--primary mb-3">Мои квалификации</a>
    <div id="dataWrapper" class="row g-3">
        <div class="col-sm-12 col-lg-12">
            <h2>{{ $teacher->name }}</h2>
        </div>
        <div class="col-sm-12 col-lg-4 data__element">
            <p>Телефон: {{ $teacher->phone }}</p>
        </div>
        <div class="col-sm-12 col-lg-4 data__element">
            <p>Email: {{ $teacher->email }}</p>
        </div>
        <div class="col-sm-12 col-lg-4 data__element">
            <p>Должность: {{ $teacher->post }}</p>
        </div>
        <div class="col-sm-12 col-lg-3 data__element">
            <p>СНИЛС: {{ $teacher->snils }}</p>
        </div>
        <div class="col-sm-12 col-lg-3 data__element">
            <p>ИНН: {{ $teacher->inn }}</p>
        </div>
        <div class="col-sm-12 col-lg-3 data__element">
            <p>Паспорт: {{ $teacher->passport_data }}</p>
        </div>
        <div class="col-sm-12 col-lg-3 data__element">
            <p>Дата рождения: {{ $teacher->birth_date->format('Y-m-d') }}</p>
        </div>
        <div class="col-sm-12 col-lg-4 data__element">
            <p>Адрес: {{ $teacher->address }}</p>
        </div>
        <div class="col-sm-12 col-lg-4 data__element">
            <p>Образование: {{ $teacher->education }}</p>
        </div>
        <div class="col-sm-12 col-lg-4 data__element">
            <p>Стаж: {{ $teacher->experience }}</p>
        </div>
    </div>
    <form action="{{ route('cabinet.update') }}" method="post" id="formUpdate" class="row g-3 hidden">
        @csrf
        @method('PATCH')
        <div class="col-sm-12 col-lg-6">
            <input type="text" name="name" value="{{ $teacher->name }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-4">
            <input type="tel" name="phone" value="{{ $teacher->phone }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-4">
            <input type="email" name="email" value="{{ $teacher->email }}" class="form-control">

        </div>
        <div class="col-sm-12 col-lg-3">
            <input type="text" name="snils" value="{{ $teacher->snils }}" class="form-control">

        </div>
        <div class="col-sm-12 col-lg-3">
            <input type="text" name="inn" value="{{ $teacher->inn }}" class="form-control">

        </div>
        <div class="col-sm-12 col-lg-3">
            <input type="text" name="passport_data" value="{{ $teacher->passport_data }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-3">
            <input type="date" name="birth_date" value="{{ $teacher->birth_date->format('Y-m-d') }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-4">
            <input type="text" name="address" value="{{ $teacher->address }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-4">
            <input type="text" name="education" value="{{ $teacher->education }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-4">
            <input type="text" name="experience" value="{{ $teacher->experience }}" class="form-control">
        </div>
        <button type="submit" class="btn btn--primary">Отправить</button>
    </form>
    <script src="{{ asset('scripts/profile.js') }}"></script>
@endsection
