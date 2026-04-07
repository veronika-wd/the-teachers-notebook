@extends('layout')
@section('title', 'Ученики')
@section('content')
    <h2>Ученики</h2>
    <hr>
        @foreach($classes as $class)
            <div class="row">
                    <details>
                        <summary>{{ $class->name }} класс</summary>
                        @foreach($students[$class->name] as $student)
                            <a href="{{ route('students.show', $student) }}" class="btn btn--outline">{{ $student->name }}</a>
                        @endforeach
                    </details>
            </div>
    @endforeach
@endsection
