@extends('landing.home.index')

@section('content')

    <style media="screen">
        a, a:hover{ color: #383939; text-decoration: none; }
        .min-height-card { min-height: 370px; }
        .divider{
            background: rgba(0, 0, 0, 0) linear-gradient(180deg, #FFFFFF 50%, #F4F5F5 100%) repeat scroll 0 0;
        }
    </style>

    <section id="contact" class="section divider">
        <div class="container">

            <h2 class="text-center">Para você e profissionais de saúde</h2>

            <div class="row m-t-30">
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

            </div>
        </div>
    </section>


@stop
