@extends('layout')
@section('title', 'Общее расписание')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
@endpush
@section('content')
    <h2>Расписание уроков</h2>
    <hr>
    <form class="w-75 mb-4">
        <div class="row">
            <div class="col-sm-12 col-lg-4">
                <select name="day" id="day" class="form-control">
                    <option>Выбрать день недели</option>
                    <option value="1">Понедельник</option>
                    <option value="2">Вторник</option>
                    <option value="3">Среда</option>
                    <option value="4">Четверг</option>
                    <option value="5">Пятница</option>
                </select>
            </div>
            <div class="col-sm-12 col-lg-4">
                <button type="submit" class="btn btn--primary btn-lg">Получить расписание</button>
            </div>
            @admin
            <div class="col-sm-12 col-lg-4">
                <a href="{{ route('schedule.edit') }}" class="btn btn--primary">Добавить изменения</a>
            </div>
            @endadmin
        </div>
    </form>
    <h2>Начальные классы</h2>
    <div class="schedule-table">

        <table>
            <thead>
            <tr>
                <th>1 класс</th>
                <th>2 класс</th>
                <th>3 класс</th>
                <th>4 класс</th>
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
            <th>5 класс</th>
            <th>6 класс</th>
            <th>7 класс</th>
            <th>8 класс</th>
            <th>9 класс</th>
            <th>10 класс</th>
            <th>11 класс</th>
        </tr>
        </thead>
        <tbody>
        @foreach($highSchool as $item)
            <tr>
                @foreach($item as $subject)
                    <td>{{ $subject->subject }}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>

<script src="scripts/shedule.js"></script>
@endsection
