@extends('layout')
@section('title', 'Квалификации')
@section('content')
    <h2>Квалификации</h2>
    <hr>
    <h4>Добавить достижение</h4>
    <form action="{{ route('qualifications.create') }}" method="post" class="d-flex gap-3 align-items-end mb-4"
          enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Введите наименование:</label>
            <input type="text" name="title" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="file">Загрузить файл:</label>
            <input type="file" name="img" id="file" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="dateStart">Дата начала:</label>
            <input type="date" name="date_start" id="dateStart" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="dateEnd">Дата окончания:</label>
            <input type="date" name="date_end" id="dateEnd" class="form-control" required>
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

                            {{-- Условие: сейчас уже настало время напоминания, но квалификация еще не истекла --}}
                            @if($now->greaterThanOrEqualTo($reminderDate) && $now->lessThan($dateEnd))
                                <div class="alert alert-danger">
                                    Напоминание: Срок действия квалификации истекает менее чем через 6 месяцев
                                    ({{ $dateEnd->format('d.m.Y') }}).
                                </div>
                            @endif
                        @endif
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($qualification->image) }}" class="img-fluid" alt="img">
                        <p>{{ $qualification->date_start . ' - ' . $qualification->date_end }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
