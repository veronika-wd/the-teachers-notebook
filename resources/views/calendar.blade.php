@extends('layout')
@section('title', 'Календарь событий')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
@endpush
@section('content')
    <h1>Календарь событий</h1>
    <hr>
    <button id="addEventBtn" class="btn btn--primary mb-3">Добавить событие</button>
    <div id="calendar"></div>
    <h2 class="mt-3">Учебный план</h2>
    <div class="card-body">
        @admin
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="bi bi-cloud-upload me-2"></i>
                    Загрузить документ
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('plan') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <label for="file" class="form-label">Выберите файл</label>
                            <input type="file"
                                   class="form-control form-control-lg @error('file') is-invalid @enderror"
                                   id="file"
                                   name="file"
                                   required>
                            <div class="form-text">
                            </div>
                            @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn--primary btn-lg w-100">
                                Загрузить
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endadmin
        @if($plan)
        <img src="{{ \Illuminate\Support\Facades\Storage::url($plan->path) }}" alt="plan" class="d-flex justify-content-center w-100">
        @endif
        <!-- Модальное окно для добавления/редактирования события -->
        <div id="eventModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="modalTitle">Добавить событие</h2>
                <form id="eventForm" action="{{ route('events.store') }}" method="post">
                    @csrf
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="eventTitle">Название:</label>
                        <input type="text" id="eventTitle" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="eventStart">Начало:</label>
                        <input type="datetime-local" id="eventStart" name="date_start" required>
                    </div>
                    <div class="form-group">
                        <label for="eventEnd">Конец:</label>
                        <input type="datetime-local" id="eventEnd" name="date_end" required>
                    </div>
                    <div class="form-group">
                        <label for="eventDescription">Описание:</label>
                        <textarea id="eventDescription" rows="3" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="eventColor">Цвет:</label>
                        <input type="color" id="eventColor" value="#7f3d9e" name="color" class="form-control">
                    </div>
                    <div class="form-actions d-flex justify-content-center">
                        @error('user')
                        <p>{{ $message }}</p>
                        @enderror
                        <button type="submit" class="btn btn--primary w-50">Сохранить</button>
                        <button type="button" id="deleteEventBtn" class="btn btn-danger w-50">Удалить</button>
                    </div>
                </form>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/ru.min.js"></script>
        <script src="{{ asset('scripts/full-calendar.js') }}"></script>
@endsection
