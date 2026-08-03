{{-- resources/views/class-activities/show.blade.php --}}

@extends('layout')

@section('title', 'Класс ' . $schoolClass->name . ' — Кружки')

@section('content')
    <div class="container-fluid py-4">

        <!-- Заголовок -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Класс: {{ $schoolClass->name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('activities.classes.index') }}">Классы</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $schoolClass->name }}</li>
                    </ol>
                </nav>
                <small class="text-muted">Внеурочная деятельность</small>
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

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="table-light">
                        <tr>
                            <th style="min-width: 250px; position: sticky; left: 0; z-index: 10; background: white;">
                                ФИО ученика
                            </th>
                            @foreach($activities as $activity)
                                <th class="text-center" style="min-width: 80px;">
                                    <div style="writing-mode: vertical-rl; transform: rotate(180deg); height: 120px;">
                                        <strong>{{ $activity->name }}</strong><br>
                                        <small>{{ $activity->schedule_label }}</small>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td style="position: sticky; left: 0; z-index: 5; background: white;">
                                    <strong>{{ $student->surname }} {{ $student->name }} {{ $student->patronymic }}</strong>
                                </td>
                                @foreach($activities as $activity)
                                    @php
                                        $isEnrolled = isset($enrollmentMap[$student->id][$activity->id]);
                                    @endphp
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-sm enrollment-btn {{ $isEnrolled ? 'btn-success' : 'btn-outline-secondary' }}"
                                                onclick="toggleEnrollment(this, {{ $student->id }}, {{ $activity->id }})"
                                                style="width: 32px; height: 32px; padding: 0;"
                                                data-enrolled="{{ $isEnrolled ? '1' : '0' }}">
                                            {{ $isEnrolled ? '+' : '' }}
                                        </button>
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $activities->count() + 1 }}"
                                    class="text-center text-muted py-4">
                                    Нет учеников в этом классе
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-muted">
                    <span class="badge bg-success me-2">+</span> Зачислен на кружок
                    <span class="badge bg-secondary me-2">○</span> Не записан
                </div>
            </div>
        </div>

    </div>

    <script>
        function toggleEnrollment(btn, studentId, activityId) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const isCurrentlyEnrolled = btn.dataset.enrolled === '1';

            // Оптимистичное обновление UI (мгновенно, до ответа сервера)
            if (isCurrentlyEnrolled) {
                btn.className = 'btn btn-sm enrollment-btn btn-outline-secondary';
                btn.textContent = '';
                btn.dataset.enrolled = '0';
            } else {
                btn.className = 'btn btn-sm enrollment-btn btn-success';
                btn.textContent = '+';
                btn.dataset.enrolled = '1';
            }
            btn.style.width = '32px';
            btn.style.height = '32px';
            btn.style.padding = '0';

            fetch('{{ route("activities.toggle-enrollment") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    student_id: studentId,
                    activity_id: activityId,
                })
            })
                .then(response => response.json())
                .then(data => {
                    // Синхронизируем с ответом сервера
                    if (data.enrolled) {
                        btn.className = 'btn btn-sm enrollment-btn btn-success';
                        btn.textContent = '+';
                        btn.dataset.enrolled = '1';
                    } else {
                        btn.className = 'btn btn-sm enrollment-btn btn-outline-secondary';
                        btn.textContent = '';
                        btn.dataset.enrolled = '0';
                    }
                    btn.style.width = '32px';
                    btn.style.height = '32px';
                    btn.style.padding = '0';

                    // Визуальный фидбек
                    const row = btn.closest('tr');
                    row.classList.add('table-active');
                    setTimeout(() => row.classList.remove('table-active'), 500);
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    // Возвращаем предыдущее состояние при ошибке
                    if (isCurrentlyEnrolled) {
                        btn.className = 'btn btn-sm enrollment-btn btn-success';
                        btn.textContent = '+';
                        btn.dataset.enrolled = '1';
                    } else {
                        btn.className = 'btn btn-sm enrollment-btn btn-outline-secondary';
                        btn.textContent = '';
                        btn.dataset.enrolled = '0';
                    }
                    btn.style.width = '32px';
                    btn.style.height = '32px';
                    btn.style.padding = '0';
                    alert('Ошибка при сохранении. Попробуйте снова.');
                });
        }
    </script>
@endsection
