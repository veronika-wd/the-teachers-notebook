@extends('layout')

@section('title', 'Табель: ' . $class->name)

{{--
    СТИЛИ ДЛЯ ПЕЧАТИ
    Используем подход "Fixed Overlay", который работает надежнее всего в Chrome/Edge/Firefox
--}}
<style>
    /* На экране блок печати скрыт */
    #print-sheet { display: none; }

    @media print {
        /* 1. Жесткая настройка страницы */
        @page {
            size: A4 landscape;
            margin: 5mm;
        }

        /* 2. Делаем ВСЁ на странице невидимым и нулевого размера,
              но НЕ используем display:none, чтобы не ломать рендеринг */
        body > * {
            visibility: hidden !important;
            height: 0 !important;
            overflow: hidden !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* 3. Показываем ТОЛЬКО наш лист печати */
        #print-sheet {
            display: block !important;
            visibility: visible !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 287mm !important; /* 297mm - 10mm поля */
            height: 200mm !important; /* 210mm - 10mm поля */
            background: white !important;
            z-index: 99999 !important;
            padding: 5mm !important;
            box-sizing: border-box !important;
        }

        /* 4. Таблица для печати */
        .print-tbl {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 7pt !important;
            table-layout: fixed !important;
            color: #000 !important;
        }

        .print-tbl th,
        .print-tbl td {
            border: 1px solid #000 !important;
            padding: 2px !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: clip !important;
            vertical-align: middle !important;
            background: white !important; /* Важно для печати! */
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Столбец ФИО */
        .print-tbl th:first-child,
        .print-tbl td:first-child {
            width: 130px !important;
            min-width: 130px !important;
            text-align: left !important;
            padding-left: 4px !important;
        }

        /* Столбцы дней */
        .print-tbl th:not(:first-child),
        .print-tbl td:not(:first-child) {
            width: 15px !important;
            text-align: center !important;
        }

        /* Вертикальные заголовки */
        .v-header {
            writing-mode: vertical-rl !important;
            transform: rotate(180deg) !important;
            height: 50px !important;
            margin: 0 auto !important;
            line-height: 1.1 !important;
            font-weight: bold !important;
        }

        /* Заголовок документа */
        .print-head {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 8px;
            color: #000 !important;
        }

        /* Легенда */
        .print-leg {
            margin-top: 6px;
            font-size: 8pt;
            color: #000 !important;
        }
    }
</style>

@section('content')
    @php
        \Carbon\Carbon::setLocale('ru');
        setlocale(LC_TIME, 'ru_RU.UTF-8', 'ru_RU', 'rus');
    @endphp

    {{--
        ЛИСТ ДЛЯ ПЕЧАТИ
        Размещаем его ВНЕ основного контейнера, чтобы он не наследовал стили layout
    --}}
    <div id="print-sheet">
        <div class="print-head">
            Журнал питания: {{ $class->name }} | {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
        </div>

        <table class="print-tbl">
            <thead>
            <tr>
                <th>ФИО ученика</th>
                @foreach($days as $day)
                    <th>
                        <div class="v-header">
                            {{ $day->format('d') }}<br>
                            <span style="font-weight:normal; font-size:6pt;">{{ $day->translatedFormat('D') }}</span>
                        </div>
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->surname . ' ' . $student->name . ' ' . $student->patronymic }}</td>
                    @foreach($days as $day)
                        @php
                            $dateStr = $day->format('Y-m-d');
                            $record = $student->attendances->firstWhere('date', $dateStr);
                            $status = $record ? $record->status : null;
                            $isWeekend = $day->isWeekend();
                        @endphp
                        <td>
                            @if(!$isWeekend)
                                @if($status === 'present')
                                    <b>+</b>
                                @elseif($status === 'absent')
                                    <s>−</s>
                                @endif
                            @else
                                <span style="color:#ccc;">−</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="print-leg">
            <b>+</b> Присутствует &nbsp;&nbsp;
            <s>−</s> Отсутствует &nbsp;&nbsp;
            <span style="color:#999;">−</span> Выходной
        </div>
    </div>

    {{-- ОСНОВНОЙ КОНТЕНТ (виден только на экране) --}}
    <div class="container-fluid py-4">
        <!-- Заголовок и навигация -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Класс: {{ $class->name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Классы</a></li>
                        <li class="breadcrumb-item active">{{ $class->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-dark" onclick="window.print()">
                    🖨️ Печать табеля
                </button>
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary">← Назад</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('payment_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('payment_success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('payment_deleted'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('payment_deleted') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Фильтр месяца -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('attendance.show', $class) }}" class="row g-3 align-items-end">
                    <div class="col-auto">
                        <label class="form-label">Год</label>
                        <input type="number" name="year" value="{{ $year }}" class="form-control" style="width: 100px;">
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
                        <button type="submit" class="btn btn--primary">Показать</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Вкладки -->
        <ul class="nav nav-tabs mb-3" id="mainTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab">Журнал столовой</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab">Учет платежей</button>
            </li>
        </ul>

        <div class="tab-content" id="mainTabContent">
            <!-- Вкладка посещаемости -->
            <div class="tab-pane fade show active" id="attendance" role="tabpanel">
                <form method="POST" action="{{ route('attendance.store', $class) }}">
                    @csrf
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="month" value="{{ $month }}">

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 250px; position: sticky; left: 0; z-index: 10; background: white;">ФИО ученика</th>
                                        @foreach($days as $day)
                                            <th class="text-center" style="min-width: 40px;">
                                                <div style="writing-mode: vertical-rl; transform: rotate(180deg); height: 100px;">
                                                    <strong>{{ $day->format('d') }}</strong><br>
                                                    <small>{{ $day->translatedFormat('D') }}</small>
                                                </div>
                                            </th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td style="position: sticky; left: 0; z-index: 5; background: white;">
                                                <strong>{{ $student->surname . ' ' . $student->name . ' ' . $student->patronymic }}</strong>
                                            </td>
                                            @foreach($days as $day)
                                                @php
                                                    $dateStr = $day->format('Y-m-d');
                                                    $record = $student->attendances->firstWhere('date', $dateStr);
                                                    $status = $record ? $record->status : null;
                                                    $isWeekend = $day->isWeekend();
                                                @endphp
                                                <td class="text-center {{ $isWeekend ? 'table-secondary' : '' }}">
                                                    @if(!$isWeekend)
                                                        <button type="button"
                                                                class="btn btn-sm attendance-btn {{ $status === 'present' ? 'btn-success' : ($status === 'absent' ? 'btn-danger' : 'btn-outline-secondary') }}"
                                                                onclick="toggleAttendance(this, '{{ $student->id }}', '{{ $dateStr }}')"
                                                                style="width: 32px; height: 32px; padding: 0;">
                                                            {{ $status === 'present' ? '+' : ($status === 'absent' ? '−' : '') }}
                                                        </button>
                                                        <input type="hidden" name="attendance[{{ $student->id }}][{{ $dateStr }}]" id="att-{{ $student->id }}-{{ $dateStr }}" value="{{ $status }}">
                                                    @else
                                                        <span class="text-muted">−</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    <span class="badge bg-success me-2">+</span> Присутствует
                                    <span class="badge bg-danger me-2">−</span> Отсутствует
                                </div>
                                <button type="submit" class="btn btn--primary btn-lg">Сохранить табель</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Вкладка платежей -->
            <div class="tab-pane fade" id="payments" role="tabpanel">
                <div class="card mb-4 border-success">
                    <div class="card-header bg-success text-white"><h5 class="mb-0">Внести оплату за питание</h5></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('attendance.payment.store', $class) }}" class="row g-3 align-items-end">
                            @csrf
                            <div class="col-md-4">
                                <label class="form-label">Ученик *</label>
                                <select name="student_id" class="form-select" required>
                                    <option value="">Выберите ученика...</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->surname . ' ' . $student->name . ' ' . $student->patronymic }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Сумма (₽) *</label>
                                <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required placeholder="0.00">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Дата внесения</label>
                                <input type="date" name="payment_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Примечание</label>
                                <input type="text" name="comment" class="form-control" placeholder="Например: За октябрь">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn--primary w-100">+</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h5 class="mb-0">📊 История платежей за {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}</h5></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                <tr>
                                    <th>Дата</th><th>Ученик</th><th>Сумма</th><th>Примечание</th><th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $totalByStudent = []; @endphp
                                @foreach($students as $student)
                                    @forelse($student->payments as $payment)
                                        @php $totalByStudent[$student->id] = ($totalByStudent[$student->id] ?? 0) + $payment->amount; @endphp
                                        <tr>
                                            <td>{{ $payment->payment_date->format('d.m.Y') }}</td>
                                            <td><strong>{{ $student->surname . ' ' . $student->name }}</strong></td>
                                            <td class="text-success fw-bold">{{ $payment->formatted_amount }}</td>
                                            <td>{{ $payment->comment ?? '—' }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('attendance.payment.delete', $payment) }}" style="display: inline-block;" onsubmit="return confirm('Удалить этот платеж?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">✕</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty @endforelse
                                @endforeach
                                @if($students->flatMap->payments->isEmpty())
                                    <tr><td colspan="5" class="text-center text-muted py-4">В этом месяце платежей еще не было</td></tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAttendance(btn, studentId, date) {
            const input = document.getElementById(`att-${studentId}-${date}`);
            const currentValue = input.value;
            let newValue, newClass, newText;

            if (currentValue === 'present') {
                newValue = 'absent'; newClass = 'btn-danger'; newText = '−';
            } else if (currentValue === 'absent') {
                newValue = ''; newClass = 'btn-outline-secondary'; newText = '';
            } else {
                newValue = 'present'; newClass = 'btn-success'; newText = '+';
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
