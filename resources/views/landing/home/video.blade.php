<style media="screen">
    .section{ background: #F4F5F5; }

    .video-container {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
        height: 600px;
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
        position: relative;
        font-size: 60px;
        line-height: 80px;
        font-weight: 400;
        height: 200px;
        background: #f4f5f5;
        margin-top: 20%;
        text-align: center;
        vertical-align: middle;
        padding: 20px 40px 20px 40px;
        border-radius: 4px;
    }

    .screenshot {
        position: absolute;
        max-width: 230px;
        bottom: -60px; right: 40px;
    }

    @media screen and (max-width: 768px) {
        .slogan{
            margin-top: 70%;
            font-size: 22px;
        }
    }
</style>

<div class="video-section">

    <div class="video-container">
      <video autoplay loop="true" width="1280" height="720">
        <source type="video/mp4" src="https://d2v9y0dukr6mq2.cloudfront.net/video/preview/ibUZgsf/kipping-pull-ups-crossfit_njexfxci__PM.mp4">
      </video>
    </div>

        <div class="clearfix" style="position: relative; height: 600px;">
        <div class="container text-center">
                <h2 class="slogan">A sua saúde em boas <strong class="f-700">mãos</strong>.</h2>
                <img class="screenshot" src="https://weplaces.com.br/assets/weplaces/iphone_black/screen1.png" alt="Screen 1">
            </div>
        </div>
</div>
