@extends('layout')
@section('title', 'Общее расписание')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
@endpush
@section('content')
    <h2>Расписание уроков</h2>
    <hr>
    <form class="mb-4">
        <div class="row">
            <div class="col-sm-12 col-lg-3">
                <select name="day" id="day" class="form-control">
                    <option>Выбрать день недели</option>
                    <option value="1" {{ $day == 1 ? 'selected' : '' }}>Понедельник</option>
                    <option value="2" {{ $day == 2 ? 'selected' : '' }}>Вторник</option>
                    <option value="3" {{ $day == 3 ? 'selected' : '' }}>Среда</option>
                    <option value="4" {{ $day == 4 ? 'selected' : '' }}>Четверг</option>
                    <option value="5" {{ $day == 5 ? 'selected' : '' }}>Пятница</option>
                </select>
            </div>
            <div class="col-sm-12 col-lg-3">
                <button type="submit" class="btn btn--primary btn-lg">Получить расписание</button>
            </div>
            @admin
            <div class="col-sm-12 col-lg-3">
                <a href="{{ route('schedule.edit') }}" class="btn btn--primary">Добавить изменения</a>
            </div>
            <div class="col-sm-12 col-lg-3">
                <a href="{{ route('schedule.replace.form') }}" class="btn btn--primary">Создать новое расписание</a>
            </div>
            @endadmin
        </div>
    </form>
    <h2>Начальные классы</h2>
    <div class="schedule-table">

        <table>
            <thead>
            <tr>
                @foreach($primaryClasses as $class)
                    <th>{{ $class->name }} класс</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($primarySchool as $item)
                <tr>
                    @foreach($item as $subject)
                        <td>{{ $subject->subject . ', ' . $subject->cabinet }}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>

    <h2>Старшие классы</h2>
    <table>
        <thead>
        <tr>
            @foreach($highClasses as $class)
                <th>{{ $class->name }} класс</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($highSchool as $item)
            <tr>
                @foreach($item as $subject)
                    <td>{{ $subject->subject . ', ' . $subject->cabinet}}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>

<script src="scripts/shedule.js"></script>
@endsection
