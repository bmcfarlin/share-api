  </section>
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <ul>
            <li><a href="/en/"><div class="logo"></div></a></li>
            <li><a href="/en/terms">Terms of Service</a></li>
            <li><a href="/en/privacy">Privacy Policy</a></li>
            <li><?php include('copyright.php'); ?></li>
          </ul>
        </div>
        <div class="col-md-5">
        </div>
        <div class="col-md-3">
          <ul>
            <li><a href="/en/about">About</a></li>
            <li><a href="/en/contact">Contact</a></li>
          </ul>
          <div class="social">
            <a href="javascript:void(0)" target="_blank">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 408.8 408.8"><path class="fill" d="M353.7 0H55.1C24.7 0 0 24.7 0 55.1v298.6c0 30.4 24.7 55.1 55.1 55.1h147.3l0.3-146.1h-38c-4.9 0-8.9-4-9-8.9l-0.2-47.1c0-5 4-9 9-9h37.9v-45.5c0-52.8 32.2-81.5 79.3-81.5h38.7c4.9 0 9 4 9 9v39.7c0 4.9-4 9-8.9 9l-23.7 0c-25.6 0-30.6 12.2-30.6 30v39.4h56.3c5.4 0 9.5 4.7 8.9 10l-5.6 47.1c-0.5 4.5-4.4 7.9-8.9 7.9h-50.5l-0.3 146.1h87.6c30.4 0 55.1-24.7 55.1-55.1V55.1C408.8 24.7 384.1 0 353.7 0z"></path></svg>
            </a>
            <a href="javascript:void(0)" target="_blank">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 410.2 410.2"><path class="fill" d="M403.6 74.2c-9.1 4-18.6 7.2-28.3 9.5 10.7-10.2 18.7-22.9 23.3-37.1l0 0c1.3-4.1-3.1-7.6-6.8-5.4l0 0c-13.5 8-28 14-43.2 17.9 -0.9 0.2-1.8 0.3-2.7 0.3 -2.8 0-5.5-1-7.6-2.9 -16.2-14.2-36.9-22.1-58.5-22.1 -9.3 0-18.8 1.5-28 4.3 -28.7 8.9-50.8 32.5-57.7 61.7 -2.6 10.9-3.3 21.9-2.1 32.6 0.1 1.2-0.4 2.1-0.8 2.5 -0.6 0.7-1.5 1.1-2.4 1.1 -0.1 0-0.2 0-0.3 0 -62.8-5.8-119.4-36.1-159.4-85.1l0 0c-2-2.5-6-2.2-7.6 0.6l0 0C13.7 65.6 9.5 80.9 9.5 96.6c0 24 9.6 46.6 26.4 63 -7-1.7-13.8-4.3-20.2-7.8l0 0c-3.1-1.7-6.8 0.5-6.9 4l0 0c-0.4 35.6 20.4 67.3 51.6 81.6 -0.6 0-1.3 0-1.9 0 -5 0-10-0.5-14.9-1.4l0 0c-3.4-0.7-6.3 2.6-5.3 6l0 0c10.1 31.7 37.4 55 70 60.3 -27.1 18.2-58.6 27.8-91.4 27.8l-10.2 0c-3.2 0-5.8 2.1-6.6 5.1 -0.8 3 0.7 6.2 3.4 7.7 37 21.5 79.1 32.9 122 32.9 37.5 0 72.5-7.4 104.2-22.1 29-13.4 54.7-32.7 76.3-57.1 20.1-22.8 35.8-49.1 46.7-78.2 10.4-27.7 15.9-57.3 15.9-85.6v-1.3c0-4.5 2.1-8.8 5.6-11.7 13.6-11 25.4-24 35.2-38.6l0 0C411.9 77.1 407.9 72.3 403.6 74.2L403.6 74.2z"></path></svg>
            </a>
            <a href="javascript:void(0)" target="_blank">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path class="fill" d="M352 0H160C71.6 0 0 71.6 0 160v192c0 88.4 71.6 160 160 160h192c88.4 0 160-71.6 160-160V160C512 71.6 440.4 0 352 0zM464 352c0 61.8-50.2 112-112 112H160c-61.8 0-112-50.2-112-112V160C48 98.2 98.2 48 160 48h192c61.8 0 112 50.2 112 112V352z"></path><path class="fill" d="M256 128c-70.7 0-128 57.3-128 128s57.3 128 128 128 128-57.3 128-128S326.7 128 256 128zM256 336c-44.1 0-80-35.9-80-80 0-44.1 35.9-80 80-80s80 35.9 80 80C336 300.1 300.1 336 256 336z"></path><circle class="fill" cx="393.6" cy="118.4" r="17.1"></circle></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </footer>

<?php
  $html = null;
  $html .= "      <div class=\"island\"";
  if(isset($datas)){
    foreach($datas as $key => $value){
      $html .= sprintf(" data-%s=\"%s\"", $key, $value);
    }
  }
  $html .= "></div>\n";
  print $html;
?>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.2/TweenMax.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.6/ScrollMagic.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.6/plugins/animation.gsap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.0.13/howler.min.js" integrity="sha384-sFkNm3ufIBzWAEmeHSpFm2ssvymvtHM/tWp7sSasJfEtwWoHYkkpVAr+neXTwJxH" crossorigin="anonymous"></script>
  <script src="<?php echo CDN_URL; ?>/js/jquery.cookie.js?v=<?php echo JS_VERSION; ?>"></script>
  <script src="<?php echo CDN_URL; ?>/js/common.js?v=<?php echo JS_VERSION; ?>"></script>

<?php
  $html = null;
  if(isset($scripts)){
    foreach($scripts as $script){
      $html .= sprintf("  <script src=\"%s?v=%s\"></script>\n", $script, JS_VERSION);
    }
  }
  print $html;
?>

</body>
</html>


