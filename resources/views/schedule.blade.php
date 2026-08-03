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
                    <option value="">Выбрать день недели</option>
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

    {{-- ===================== НАЧАЛЬНЫЕ КЛАССЫ ===================== --}}
    <h2>Начальные классы</h2>
    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
            <tr>
                <th style="width: 80px;">Урок</th>
                @foreach($primaryClasses as $class)
                    <th>{{ $class->name }} класс</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @for($i = 1; $i <= 7; $i++)
                <tr>
                    <td class="fw-bold bg-light">{{ $i }}</td>
                    @foreach($primaryClasses as $class)
                        @php
                            $lesson = $schedules->get($class->name)?->firstWhere('number', $i);
                        @endphp
                        <td>
                            @if($lesson)
                                <div class="fw-semibold">
                                    {{ $lesson->subject->name ?? '—' }}
                                </div>
                                @if($lesson->teacher)
                                    <small class="text-muted d-block">
                                        {{ $lesson->teacher->fio ?? $lesson->teacher->name ?? '' }}
                                    </small>
                                @endif
                                @if($lesson->cabinet)
                                    <small class="text-muted">
                                        Кабинет {{ $lesson->cabinet }}
                                    </small>
                                @endif
                            @else
                                &nbsp;
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endfor
            </tbody>
        </table>
    </div>

    {{-- ===================== СТАРШИЕ КЛАССЫ ===================== --}}
    <h2>Старшие классы</h2>
    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
            <tr>
                <th style="width: 80px;">Урок</th>
                @foreach($highClasses as $class)
                    <th>{{ $class->name }} класс</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @for($i = 1; $i <= 8; $i++)
                <tr>
                    <td class="fw-bold bg-light">{{ $i }}</td>
                    @foreach($highClasses as $class)
                        @php
                            $lesson = $schedules->get($class->name)?->firstWhere('number', $i);
                        @endphp
                        <td>
                            @if($lesson)
                                <div class="fw-semibold">
                                    {{ $lesson->subject->name ?? '—' }}
                                </div>
                                @if($lesson->teacher)
                                    <small class="text-muted d-block">
                                        {{ $lesson->teacher->fio ?? $lesson->teacher->name ?? '' }}
                                    </small>
                                @endif
                                @if($lesson->cabinet)
                                    <small class="text-muted">
                                        Каб. {{ $lesson->cabinet }}
                                    </small>
                                @endif
                            @else
                                &nbsp;
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endfor
            </tbody>
        </table>
    </div>

@endsection
