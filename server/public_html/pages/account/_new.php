<div id="wrapper">
    <?php
    @include("inc/_navi.php");
    ?>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-3">
                <h1 class="page-header">Add Product</h1>
            </div>
            <div class="row">
                <div class="col-md-5 col-md-offset-0">
                    <div class="login-panel panel panel-default">
                        <div class="panel-body">
                            <form role="form" action="" id="product-form">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Product name" id="name_product" type="text" value="" data-required="true">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Product version" id="ver_product" type="text" value="" data-required="true">
                                    </div>
									<div class="form-group">
										Select Smart Contract:<br>
										<select class="form_control" id="contract_product" data-required="true">
										<?php
										$dbi = new mysqli($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);
										$dbi->set_charset("utf8");
										if(!$dbi->connect_errno){
											$result = $dbi->query("SELECT id, name FROM `db_eth_contracts`");
											while($row = $result->fetch_assoc()) {
			
												echo '
												<option value='.$row['id'].'>'.$row['name'].'</option>
												';
											}
											$dbi->close();
										}
										?>
										</select>
                                    </div>
									<div class="form-group">
										Select Ethereum Account:<br>
										<select class="form_control" id="account_product" data-required="true">
										<?php
										$dbi = new mysqli($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);
										$dbi->set_charset("utf8");
										if(!$dbi->connect_errno){
											$result = $dbi->query("SELECT id, address FROM `db_eth_accounts`");
											while($row = $result->fetch_assoc()) {
			
												echo '
												<option value='.$row['id'].'>'.$row['address'].'</option>
												';
											}
											$dbi->close();
										}
										?>
										</select>
                                    </div>
									<div class="form-group">
										Select Ethereum Provider:<br>
										<select class="form_control" id="api_product" data-required="true">
										<?php
										$dbi = new mysqli($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);
										$dbi->set_charset("utf8");
										if(!$dbi->connect_errno){
											$result = $dbi->query("SELECT id, name FROM `db_eth_providers`");
											while($row = $result->fetch_assoc()) {
			
												echo '
												<option value='.$row['id'].'>'.$row['name'].'</option>
												';
											}
											$dbi->close();
										}
										?>
										</select>
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