@extends('layout')

@section('title', 'Создать кружок')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Создать кружок</h3>
            <a href="{{ route('activities.classes.index') }}" class="btn btn-secondary btn-sm">
                ← Назад
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('activities.store') }}" method="POST">
                    @csrf

                    {{-- Название --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Название кружка <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}"
                               placeholder="Например: Шахматы, Рисование, Футбол" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Описание --}}
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Описание</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3"
                                  placeholder="Краткое описание кружка">{{ old('description') }}</textarea>
                    </div>

                    {{-- Дни недели — КЛЮЧЕВОЙ БЛОК --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Дни проведения <span class="text-danger">*</span>
                        </label>
                        <p class="text-muted small mb-2">
                            Выберите 1–3 дня недели, когда проходит кружок.
                            Расписание будет неизменным каждую неделю.
                        </p>

                        <div class="d-flex flex-wrap gap-2" id="weekDaysContainer">
                            @php
                                $days = [
                                    1 => 'Понедельник',
                                    2 => 'Вторник',
                                    3 => 'Среда',
                                    4 => 'Четверг',
                                    5 => 'Пятница',
                                    6 => 'Суббота',
                                    7 => 'Воскресенье',
                                ];
                                $shortDays = [
                                    1 => 'Пн', 2 => 'Вт', 3 => 'Ср', 4 => 'Чт',
                                    5 => 'Пт', 6 => 'Сб', 7 => 'Вс',
                                ];
                                $oldDays = old('week_days', []);
                            @endphp

                            @foreach($days as $num => $label)
                                <div class="form-check">
                                    <input
                                        class="form-check-input week-day-check"
                                        type="checkbox"
                                        name="week_days[]"
                                        value="{{ $num }}"
                                        id="day_{{ $num }}"
                                        {{ in_array($num, $oldDays) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="day_{{ $num }}">
                                    <span class="badge bg-light text-dark border me-1">
                                        {{ $shortDays[$num] }}
                                    </span>
                                        {{ $label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        {{-- Счётчик выбранных дней --}}
                        <div class="mt-2">
                            <small class="text-muted">
                                Выбрано: <strong id="selectedDaysCount">0</strong> / 3 дня
                            </small>
                            <div id="daysWarning" class="text-danger small mt-1" style="display:none;">
                                Можно выбрать максимум 3 дня!
                            </div>
                        </div>

                        @error('week_days')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Время и кабинет --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="start_time" class="form-label fw-semibold">Время начала</label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                   id="start_time" name="start_time" value="{{ old('start_time') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="end_time" class="form-label fw-semibold">Время окончания</label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                   id="end_time" name="end_time" value="{{ old('end_time') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="room" class="form-label fw-semibold">Кабинет</label>
                            <input type="text" class="form-control @error('room') is-invalid @enderror"
                                   id="room" name="room" value="{{ old('room') }}"
                                   placeholder="Например: 205">
                        </div>
                    </div>

                    {{-- Предпросмотр расписания --}}
                    <div class="alert alert-light border mb-3" id="schedulePreview">
                        <strong>Расписание:</strong>
                        <span id="scheduleText" class="text-muted">Выберите дни недели</span>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-purple px-4">
                            Создать кружок
                        </button>
                        <a href="{{ route('activities.classes.index') }}" class="btn btn-outline-secondary">
                            Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('.week-day-check');
                const counter    = document.getElementById('selectedDaysCount');
                const warning    = document.getElementById('daysWarning');
                const schedText  = document.getElementById('scheduleText');

                const dayNames = {
                    1: 'Пн', 2: 'Вт', 3: 'Ср', 4: 'Чт',
                    5: 'Пт', 6: 'Сб', 7: 'Вс'
                };

                function updateUI() {
                    const checked = document.querySelectorAll('.week-day-check:checked');
                    const count = checked.length;

                    counter.textContent = count;

                    // Блокируем если > 3
                    if (count >= 3) {
                        checkboxes.forEach(cb => {
                            if (!cb.checked) cb.disabled = true;
                        });
                        warning.style.display = count > 3 ? 'block' : 'none';
                    } else {
                        checkboxes.forEach(cb => cb.disabled = false);
                        warning.style.display = 'none';
                    }

                    // Предпросмотр
                    if (count === 0) {
                        schedText.textContent = 'Выберите дни недели';
                        schedText.className = 'text-muted';
                    } else {
                        const days = Array.from(checked)
                            .map(cb => dayNames[cb.value])
                            .sort((a, b) => {
                                const order = ['Пн','Вт','Ср','Чт','Пт','Сб','Вс'];
                                return order.indexOf(a) - order.indexOf(b);
                            });
                        schedText.textContent = days.join(', ') +
                            ` (${count} раз${count === 1 ? '' : count < 5 ? 'а' : ''} в неделю)`;
                        schedText.className = 'text-success fw-semibold';
                    }
                }

                checkboxes.forEach(cb => cb.addEventListener('change', updateUI));
                updateUI(); // Инициализация
            });
        </script>
    @endpush

    <style>
        .btn-purple {
            background-color: #7c3aed;
            color: white;
            border: none;
        }
        .btn-purple:hover {
            background-color: #6d28d9;
            color: white;
        }
    </style>
@endsection
