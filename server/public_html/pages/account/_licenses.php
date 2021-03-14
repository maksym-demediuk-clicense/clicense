<div id="wrapper">
    <?php
    @include("inc/_navi.php");
    $product_id = intval($_GET['plugin_id']);
    $dbi = new mysqli($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);
    $dbi->set_charset("utf8");
    if(!$dbi->connect_errno){
        $result = $dbi->query(sprintf("SELECT `name` FROM `db_products` WHERE id = %d", $product_id));
        $info = $result->fetch_array();
    }
    $title = 'License list for '.$info['name'];
    ?>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">License list for <?php echo $info['name']?></h1>
                <div style="display: none" id="delete_result"></div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">

            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php
                    if(!$dbi->connect_errno){
                        $licenses = $dbi->query(sprintf("SELECT `id`, `transaction_add`, `email`, `public_key`, `transaction_activate` FROM `db_licenses` WHERE `product_id` = %d",
                            $product_id));
                        $product = $dbi->query(sprintf("SELECT `provider_id` FROM `db_products` WHERE `id` = %d", $product_id))->fetch_array();
                        $tx_api = $dbi->query(sprintf("SELECT `tx_info_api` FROM `db_eth_providers` WHERE `id` = %d", $product['provider_id']))->fetch_array();
                        $rows = $licenses->num_rows;
                        if( $rows> 0){
                            echo'
                        <table width="100%" class="table table-striped table-bordered table-hover" id="data_licenses">
                            <thead>
                            <tr>
                                <th>Transaction</th>
                                <th>Email</th>
                                <th>Key</th>
                                <th>Activated</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>';
                                    while ($license = $licenses->fetch_assoc()){
                                        $echoed = '
                                        <tr>

                                        <td><a href="'.$tx_api['tx_info_api'].'tx/'.$license['transaction_add'].'" class="btn btn-primary">Etherscan</a></td>
                                        <td>'.$license['email'].'</td>
                                        <td>'.$license['public_key'].'</td>';
                                        $tx_activate = $license['transaction_activate'];
                                        if (!empty($tx_activate))
                                        {
                                            $echoed .= '<td><a href="'.$tx_api['tx_info_api'].'tx/'.$tx_activate.'" class="btn btn-primary">Etherscan</a></td>';
                                        }
                                        else
                                        {
                                            $echoed .= '<td>NO</td>';
                                        }
                                        $public_key = $license['id'];
                                        $echoed .= '
                                        <td>
											<a class="btn btn-danger" onclick="DeleteLicense(this, ' . $public_key . ', ' . $product_id . ')" id="keyid_'.$license['id'].'">Delete</a>
                                        </td>
                                        </tr>
                                        ';
                                        echo $echoed;
                                    }
                            echo'
                            </tbody>
                        </table>
                        <a href="/panel/products/' . $product_id . '/new_license" class="btn btn-primary">Add license</a>';
                        }
                        else{
                            echo 'No items';
                            echo '<br><br><a href="/panel/products/' . $product_id . '/new_license" class="btn btn-primary">Add license</a>';
                        }
                        $dbi->close();
                    }
                    ?>
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

<?php
if($rows > 0) {
    echo "<script> $(document).ready(function () { $('#data_licenses') . DataTable({ responsive: true }); }); </script>";
}
?>