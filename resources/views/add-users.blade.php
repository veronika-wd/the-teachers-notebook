@extends('layout')
@section('title', 'Добавление')
@section('content')
<h2>Добавить</h2>
<hr>

<h3>Добавить работника</h3>
<form action="{{ route('user.store') }}" method="post" id="formUpdateWorker" class="row g-3 hidden">
    @csrf

    <div class="col-sm-12 col-lg-4">
        <label for="worker_name" class="form-label">ФИО</label>
        <input type="text" name="name" id="worker_name" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-4">
        <label for="worker_phone" class="form-label">Телефон</label>
        <input type="tel" name="phone" id="worker_phone" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-4">
        <label for="worker_email" class="form-label">Email</label>
        <input type="email" name="email" id="worker_email" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-4">
        <label for="password" class="form-label">Пароль</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-4">
        <label for="worker_snils" class="form-label">СНИЛС</label>
        <input type="text" name="snils" id="worker_snils" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-4">
        <label for="worker_inn" class="form-label">ИНН</label>
        <input type="text" name="inn" id="worker_inn" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-4">
        <label for="worker_passport" class="form-label">Паспортные данные</label>
        <input type="text" name="passport_data" id="worker_passport" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-4">
        <label for="worker_birth_date" class="form-label">Дата рождения</label>
        <input type="date" name="birth_date" id="worker_birth_date" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-4">
        <label for="worker_address" class="form-label">Адрес</label>
        <input type="text" name="address" id="worker_address" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-4">
        <label for="worker_education" class="form-label">Образование</label>
        <input type="text" name="education" id="worker_education" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-4">
        <label for="post" class="form-label">Должность</label>
        <input type="text" name="post" id="post" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-4">
        <label for="worker_experience" class="form-label">Опыт работы</label>
        <input type="text" name="experience" id="worker_experience" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-4">
        <label for="role" class="form-label">Права</label>
        <select name="role" id="role" class="form-control">
            <option value="teacher" selected>Учитель</option>
            <option value="admin">Администратор</option>
        </select>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn--primary">Отправить</button>
    </div>
</form>

<h3>Добавить школьника</h3>
<form action="{{ route('student.store') }}" method="post" id="formUpdateStudent" class="row g-3 hidden">
    @csrf

    <div class="col-sm-12 col-lg-3">
        <label for="student_surname" class="form-label">Фамилия</label>
        <input type="text" name="surname" id="student_surname" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-3">
        <label for="student_name" class="form-label">Имя</label>
        <input type="text" name="name" id="student_name" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-3">
        <label for="student_patronymic" class="form-label">Отчество</label>
        <input type="text" name="patronymic" id="student_patronymic" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-3">
        <label for="class" class="form-label">Класс</label>
        <select name="class" id="class" class="form-select">
            @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-sm-12 col-lg-2">
        <label for="student_snils" class="form-label">СНИЛС</label>
        <input type="text" name="snils" id="student_snils" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-2">
        <label for="student_inn" class="form-label">ИНН</label>
        <input type="text" name="inn" id="student_inn" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-2">
        <label for="student_passport" class="form-label">Паспортные данные</label>
        <input type="text" name="passport_data" id="student_passport" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-2">
        <label for="student_birth_date" class="form-label">Дата рождения</label>
        <input type="date" name="birth_date" id="student_birth_date" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-2">
        <label for="student_address" class="form-label">Адрес</label>
        <input type="text" name="address" id="student_address" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-2">
        <label for="student_status" class="form-label">Статус</label>
        <input type="text" name="status" id="student_status" class="form-control">
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn--primary">Отправить</button>
    </div>
</form>

<h3>Добавить родителя</h3>
<form action="{{ route('guardian.store') }}" method="post" id="formUpdateGuardian" class="row g-3 hidden">
    @csrf

    <div class="col-sm-12 col-lg-12">
        <label for="guardian_full_name" class="form-label">ФИО</label>
        <input type="text" name="surname" id="guardian_full_name" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-6">
        <label for="guardian_phone" class="form-label">Телефон</label>
        <input type="tel" name="phone" id="guardian_phone" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-6">
        <label for="guardian_status" class="form-label">Статус</label>
        <input type="text" name="status" id="guardian_status" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-6">
        <label for="guardian_job" class="form-label">Место работы</label>
        <input type="text" name="job" id="guardian_job" class="form-control">
    </div>

    <div class="col-sm-12 col-lg-6">
        <label for="guardian_address" class="form-label">Адрес</label>
        <input type="text" name="address" id="guardian_address" class="form-control">
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn--primary">Отправить</button>
    </div>
</form>
@endsection
