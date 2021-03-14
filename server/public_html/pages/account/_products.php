<div id="wrapper">
    <?php
    @include("inc/_navi.php");
    $title = "Product list";
    ?>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Product list</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">

                <div class="panel panel-default">
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Version</th>
                                <th>License count</th>
								<th>Transaction</th>
                                <th>Contract</th>
                                <th>Sync date</th>

                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $dbi = new mysqli($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);
                            $dbi->set_charset("utf8");
                            if(!$dbi->connect_errno){
                                $result = $dbi->query(sprintf("SELECT * FROM `db_products` WHERE `user_id` =  %d", $_SESSION["user_id"]));
                                while($plugins = $result->fetch_assoc()) {

                                    echo '
                                    <tr>
                                        
                                        <td>'.$plugins['id'].'</td>
                                        <td>'.$plugins['name'].'</td>
                                        <td>'.$plugins['version'].'</td>';
                                            //--------------------------------------------------------------------------------------------------------
                                            $license_count = $dbi->query(sprintf("SELECT COUNT(*) FROM `db_licenses` WHERE `product_id` = %d", $plugins['id']))->fetch_array();
                                            $license_count = $license_count[0] != NULL ? $license_count[0] : '0';
                                            
											$tx_api = $dbi->query(sprintf("SELECT `tx_info_api` FROM `db_eth_providers` WHERE `id` = %d", $plugins['provider_id']))->fetch_array();
											$contract_addr = ($plugins['contract_address']);
                                                echo    '
            
                                        <td>'.$license_count.'</td>
										<td><a href="'.$tx_api['tx_info_api'].'tx/'.$plugins['transaction_id'].'" class="btn btn-primary">Etherscan</a></td>
                                        <td>';
                                        if (!empty($plugins['contract_address']))
                                        {
                                            echo '<a href="'.$tx_api['tx_info_api'].'address/'.$plugins['contract_address'].'" class="btn btn-primary">Etherscan</a>';
                                        }
                                        echo '
                                        </td>
                                        <td>'.$plugins['sync_date'].'</td> 
                                        <td>';
                                            if (!empty($contract_addr))
                                            {
                                                echo 
                                                '<a href="/panel/products/'.$plugins['id'].'/licenses" class="btn btn-info btn-large">Licenses</a>
                                                <a class="btn btn-success" onclick="SyncProduct('. $plugins['id'] . ')">Sync</a>';
                                            }
                                            else
                                            {
                                                echo
                                                '<a class="btn btn-warning" onclick="SyncProduct('. $plugins['id'] . ')">Sync required</a>';
                                            }

                                           echo ' 
                                        </td>
                                    </tr>
                                    ';
                                }
                                $dbi->close();
                            }
                            ?>
                            </tbody>
                        </table>
                        <!-- /.table-responsive -->
                        <a href="/panel/products/new" class="btn btn-primary">Add product</a>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->

    </div>
<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
</script>