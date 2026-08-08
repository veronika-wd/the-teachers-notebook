@extends('layout')
@section('title', 'Квалификации')
@section('content')
        <h2>Квалификации</h2>
        <a href="{{ route('qualifications.index') }}"><button class="btn btn--primary">Курсы</button></a>
    <hr>
    <h4>Добавить</h4>
    <form action="{{ route('qualifications.create') }}" method="post" class="d-flex gap-3 align-items-end mb-4"
          enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Введите наименование:</label>
            <input type="text" name="title" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="link">Вставьте ссылку:</label>
            <input type="text" name="link" id="link" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="dateStart">Дата начала:</label>
            <input type="date" name="date_start" id="dateStart" class="form-control" required>
        </div>
        <button type="submit" class="btn btn--primary">Добавить</button>
    </form>
    <div class="row g-3">
        @foreach($qualifications as $qualification)
            <div class="col-sm-12 col-lg-4">
                <div class="card">
                    <div class="card-header bg--info bg-gradient">
                        <p>{{ $qualification->title }}</p>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        {{-- Предположим, переменная $qualification передана из контроллера --}}

                        @if($qualification->date_end)
                            @php
                                $dateEnd = \Carbon\Carbon::parse($qualification->date_end);
                                $reminderDate = $dateEnd->copy()->subMonths(6);
                                $now = \Carbon\Carbon::now();
                            @endphp
                        @endif
                        <p class="text-sm">Ссылка: <a class="active-link" href="{{ $qualification->image }}">{{ $qualification->image }}</a></p>
                        <p>{{ $qualification->date_start }}</p>
                        <form action="{{ route('qualifications.destroy', $qualification) }}"
                              method="POST"
                              onsubmit="return confirm('Удалить эту запись?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2">
                                Удалить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
