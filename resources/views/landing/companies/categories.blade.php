<style media="screen">
    #categories-section { transition: ease .3s; }

    #categories-section.pilates     { background-color: #615de2 }
    #categories-section.personal    { background-color: #00cbb2 }
    #categories-section.fisio       { background-color: #54d4de }
    #categories-section.nutri       { background-color: #9c40a9 }
    #categories-section.crossfit    { background-color: #6ccf5f }
    #categories-section.massagem    { background-color: #d65e00 }
    #categories-section.acupuntura  { background-color: #f8d163 }
    #categories-section.academia    { background-color: #cc427f }
    #categories-section.demarto     { background-color: #c192e2 }
    #categories-section.cardio      { background-color: #d64646 }
    #categories-section.ortopedia   { background-color: #63b0ce }
</style>
<section id="categories-section" class="section bg-pattern p-b-30 p-t-30 pilates">
    <div class="container">

        <h3 class="text-center m-b-30">Selecione uma categoria para pesquisar</h3>

        <div class="row m-t-10">
            <div class="col-sm-12">
                <div class="swiper-container swiper-categories wow fadeInUp">
                    <div class="swiper-wrapper">
                        @foreach($categories as $category)
                            <div class="swiper-slide">
                                <div class="card category">
                                    <a href="{{route('landing.search.index', ['category' => $category->slug])}}">
                                        <div
                                            class="card-header ch-alt picture-bg"
                                            style="background-image:url({{ $category->avatar }})"
                                        >
                                        </div>
                                        <div class="card-body card-padding text-center">
                                            <h5 class="f-300">{{ $category->name }}</h5>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-prev">
                        <i class="ion-ios-arrow-left"></i>
                    </div>
                    <div class="swiper-button-next">
                        <i class="ion-ios-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('scripts')
    @parent
        <script>
            var setClassToChangeColorBeta = function (pos) {
                var classesColors = ['pilates', 'personal', 'fisio', 'nutri', 'crossfit', 'massagem', 'acupuntura', 'academia', 'demarto', 'cardio', 'ortopedia']
                $('#categories-section').attr('class', 'section bg-pattern p-b-30 p-t-30')
                $('#categories-section').addClass(classesColors[pos])
            }
            var swiperFeatureds = new Swiper('.swiper-categories', {
                centeredSlides: true,
                spaceBetween: 15,
                loop: true,
                slidesPerView: 5,
                slideToClickedSlide: false,
                paginationClickable: true,
                pagination: '.swiper-pagination',
                prevButton: '.swiper-button-prev',
                nextButton: '.swiper-button-next',
                onSlideChangeEnd: swiper => {
                    setClassToChangeColorBeta(swiper.realIndex)
                },
                breakpoints: {
                    768: {
                        slidesPerView: 1
                    }
                }
            })

        </script>
    @stop
