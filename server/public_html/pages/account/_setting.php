<div id="wrapper">
    <?php
    @include("inc/_navi.php");
    ?>


    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Change password</h1>
            </div>
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-body">
                            <form role="form" action="" id="setting-form">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Old password" id="old_pass" type="password" value="" data-required="true">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="New password" id="new_pass" type="password" value="" data-required="true">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Repeat new password" id="rep_pass" type="password" value="" data-required="true">
                                    </div>
                                    <div style="display: none;" id="set_result"></div>

                                    <span id="sign-form-valid">
									    <input type="submit" class="btn btn-lg btn-success btn-block" value="Change">
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