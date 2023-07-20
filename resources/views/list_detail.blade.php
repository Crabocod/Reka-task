@extends('templates.main')

@section('title', 'Reka-TODO - Задачи')


@section('content')

    <div class="container">

        <p class="h2">Список {{ $list->title }} <button type="button" class="btn btn-primary ms-4"  data-bs-toggle="modal" data-bs-target="#add-task">Создать задачу</button></p>

        <p class="h3 mt-3">Задачи</p>
        <label for="">Поиск по задачам:</label>
        <p>
            <input type="text" name="search" id="tasks-search" class="w-100" data-list_id="{{ $list->id }}">
        </p>

        <p>
            <select name="tags" class="form-control" multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag }}">{{ $tag }}</option>
                @endforeach
            </select>
        </p>
        <button type="button" class="btn btn-primary tasks-search w-100">Поиск</button>


        <div id="tasks-container" class="mt-1 row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            {!! $tasks !!}
        </div>


    </div>


@endsection
