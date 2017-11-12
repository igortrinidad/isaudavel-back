@extends('landing.events.index')

@section('landing-content')
    <style media="screen">
        .form-container {
            max-width: 768px;
            margin: 0 auto;
        }
        .form-control:focus {
            box-shadow: 0 0 4px rgba(110, 192, 88, .4);
            border-color: #6ec058;
        }
        .confirm-image {
            max-width: 100px;
            display: block;
            margin-left: -5px;
            position: relative;
            top: 10px;
        }
        .card-body.card-custom-padding{
            padding: 43px 14px;
        }
    </style>

    <section class="section gray p-t-30 p-b-0">

        <div class="container">

            @if(isset($page_blank_header))
                <h2 class="text-center m-t-30">{!! $page_blank_header !!}</h2>
            @endif

            <div class="row m-t-30">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-custom-padding">

                            @if(isset($page_blank_message))
                                {!! $page_blank_message !!}
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-t-30">
                <div class="col-sm-12">
                    @include('landing.components.card-to-download', ['card_message' => 'Baixe o aplicativo iSaudavel e divulgue seus serviços através da plataforma mais fitness do mundo!'])
                </div>
            </div>

        </div>

    </section>

@stop

