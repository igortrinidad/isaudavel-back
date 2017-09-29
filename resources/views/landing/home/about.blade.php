@extends('landing.home.index')


@section('content')

    <style media="screen">
        a, a:hover{ color: #383939; text-decoration: none; }
        .min-height-card { min-height: 370px; }
        .section.divider{
            background-color: transparent;
            background-image: url('https://image.ibb.co/ncHZ1v/home.jpg') !important;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed
        }
    </style>

    @include('landing.companies.categories')

    <section id="about" class="section divider">
        <div class="container">
            <div class="text-center">
                <h2 class="f-700 text-center m-b-10" style="color: #fff;">Para você e profissionais de </h2>
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="60px" height="20px" viewBox="0 0 60 20" enable-background="new 0 0 60 20" xml:space="preserve">
                    <path fill="none" stroke="#FFFFFF" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" d="M3.188,8.848 C20.521,3.89,38.313,16.66,56.813,9.473" style="stroke-dasharray: 54.3116, 54.3116; stroke-dashoffset: 0;"></path>
                </svg>
            </div>

            <!-- <div class="row m-t-30">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="card min-height-card wow fadeInUp m-t-30">
                        <div class="card-header ch-alt text-center">
                            <h2 class="f-300">Para você</h2>
                        </div>
                        <div class="card-body card-padding text-justify">
                            <p class="f-16 m-t-10 f-300">
                                No <b>iSaudavel</b> você encontrará profissionais especializados em sua saúde como personal trainer, nutricionista, estúdios de pilates, academia, fisioterapia, crossfit e diversas clínicas de saúde e bem estar - todos unidos em só lugar e você poderá compartilhar as principais informações sobre sua saúde e objetivos com esses profissionais, que juntos irão te ajudar a atingir seus objetivos de saúde, estética e bem estar.
                            </p>
                            <div class="text-center m-t-30">
                                <a href="{!! route('landing.clients.about') !!}" class="btn btn-success f-300 f-16">Saiba mais</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 ">
        			<div class="card min-height-card wow fadeInUp m-t-30">
        				<div class="card-header ch-alt text-center">
        					<h2 class="f-300">Para profissinoais</h2>
        				</div>
                        <div class="card-body card-padding text-justify">
                            <p class="f-16 m-t-10 f-300">
                                O <b>iSaudavel</b> foi criado para você profissional da área da saúde e seu cliente economizarem o mais importante da vida: <b>TEMPO</b>. Uma rede social dedicada para você divulgar seus serviços e organizar o atendimento a seu cliente de forma simplificada e objetiva, integrando outros profissionais que assim como você estão comprometidos à promover a saúde e bem estar de seus clientes.
                            </p>
                            <div class="text-center m-t-30">
                                <a href="{!! route('landing.professionals.about') !!}" class="btn btn-success f-300 f-16">Saiba mais</a>
                            </div>
                        </div>
        			</div>
                </div>
            </div> -->
        </div>
    </section>


@stop
