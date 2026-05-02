@extends('layout')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Управление расписанием</h2>
            <a href="{{ route('schedule.index') }}" class="btn btn-outline-secondary">Назад</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="alert alert-info mb-4">
            <strong>💡 Подсказка:</strong>
            Измените нужные уроки и нажмите "Сохранить изменения" (обновит только измененные ячейки).
            Или нажмите "Заменить полностью" для полной перезаписи расписания.
        </div>

        <form action="{{ route('schedule.replace') }}" method="POST" id="scheduleForm">
            @csrf

            <!-- Табы классов -->
            <ul class="nav nav-tabs mb-3" id="classTabs" role="tablist">
                @foreach($classes as $i => $class)
                    <li class="nav-item">
                        <button class="nav-link {{ $i === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab"
                                data-bs-target="#content-{{ $class->id }}"
                                type="button">
                            {{ $class->name }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content mb-4">
                @foreach($classes as $i => $class)
                    <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="content-{{ $class->id }}">

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle text-center">
                                <thead class="table-light">
                                <tr>
                                    <th style="width: 80px;">Урок</th>
                                    @foreach($days as $dayName)
                                        <th>{{ $dayName }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @for($lessonNum = 1; $lessonNum <= $maxLessons; $lessonNum++)
                                    @php $idx = $lessonNum - 1; @endphp
                                    <tr>
                                        <th class="bg-light">{{ $lessonNum }}</th>

                                        @foreach($days as $dayNum => $dayName)
                                            @php
                                                $currentData = $currentSchedule[$class->id]['lessons'][$idx][$dayNum] ?? [];

                                                $subjectId = old("schedule.{$class->id}.{$idx}.{$dayNum}.subject_id", $currentData['subject_id'] ?? '');
                                                $userId = old("schedule.{$class->id}.{$idx}.{$dayNum}.user_id", $currentData['user_id'] ?? ''); // 🔥 user_id
                                                $cabinet = old("schedule.{$class->id}.{$idx}.{$dayNum}.cabinet", $currentData['cabinet'] ?? '');
                                            @endphp

                                            <td class="p-1" style="min-width: 150px;">
                                                <select name="schedule[{{ $class->id }}][{{ $idx }}][{{ $dayNum }}][subject_id]"
                                                        class="form-select form-select-sm mb-1">
                                                    <option value="">Предмет</option>
                                                    @foreach($subjects as $subj)
                                                        <option value="{{ $subj->id }}" {{ $subjectId == $subj->id ? 'selected' : '' }}>
                                                            {{ $subj->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                {{-- 🔥 Изменил name с teacher_id на user_id --}}
                                                <select name="schedule[{{ $class->id }}][{{ $idx }}][{{ $dayNum }}][user_id]"
                                                        class="form-select form-select-sm mb-1">
                                                    <option value="">Учитель</option>
                                                    @foreach($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}"
                                                            {{ (string) $userId === (string) $teacher->id ? 'selected' : '' }}>
                                                            {{ $teacher->fio ?? $teacher->name ?? $teacher->email }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <input type="number"
                                                       name="schedule[{{ $class->id }}][{{ $idx }}][{{ $dayNum }}][cabinet]"
                                                       class="form-control form-control-sm"
                                                       placeholder="Каб."
                                                       value="{{ $cabinet }}">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Кнопки управления -->
            <div class="d-flex justify-content-between gap-2 mt-3">
                <!-- Кнопка очистки -->
                <button type="button"
                        class="btn btn-outline-danger"
                        onclick="if(confirm('⚠️ ВНИМАНИЕ! Это полностью удалит ВСЁ расписание. Продолжить?')) {
                        document.getElementById('clearForm').submit();
                    }">
                    🗑️ Очистить всё расписание
                </button>

                <div class="d-flex gap-2">
                    <!-- Кнопка частичного сохранения -->
                    <button type="submit"
                            name="action"
                            value="update"
                            class="btn btn-success px-4"
                            onclick="return confirm('💾 Сохранить только измененные уроки? Неизмененные останутся как есть.')">
                        💾 Сохранить изменения
                    </button>

                    <!-- Кнопка полной замены -->
                    <button type="submit"
                            name="action"
                            value="full_replace"
                            class="btn btn-primary px-4"
                            onclick="return confirm('⚠️ Заменить ВСЁ расписание полностью? Все текущие данные будут удалены.')">
                        🔄 Заменить полностью
                    </button>
                </div>
            </div>
        </form>

        <!-- Отдельная форма для очистки -->
        <form id="clearForm" action="{{ route('schedule.clear') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    @push('scripts')
        <script>
            // Блокировка кнопок при отправке
            document.querySelectorAll('#scheduleForm button[type="submit"]').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    // Если подтверждение не прошло
                    if (!confirm) {
                        e.preventDefault();
                        return;
                    }

                    // Блокируем все кнопки
                    document.querySelectorAll('#scheduleForm button').forEach(b => {
                        b.disabled = true;
                        if (b.classList.contains('btn-success') || b.classList.contains('btn-primary')) {
                            const originalText = b.innerHTML;
                            b.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Сохранение...';
                            b.dataset.originalText = originalText;
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
