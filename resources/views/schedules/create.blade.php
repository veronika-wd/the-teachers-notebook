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
            Измените нужные уроки и нажмите "Сохранить изменения".
            Чтобы удалить урок, выберите пустой предмет и нажмите кнопку 🗑️ рядом с ячейкой.
        </div>

        <form action="{{ route('schedule.replace') }}" method="POST" id="scheduleForm">
            @csrf

            {{-- Скрытое поле для удаления ячеек --}}
            <input type="hidden" name="delete_cells" id="deleteCells" value="">

            {{-- Табы классов --}}
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
                    <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}"
                         id="content-{{ $class->id }}">

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

                                                $subjectId = old(
                                                    "schedule.{$class->id}.{$idx}.{$dayNum}.subject_id",
                                                    $currentData['subject_id'] ?? ''
                                                );
                                                $userId = old(
                                                    "schedule.{$class->id}.{$idx}.{$dayNum}.user_id",
                                                    $currentData['user_id'] ?? ''
                                                );
                                                $cabinet = old(
                                                    "schedule.{$class->id}.{$idx}.{$dayNum}.cabinet",
                                                    $currentData['cabinet'] ?? ''
                                                );

                                                // Проверяем, есть ли данные в этой ячейке
                                                $hasData = !empty($subjectId) || !empty($userId) || !empty($cabinet);
                                            @endphp

                                            <td class="p-1 position-relative" style="min-width: 150px;"
                                                id="cell-{{ $class->id }}-{{ $idx }}-{{ $dayNum }}">

                                                {{-- Выбор предмета --}}
                                                <select name="schedule[{{ $class->id }}][{{ $idx }}][{{ $dayNum }}][subject_id]"
                                                        class="form-select form-select-sm mb-1"
                                                        onchange="checkCellHasData(this)">
                                                    <option value="">Предмет</option>
                                                    @foreach($subjects as $subj)
                                                        <option value="{{ $subj->id }}"
                                                            {{ (string) $subjectId === (string) $subj->id ? 'selected' : '' }}>
                                                            {{ $subj->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                {{-- Выбор учителя --}}
                                                <select name="schedule[{{ $class->id }}][{{ $idx }}][{{ $dayNum }}][user_id]"
                                                        class="form-select form-select-sm mb-1"
                                                        onchange="checkCellHasData(this)">
                                                    <option value="">Учитель</option>
                                                    @foreach($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}"
                                                            {{ (string) $userId === (string) $teacher->id ? 'selected' : '' }}>
                                                            {{ $teacher->fio ?? $teacher->name ?? $teacher->email }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                {{-- Кабинет --}}
                                                <input type="number"
                                                       name="schedule[{{ $class->id }}][{{ $idx }}][{{ $dayNum }}][cabinet]"
                                                       class="form-control form-control-sm"
                                                       placeholder="Каб."
                                                       value="{{ $cabinet }}"
                                                       onchange="checkCellHasData(this)">
                                                {{-- Кнопка удаления (показывается только если есть данные) --}}
                                                @if($hasData)
                                                    <button type="button"
                                                            class="btn btn-outline-danger delete-cell-btn w-100 mt-2"
                                                            style="top: 2px; right: 2px; z-index: 10; padding: 2px 6px; font-size: 12px;"
                                                            onclick="markCellForDelete({{ $class->id }}, {{ $idx }}, {{ $dayNum }})"
                                                            title="Удалить урок">
                                                        Очистить
                                                    </button>
                                                @endif
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

            {{-- Кнопки управления --}}
            <div class="d-flex justify-content-between gap-2 mt-3">

                <button type="button"
                        class="btn btn-outline-danger"
                        onclick="if(confirm('⚠️ ВНИМАНИЕ! Это полностью удалит ВСЁ расписание. Продолжить?')) {
                            document.getElementById('clearForm').submit();
                        }">
                    🗑️ Очистить всё расписание
                </button>

                <div class="d-flex gap-2">
                    <button type="submit"
                            name="action"
                            value="update"
                            class="btn btn-success px-4"
                            onclick="return confirm('💾 Сохранить только измененные уроки?')">
                        💾 Сохранить изменения
                    </button>

                    <button type="submit"
                            name="action"
                            value="full_replace"
                            class="btn btn-primary px-4"
                            onclick="return confirm('⚠️ Заменить ВСЁ расписание полностью?')">
                        🔄 Заменить полностью
                    </button>
                </div>
            </div>
        </form>

        {{-- Отдельная форма для очистки --}}
        <form id="clearForm" action="{{ route('schedule.clear') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <script>
        // Массив для хранения ячеек, которые нужно удалить
        let cellsToDelete = [];

        /**
         * Отметить ячейку для удаления
         */
        function markCellForDelete(classId, lessonIndex, dayNum) {
            const cellKey = `${classId}-${lessonIndex}-${dayNum}`;

            if (!cellsToDelete.includes(cellKey)) {
                cellsToDelete.push(cellKey);

                // Визуально помечаем ячейку
                const cell = document.getElementById(`cell-${classId}-${lessonIndex}-${dayNum}`);
                if (cell) {
                    cell.style.backgroundColor = '#ffe6e6';
                    cell.style.opacity = '0.6';
                }

                // Очищаем поля в ячейке
                clearCellFields(classId, lessonIndex, dayNum);

                updateDeleteField();
            }
        }

        /**
         * Очистить все поля в ячейке
         */
        function clearCellFields(classId, lessonIndex, dayNum) {
            const subjectSelect = document.querySelector(`select[name="schedule[${classId}][${lessonIndex}][${dayNum}][subject_id]"]`);
            const teacherSelect = document.querySelector(`select[name="schedule[${classId}][${lessonIndex}][${dayNum}][user_id]"]`);
            const cabinetInput = document.querySelector(`input[name="schedule[${classId}][${lessonIndex}][${dayNum}][cabinet]"]`);

            if (subjectSelect) subjectSelect.value = '';
            if (teacherSelect) teacherSelect.value = '';
            if (cabinetInput) cabinetInput.value = '';

            // Удаляем кнопку удаления
            const deleteBtn = document.querySelector(`#cell-${classId}-${lessonIndex}-${dayNum} .delete-cell-btn`);
            if (deleteBtn) deleteBtn.remove();
        }

        /**
         * Обновить скрытое поле с списком ячеек для удаления
         */
        function updateDeleteField() {
            document.getElementById('deleteCells').value = JSON.stringify(cellsToDelete);
        }

        /**
         * Проверить, есть ли данные в ячейке
         */
        function checkCellHasData(element) {
            const td = element.closest('td');
            const selects = td.querySelectorAll('select');
            const input = td.querySelector('input[type="number"]');

            const hasSubject = selects[0] && selects[0].value !== '';
            const hasTeacher = selects[1] && selects[1].value !== '';
            const hasCabinet = input && input.value !== '';

            const hasData = hasSubject || hasTeacher || hasCabinet;

            const cellId = td.id;
            const match = cellId.match(/cell-(\d+)-(\d+)-(\d+)/);

            if (match) {
                const [, classId, lessonIndex, dayNum] = match;
                const cellKey = `${classId}-${lessonIndex}-${dayNum}`;

                if (!hasData && !cellsToDelete.includes(cellKey)) {
                    td.style.backgroundColor = '';
                    td.style.opacity = '';
                }
            }
        }

        // Обработчик отправки формы
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('scheduleForm');

            if (form) {
                form.addEventListener('submit', function(e) {
                    // Обновляем поле перед отправкой
                    updateDeleteField();

                    // Блокируем кнопки ПОСЛЕ начала отправки
                    setTimeout(() => {
                        form.querySelectorAll('button').forEach(b => {
                            b.disabled = true;
                            if (b.classList.contains('btn-success') || b.classList.contains('btn-primary')) {
                                b.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Сохранение...';
                            }
                        });
                    }, 100);
                });
            }
        });
    </script>
@endsection
