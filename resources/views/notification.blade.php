@extends('layout')
@section('title', $notification->title)
@section('content')
    <style>
        .bg-primary{
            background-color: #deceef !important;
        }
        .form-label{
            font-size: 20px !important;
        }
    </style>
    <h2>Объявление: {{ $notification->title }}</h2>
    <hr>
    <p>{{ $notification->body }}</p>
    <h3 class="mb-3">Комментарии</h3>
    <div class="row">
        <div class="col-sm-12 col-lg-8">
            @if($comments->count() == 0)
                <h5 class="text-center mb-4">Комментариев пока нет</h5>
            @endif
            <div class="row g-3">
                @foreach($comments as $comment)
                    <div class="col-sm-12 col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between bg-primary">
                                <h4>{{ $comment->user->name }}</h4>
                                <p>{{ date_format($comment->created_at, 'd/m/y H:i') }}</p>
                            </div>
                            <div class="card-body">
                                <p>{{ $comment->comment }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-sm-12 col-lg-4">
            <form action="{{ route('notifications.comment', $notification) }}" method="post" class="d-flex flex-column gap-3 bg-primary p-3 rounded-2">
                @csrf
                <label for="comment" class="form-label">Написать комментарий:</label>
                <textarea name="comment" id="comment" cols="30" rows="4" class="form-control"></textarea>
                <button type="submit" class="btn btn--primary">Оставить комментарий</button>
            </form>
        </div>
    </div>
@endsection
