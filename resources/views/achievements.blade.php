@extends('layout')
@section('title', 'Достижения')
@section('content')
    <h2>Достижения</h2>
    <hr>
    <div class="row g-3">
        @foreach($achievements as $achievement)
            <div class="col-sm-12 col-lg-4">
                <div class="card">
                    <div class="card-header bg--info bg-gradient">
                        <p>{{ $achievement->name }}</p>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($achievement->path) }}" class="img-fluid" alt="img">
                        <p>{{ date_format($achievement->created_at, 'd/m/Y') }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
