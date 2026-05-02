@extends('layout')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush
@section('title', 'Авторизация')
@section('content')
    <div class="auth-form w-50 m-auto mt-4">
        <h2>Мой кабинет</h2>
        <form id="loginForm" action="{{ route('login') }}" method="post" class="row">
            @csrf
            <div class="md-3 col-sm-12">
                <label for="email" class="form-label text-left">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="example@school.ru" required>
            </div>

            <div class="md-3 col-sm-12">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="только латинские буквы и цифры"
                       required>
            </div>

            <button type="submit" class="btn btn--primary mt-3 w-100 col-sm-12">Войти</button>
            @error('auth')
            <p class="error">{{ $message }}</p>
            @enderror
        </form>
    </div>
@endsection
