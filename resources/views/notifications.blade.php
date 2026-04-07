@extends('layout')
@section('title', 'Объявления')
@section('content')
    <style>
        .bg-info {
            background-color: #b96aed !important;
        }

        a {
            text-decoration: none;
        }
    </style>
    <h2>Список объявлений</h2>
    <hr>
    @admin
    <form action="{{ route('notifications.store') }}" method="post" class="d-flex flex-column w-25 gap-2">
        @csrf
        <label>Добавить объявление</label>
        <input type="text" name="title" placeholder="Заголовок" class="form-control" required>
        <textarea name="body" id="body" cols="30" rows="3" class="form-control" required></textarea>
        <button type="submit" class="btn btn--primary">Добавить объявление</button>
    </form>
    @endadmin

    <div id="messages" class="mt-3">
        <div class="row g-3">
            @foreach($notifications as $notification)
                <div class="col-sm-12 col-lg-4">
                    <a href="{{ route('notifications.show', $notification) }}">
                        <div class="card">
                            <div
                                class="card-header d-flex justify-content-between align-items-center bg--info bg-gradient">
                                <h5>{{ $notification->title }}</h5>
                                <p>{{ date_format($notification->created_at, 'd/m/y H:i') }}</p>
                            </div>
                            <div class="card-body">
                                <p>{{ $notification->body }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

@endsection
