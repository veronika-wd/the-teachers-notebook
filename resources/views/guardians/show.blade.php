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
    {{--    <a href="{{ route('') }}" class="btn btn--primary">Перейти к родителям</a>--}}
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

    </div>

    <form action="{{ route('guardians.update', $guardian) }}" method="post" id="formUpdate" class="row g-3 hidden">
        @csrf
        @method('PATCH')
        <div class="col-sm-12 col-lg-12">
            <input type="text" name="surname" value="{{ $guardian->full_name }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-6">
            <input type="tel" name="phone" value="{{ $guardian->phone }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-6">
            <input type="text" name="status" value="{{ $guardian->status }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-6">
            <input type="text" name="job" value="{{ $guardian->job }}" class="form-control">
        </div>
        <div class="col-sm-12 col-lg-6">
            <input type="date" name="address" value="{{ $guardian->address }}" class="form-control">
        </div>
        <button type="submit" class="btn btn--primary col-sm-12 col-lg-4">Отправить</button>
    </form>
    <h3 class="mt-3">Дети</h3>
    <hr>
    <h4>Добавить ребенка</h4>
    <form action="{{ route('guardians.students.store', $guardian) }}" method="post" class="w-50 d-flex gap-3 align-items-center justify-content-start mb-3">
        @csrf
        <select name="student" id="student" class="form-control w-75">
            @foreach($studentsAll as $student)
                <option value="{{ $student->id }}">{{ $student->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn--primary w-25">Добавить ребенка</button>
    </form>
    <div class="row g-2">
        @foreach($students as $student)
            <div class="col-lg-2">
                <a href="{{ route('students.show', $student) }}" class="btn btn--outline">{{ $student->name }}</a>
            </div>
        @endforeach
    </div>
    <script src="{{ asset('scripts/profile.js') }}"></script>
@endsection
