@extends('layout')
@section('title', 'Главная')
@push('styles')
@endpush
@section('content')
    <style>
        .bg-info{
            background-color: #b96aed !important;
        }
    </style>
    <div class="row g-3">
        <div class="col-lg-4 col-sm-12">
            <div class="message__wrapper mb-3">
                <h2>Объявления</h2>
                @foreach($notifications as $notification)
                    <div class="card w-100 mb-2">
                        <div class="card-header d-flex justify-content-between align-items-center bg-info bg-gradient">
                            <h5>{{ $notification->title }}</h5>
                            <p>{{ date_format($notification->created_at, 'd/m/y H:i') }}</p>
                        </div>
                        <div class="card-body">
                            <p>{{ $notification->body }}</p>
                        </div>
                    </div>
                @endforeach
                <a href="{{ route('notifications.index') }}" class="btn btn--primary">
                    Открыть список объявлений
                </a>
            </div>
            <div id="calendar-container">
                <h3 id="calendar-month-year"></h3>
                <div id="calendar-grid"></div>
            </div>
        </div>

        <div class="col-lg-8">
            <div id="today-events-block" class="events__wrapper mb-3">
                <h2 class="events__header">События на сегодня</h2>
                @if($events)
                    <p class="events__info">Событий на сегодня нет</p>
                @endif
                @foreach($events as $event)
                    <p class="events__info">{{ date_format($event->date_start, 'H:i') . ': ' . $event->name . ', ' . $event->user->name}}</p>
                @endforeach
                <a href="{{ route('calendar') }}" class="btn btn--primary">
                    Перейти к календарю
                </a>
            </div>
            <div id="shedule-block">
                <div class="header-shedule">
                    <h2>Расписание на сегодня</h2>
                    <a href="{{ route('schedule.index') }}" class="btn btn--primary">
                        Перейти к общему расписанию
                    </a>
                </div>
                <div class="shedule-table">
                    <table>
                        <thead>
                        <tr>
                            <th>1 класс</th>
                            <th>2 класс</th>
                            <th>3 класс</th>
                            <th>4 класс</th>
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
                        @foreach($shedule as $number => $item) {{-- $number - это номер урока (1-7) --}}
                        <tr>
                            {{-- Заголовок строки (опционально, чтобы понимать какой урок) --}}
                            {{-- <td style="font-weight:bold;">{{ $number }} урок</td> --}}

                            @foreach($item as $subject)
                                @php
                                    // Определяем, чей это урок для подсветки
                                    $isMyLesson = false;

                                    if ($subject->is_replacement) {
                                        // Если замена, проверяем ID нового учителя
                                        if (isset($subject->replacementUserId) && $subject->replacementUserId == auth()->user()->id) {
                                            $isMyLesson = true;
                                        }
                                    } else {
                                        // Если без замены, проверяем штатного учителя
                                        if ($subject->user_id == auth()->user()->id) {
                                            $isMyLesson = true;
                                        }
                                    }

                                    // Формируем текст учителя
                                    $teacherText = '';
                                    if ($subject->is_replacement && $subject->replacementTeacherName) {
                                        $teacherText = $subject->replacementTeacherName;
                                    } else {
                                        // Здесь нужно вывести имя штатного учителя.
                                        // Если в Schedule лежит только user_id, нужна связь $subject->teacher->name
                                        // Или если вы подгрузили её в запросе Schedule::with('teacher')
                                        $teacherText = $subject->teacher->name ?? '—';
                                    }
                                @endphp

                                <td class="{{ $isMyLesson ? 'users-lesson' : '' }} {{ $subject->is_replacement ? 'bg-warning' : '' }}">
                                    <div>{{ $subject->subject }}</div>
                                    <small>{{ $subject->cabinet }}</small>
                                    <div style="color: #666; font-size: 0.8em;">
                                        {{ $teacherText }}
                                        @if($subject->is_replacement)
                                            <span style="color:red;">(зам.)</span>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <script src="{{ asset('scripts/calendar-landing.js') }}"></script>
@endsection
