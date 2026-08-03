
@extends('layout')

@section('title', $activity->name . ' — Посещаемость')

@section('content')
    @php
        \Carbon\Carbon::setLocale('ru');
        setlocale(LC_TIME, 'ru_RU.UTF-8', 'ru_RU', 'rus');
    @endphp

    <div class="container-fluid py-4">

        <!-- Заголовок -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">{{ $activity->name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('activities.classes.index') }}">Кружки</a>
                        </li>
                        <li class="breadcrumb-item active">Посещаемость</li>
                    </ol>
                </nav>
                <small class="text-muted">
                    {{ $activity->schedule_label }}
                    ({{ $activity->frequency }} раз/нед.)
                    @if($activity->room) | Каб. {{ $activity->room }} @endif
                </small>
            </div>
            <a href="{{ route('activities.classes.index') }}" class="btn btn-secondary">
                ← Назад
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Вкладки -->
        <ul class="nav nav-tabs mb-3" id="mainTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="attendance-tab" data-bs-toggle="tab"
                        data-bs-target="#attendance" type="button" role="tab">
                    Журнал посещаемости
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('activities.themes', $activity) }}">
                    Темы занятий
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('activities.enrollment', $activity) }}">
                    Управление записью
                </a>
            </li>
        </ul>

        <div class="tab-content" id="mainTabContent">
            <div class="tab-pane fade show active" id="attendance" role="tabpanel">

                <!-- Фильтр месяца -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-auto">
                                <label class="form-label">Год</label>
                                <input type="number" name="year" value="{{ $year }}"
                                       class="form-control" style="width: 100px;"
                                       min="2020" max="2040">
                            </div>
                            <div class="col-auto">
                                <label class="form-label">Месяц</label>
                                <select name="month" class="form-select">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn--primary">
                                    Показать
                                </button>
                            </div>
                            <div class="col-auto ms-auto">
                                @php
                                    $prevMonth = \Carbon\Carbon::create($year, $month, 1)->subMonth();
                                    $nextMonth = \Carbon\Carbon::create($year, $month, 1)->addMonth();
                                @endphp
                                <a href="{{ route('activities.attendance', [$activity, 'year' => $prevMonth->year, 'month' => $prevMonth->month]) }}"
                                   class="btn btn-outline-secondary">← Пред.</a>
                                <a href="{{ route('activities.attendance', [$activity, 'year' => $nextMonth->year, 'month' => $nextMonth->month]) }}"
                                   class="btn btn-outline-secondary">След. →</a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Инфо о количестве занятий --}}
                <div class="alert alert-info py-2 small mb-3">
                    В месяце <strong>{{ count($meetingDates) }}</strong> занятий
                    ({{ $activity->schedule_label }})
                </div>

                <!-- Таблица посещаемости -->
                <form method="POST" action="{{ route('activities.attendance.save', $activity) }}">
                    @csrf

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 250px; position: sticky; left: 0; z-index: 10; background: white;">
                                            ФИО ученика
                                        </th>
                                        @foreach($meetingDates as $date)
                                            <th class="text-center" style="min-width: 40px;">
                                                <div style="writing-mode: vertical-rl; transform: rotate(180deg); height: 100px;">
                                                    <strong>{{ $date->format('d') }}</strong><br>
                                                    <small>{{ $date->translatedFormat('D') }}</small>
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($studentActivities as $sa)
                                        <tr>
                                            <td style="position: sticky; left: 0; z-index: 5; background: white;">
                                                <strong>{{ $sa->student->surname }} {{ $sa->student->name }} {{ $sa->student->patronymic }}</strong>
                                            </td>
                                            @foreach($meetingDates as $date)
                                                @php
                                                    $dateKey = $date->format('Y-m-d');
                                                    $currentStatus = $attendanceMap[$sa->id][$dateKey] ?? null;
                                                @endphp
                                                <td class="text-center">
                                                    <button type="button"
                                                            class="btn btn-sm attendance-btn {{ $currentStatus === 'present' ? 'btn-success' : ($currentStatus === 'absent' ? 'btn-danger' : 'btn-outline-secondary') }}"
                                                            onclick="toggleAttendance(this, '{{ $sa->id }}', '{{ $dateKey }}')"
                                                            style="width: 32px; height: 32px; padding: 0;">
                                                        {{ $currentStatus === 'present' ? '+' : ($currentStatus === 'absent' ? '−' : '') }}
                                                    </button>
                                                    <input type="hidden"
                                                           name="attendance[{{ $sa->id }}][{{ $dateKey }}]"
                                                           id="att-{{ $sa->id }}-{{ $dateKey }}"
                                                           value="{{ $currentStatus ?? '' }}">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($meetingDates) + 1 }}"
                                                class="text-center text-muted py-4">
                                                Нет записанных учеников.
                                                <a href="{{ route('activities.enrollment', $activity) }}">
                                                    Добавить учеников
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($studentActivities->isNotEmpty())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <span class="badge bg-success me-2">+</span> Присутствует
                                        <span class="badge bg-danger me-2">−</span> Отсутствует
                                    </div>
                                    <button type="submit" class="btn btn--primary btn-lg">
                                        Сохранить табель
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function toggleAttendance(btn, saId, date) {
            const input = document.getElementById(`att-${saId}-${date}`);
            const currentValue = input.value;

            let newValue, newClass, newText;

            if (currentValue === 'present') {
                // present → absent
                newValue = 'absent';
                newClass = 'btn-danger';
                newText = '−';
            } else if (currentValue === 'absent') {
                // absent → пусто
                newValue = '';
                newClass = 'btn-outline-secondary';
                newText = '';
            } else {
                // пусто → present
                newValue = 'present';
                newClass = 'btn-success';
                newText = '+';
            }

            input.value = newValue;
            btn.className = `btn btn-sm attendance-btn ${newClass}`;
            btn.textContent = newText;
            btn.style.width = '32px';
            btn.style.height = '32px';
            btn.style.padding = '0';
        }
    </script>
@endsection
