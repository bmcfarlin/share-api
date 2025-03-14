

                </div> <!-- content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-6 text-md-right">
                                <?php include('copyright.php'); ?>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

    </form>


<!-- Standard modal -->
<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                Are you sure?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary">Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



  <script src="<?php echo CDN_URL; ?>/assets/js/vendor.min.js"></script>
  <script src="<?php echo CDN_URL; ?>/assets/js/app.min.js"></script>
  <script src="<?php echo CDN_URL; ?>/js/jquery.alerts.js"></script>
  <script src="<?php echo CDN_URL; ?>/js/jquery.cookie.js"></script>
  <script src="<?php echo CDN_URL; ?>/js/admin.common.js"></script>

<?php
  $html = null;
  if(isset($scripts)){
    foreach($scripts as $script){
      if(starts_with($script, '/js')) {
        $html .= sprintf("  <script src=\"%s%s?v=%s\"></script>\n", CDN_URL, $script, JS_VERSION);
      }else{
        $html .= sprintf("  <script src=\"%s\"></script>\n", $script);
      }
    }
  }
  print $html;
?>


</body>
</html>

