@extends('layout')
@section('title', 'Конкурсы')
@section('content')
    <h2>Конкурсы</h2>
    <hr>
    @admin
    <a href="{{ route('competitions.create') }}" class="btn btn--primary mb-3">+ Добавить конкурс</a>
    @endadmin
    <div class="row">
        @foreach($competitions as $competition)
            <div class="col-sm-12 col-lg-4">
                <div class="card">
                    @php
                        $now = now();
                    @endphp
                    <div class="card-header
                     @if($now->gt($competition->competition_end))
                        bg--danger
                    @elseif($now->gte($competition->register_start) && $now->lte($competition->register_end))
                        bg--green
                    @elseif($now->gt($competition->register_end) && $now->lte($competition->competition_end))
                        bg--process
                    @endif
                     ">
                        <div class="card-title">
                            {{ $competition->title }}
                        </div>
                        <a href="{{ route('competitions.show', $competition) }}" class="btn btn--primary">Перейти</a>
                        @admin
                        <form action="{{ route('competition.destroy', $competition) }}"
                              method="POST"
                              onsubmit="return confirm('Удалить этот конкурс?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn--secondary btn-sm py-0 px-2 mt-3">
                                Удалить
                            </button>
                        </form>
                        @endadmin
                    </div>
                    <div class="card-body">
                        <p>{{ $competition->description }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
