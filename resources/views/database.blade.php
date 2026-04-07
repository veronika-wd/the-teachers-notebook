@extends('layout')
@section('title', 'База данных')
@push('styles')
    <link href="https://unpkg.com/tabulator-tables@5.5.2/dist/css/tabulator.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/database.css') }}">
@endpush
@section('content')
    <h2>База данных</h2>
    <hr>
    <div class="sort-and-filter mb-3">
        <div class="menu">
            <button id="teachers" class="btn btn--secondary" data-db="teachers">Перейти к учителям</button>
            <button id="pupils" class="btn btn--secondary" data-db="pupils">Перейти к ученикам</button>
            <button id="guardians" class="btn btn--secondary" data-db="guardians">Перейти к родителям</button>

            <button id="orderByClass" class="btn btn--primary">Сортировать по классам</button>
            <button id="orderBySurname" class="btn btn--primary">Сортировать по фамилии</button>
            <input type="search" id="searchInput" name="search" placeholder="Поиск">
        </div>
    </div>
    <div id="database-table"></div>

    <script src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>
    <script src="{{ asset('scripts/database.js') }}"></script>
@endsection
