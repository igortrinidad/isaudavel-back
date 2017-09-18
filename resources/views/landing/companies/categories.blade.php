<section class="section bg-pattern p-b-30 p-t-30">
    <div class="container">

        <h3 class="text-center m-b-30">São mais de {{ count($categories) -1 }} atividades para deixar sua vida mais saudável</h3>

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
                    <div style="height: 30px;"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('scripts')
    @parent
        <script>
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
                breakpoints: {
                    768: {
                        slidesPerView: 1
                    }
                }
            })

        </script>
    @stop
