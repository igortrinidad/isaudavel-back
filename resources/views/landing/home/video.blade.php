<style media="screen">
    .section{ background: #F4F5F5; }

    .video-container {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    .video-container video {
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }
    .slogan {
        font-size: 60px;
        line-height: 80px;
        font-weight: 700;
        height: 170px;
        padding: 20px 20px 20px 20px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin: 0 -15px;
        background: rgba(0, 0, 0, 0) linear-gradient(180deg, #88C657 20%, #6EC058 100%) repeat scroll 0 0;
        color: #f4f5f5;
        text-shadow: 0 4px 5px rgba(0, 0, 0, .3);
        text-align: center;
    }

    .slogan-container {
        width: 100%;
        height: 170px;
        position: absolute;
        top: 50%;
        margin-top: -75px;
    }
    .screenshot {
        position: absolute;
        max-width: 230px;
        bottom: 90px; right: 130px;
        z-index: 100;
    }

    .slogan-icon-inverse { display: none; }

    @media (max-width: 1280px) {
        .slogan { padding-right: 230px; font-size: 50px; line-height: 50px; text-align: left; }
        .screenshot{ right: 30px; }
        .slogan-container { padding: 15px; }
    }
    @media (max-width: 768px) {
        .slogan { padding-right: 230px; text-align: left; font-size: 30px; line-height: 30px;}
        .screenshot{ bottom: 120px; right: 15px; }
    }
    @media (max-width: 414px) {
        .slogan { padding-right: 165px; }
        .screenshot{ max-width: 150px; }
    }

    @media (max-width: 320px) {
        .slogan-container { top: 40%; }
        .slogan-icon{ display: none; }
        .slogan-icon-inverse {
            display: block;
            position: absolute;
            width: 50px;
            top: 100px;
            left: 50%;
            margin-left: -25px;
        }
    }
</style>

<div class="video-section" style="height: calc(100vh - 112px);">

    <div class="video-container">
      <video autoplay loop="true" width="1280" height="720">
        <source type="video/mp4" src="https://d2v9y0dukr6mq2.cloudfront.net/video/preview/ibUZgsf/kipping-pull-ups-crossfit_njexfxci__PM.mp4">
      </video>
    </div>

    <img src="icons/icon_p.png" class="slogan-icon-inverse m-r-10" alt="">

    <!-- Screenshot -->
    <img class="screenshot" src="https://weplaces.com.br/assets/weplaces/iphone_black/screen1.png" alt="Screen 1">

    <!-- Slogan -->
    <div class="slogan-container">
        <div class="container">
            <h2 class="slogan">
                <img src="icons/icon_p.png" class="slogan-icon m-r-10" alt="">
                A sua saúde em boas mãos.
            </h2>
        </div>
    </div>


</div>
