<style media="screen">

</style>

<section id="about" class="section">
    <div class="container">
        <h2 class="f-300 text-center">Para profissionais</h2>
    </div>

    <div class="container">
        <div class="row m-t-30">
            <div class="col-md-12 col-xs-12 text-center">
                <h4 class="f-300 m-b-30">Saiba mais sobre o Isaudavel para clientes.</h4>
                <a href="/new-landing/para-voce">
                    <button class="btn btn-primary m-t-10 f-300">Para Você</button>
                </a>
            </div>
        </div>
    </div>

</section>

@section('scripts')
    @parent

    <script>
        var swiperScreenshots = new Swiper('.swiper-screenshots', {
            centeredSlides: true,
            spaceBetween: 15,
            loop: false,
            slidesPerView: 4,
            slideToClickedSlide: true,
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
