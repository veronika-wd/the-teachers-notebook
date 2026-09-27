@extends('layout')
@section('title', 'Рабочие программы')
@section('content')
    <h2>Рабочие программы</h2>
    <hr>
    @admin
    <h4>Добавить</h4>
    <form action="{{ route('programs.store') }}" method="post" class="d-flex gap-3 align-items-end mb-4"
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
    @endadmin
    <div class="row g-3">
        @foreach($programs as $program)
            <div class="col-sm-12 col-lg-4">
                <div class="card">
                    <div class="card-header bg--info bg-gradient">
                        <p>{{ $program->name }}</p>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        <p class="text-sm">Ссылка: <a class="active-link" href="{{ $program->link }}">{{ $program->link }}</a></p>
                        @admin
                        <form action="{{ route('programs.destroy', $program) }}"
                              method="POST"
                              onsubmit="return confirm('Удалить эту запись?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2">
                                Удалить
                            </button>
                        </form>
                        @endadmin
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
