@extends('layout')
@section('title', 'Добавить конкурс')
@section('content')
    <h2>Добавить конкурс</h2>
    <hr>
    <form action="{{ route('competitions.store') }}" method="post" class="w-50">
        @csrf
        <label for="title">Наименование</label>
        <input type="text" name="title" id="title" class="form-control mb-3">
        <label for="description">Описание</label>
        <textarea name="description" id="description" cols="30" rows="10" class="form-control"></textarea>
        <label for="register_start">Начало регистрации</label>
        <input type="datetime-local" name="register_start" id="register_start" class="form-control">
        <label for="register_end">Конец регистрации</label>
        <input type="datetime-local" name="register_end" id="register_end" class="form-control">
        <label for="competition_start">Начало конкурса</label>
        <input type="datetime-local" name="competition_start" id="competition_start" class="form-control">
        <label for="competition_end">Конец конкурса</label>
        <input type="datetime-local" name="competition_end" id="competition_end" class="form-control">
        <button type="submit" class="btn btn--primary">Отправить</button>
    </form>
@endsection
