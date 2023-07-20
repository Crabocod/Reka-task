@extends('templates.main')

@section('title', 'Reka-TODO - Списки')


@section('content')

    <div class="container">


        @auth

            <p class="h2">Доступные списки <button type="button" class="btn btn-primary ms-4"  data-bs-toggle="modal" data-bs-target="#add-list">Создать список</button></p>



            <div id="lists-container" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                {!! $lists !!}
            </div>



        @endauth

        @guest
            <p class="h2">Авторизуйтесь чтобы увидеть ваши списки</p>
        @endguest


    </div>


@endsection
