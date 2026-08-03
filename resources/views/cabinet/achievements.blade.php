@extends('layout')
@section('title', 'Мои достижения')
@section('content')
    <h2>Мои достижения</h2>
    <hr>
    <h4>Добавить достижение</h4>
    <form action="{{ route('cabinet.achievements.upload') }}" method="post" class="d-flex gap-3 align-items-end mb-4"
          enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Введите наименование:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="link">Вставьте ссылку:</label>
            <input type="text" name="link" id="link" class="form-control" required>
        </div>
        <button type="submit" class="btn btn--primary">Добавить</button>
    </form>
    <div class="row g-3">
        @foreach($achievements as $achievement)
            <div class="col-sm-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <p>{{ $achievement->name }}</p>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        <p class="text-sm">Ссылка: <a class="active-link" href="{{ $achievement->path }}">{{ $achievement->path }}</a></p>
                        <p>{{ date_format($achievement->created_at, 'd/m/Y') }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
