<div id="wrapper">
    <?php
    @include("inc/_navi.php");
    $plugin_id = intval($_GET['plugin_id']);
    ?>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-3">
                <h1 class="page-header">Add License</h1>
            </div>
            <div class="row">
                <div class="col-md-5 col-md-offset-0">
                    <div class="login-panel panel panel-default">
                        <div class="panel-body">
                            <form role="form" action="" id="license-form">
                                <fieldset>
                                    <div class="form-group">
                                        <input type="hidden" id="plugin_id" value="<?php echo $plugin_id ?>">
                                        <input class="form-control" placeholder="Email" id="email" type="text" value="" data-required="true">
                                    </div>
                                    <div style="display: none;" id="prod_result"></div>
                                    <span id="sign-form-valid">
									    <input type="submit" class="btn btn-lg btn-success btn-block" value="Add">
								    </span>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-wrapper -->

</div>