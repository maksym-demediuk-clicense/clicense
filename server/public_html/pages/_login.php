<?php
if(isset($_SESSION['user_id'])) { header('location: /panel');}
$title = "Authorization";
?>
 <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Authorization</h3>
						
                    </div>
                    <div class="panel-body">
                        <form role="form" action="" id="login-form">
                            <fieldset>
                                <div class="form-group input-group">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input class="form-control" placeholder="Login" id="login" type="text" autofocus data-required="true">
                                </div>
                                <div class="form-group input-group">
								<span class="input-group-addon"><i class="fa fa-key"></i></span>
                                    <input class="form-control" placeholder="Password" id="password" type="password" value="" data-required="true">
                                </div>
								<div style="display: none;" id="log_result"></div>
								
								<span id="sign-form-valid">
									<input type="submit" class="btn btn-lg btn-success btn-block" value="Sign In">
								</span>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>