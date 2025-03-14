  </section>

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


