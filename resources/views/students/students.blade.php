@extends('layout')
@section('title', 'Ученики')
@section('content')
    <h2>Ученики</h2>
    <hr>
        @foreach($classes as $class)
            <div class="row">
                    <details>
                        <summary>{{ $class->name }} класс | Количество человек: {{ count($students[$class->name]) }}</summary>
                        @foreach($students[$class->name] as $student)
                            <a href="{{ route('students.show', $student) }}" class="btn btn--outline">{{ $student->surname . ' ' . $student->name . ' ' . $student->patronymic }}</a>
                        @endforeach
                    </details>
            </div>
    @endforeach
@endsection
