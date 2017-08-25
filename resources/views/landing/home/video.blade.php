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
    /* Make video to at least 100% wide and tall */
    min-width: 100%;
    min-height: 100%;
    /* Setting width & height to auto prevents the browser from stretching or squishing the video */
    width: auto;
    height: auto;
    /* Center the video */
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    }

</style>

<div class="video-container">

  <video autoplay loop="true" width="1280" height="720">
    <source type="video/mp4" src="https://coverr.co/s3/mp4/Strole_in_the_Park.mp4">
  </video>

</div>
