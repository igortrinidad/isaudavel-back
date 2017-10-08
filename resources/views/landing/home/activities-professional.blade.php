<!-- Profissionals Activities -->
<div class="row wow fadeInUp">
    <div class="col-sm-12">
        <div class="card card-activities">
            <h4 class="is-activities-title"><small>atividades de</small>Profissionais</h4>
            <div class="card-body">
                <div class="swiper-container swiper-professional-activities">
                    <div class="swiper-wrapper p-t-10">
                        @foreach($companies as $index_company => $company)
                            <div class="swiper-slide text-center p-l-10 p-r-10">
                                <div class="is-box">
                                    <div class="picture-circle picture-circle-p m-b-10" style="background-image:url({{$company->avatar}})">
                                    </div>
                                    <h5 class="f-300 t-overflow m-b-5">{{ $company->name }} adicionou <strong>Tapioca Fit</strong></h5>
                                    <span class="label label-success">Receita</span>
                                    <a href="#" class="label label-primary" title="Confira a reiceita de {{ $company->name }}">Ver mais</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-prev is-swiper-button-default arrow-ms arrow-top"><i class="ion-ios-arrow-back"></i></div>
                    <div class="swiper-button-next is-swiper-button-default arrow-ms arrow-top"><i class="ion-ios-arrow-forward"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Profissionals Activities -->
