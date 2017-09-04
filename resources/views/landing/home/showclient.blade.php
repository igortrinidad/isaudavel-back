@extends('landing.companies.index', ['header_with_search' => false])

@section('landing-content')
    <style>
        .bg-picture {
            display: block;
            box-sizing: border-box;
            margin: 0 auto;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            width: 100%;
            height: 350px;
        }

        .picture-circle{
            box-sizing: border-box;
            margin: 0 auto;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }

        .picture-circle-p{
            width: 68px;
            height: 68px;
        }

        .picture-circle-m{
            width: 86px;
            height: 86px;
        }

        .bg-header-company{
            display: block;
            background-color: #000000;
            opacity: 0.8;
            width: 100%;
            height: auto;
            padding: 10px 10px 10px 25px;
        }

        .bg-header-company-name{
            color: #fff;
            font-weight:400;
        }
    </style>

    <header>
        <div class="bg-picture" style="background-image:url({{$client_fetched->avatar}})">
        </div>
        <div class="bg-header-company">
            <h1 class="bg-header-company-name">{{$client_fetched->full_name}}</h1>
        </div>
    </header>

    <section class="m-t-20">
    </section>

    @section('scripts')
        @parent

        <script>
        </script>
    @stop
@stop
