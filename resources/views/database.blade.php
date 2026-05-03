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
            <div class="row g-2">
                @admin
                <div class="col-sm-12 col-lg-4">
                    <button id="teachers" class="btn btn--secondary w-100" data-db="teachers">Перейти к учителям</button>
                </div>
                @endadmin
                <div class="col-sm-12 col-lg-4">
                    <button id="pupils" class="btn btn--secondary w-100" data-db="pupils">Перейти к ученикам</button>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <button id="guardians" class="btn btn--secondary w-100" data-db="guardians">Перейти к родителям</button>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <input type="search" id="searchInput" name="search" class="form-control w-100 search" placeholder="Поиск">
                </div>
                <div class="col-sm-12 col-lg-4">
                    <button id="orderByClass" class="btn btn--primary w-100">Сортировать по классам</button>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <button id="orderBySurname" class="btn btn--primary w-100">Сортировать по фамилии</button>
                </div>

            </div>

        </div>
    </div>
    <div id="database-table"></div>

    <script src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>
    <script src="{{ asset('scripts/database.js') }}"></script>
@endsection
