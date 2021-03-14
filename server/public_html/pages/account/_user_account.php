<div id="wrapper">
       <?php
       @include("inc/_navi.php");
       $title = "Main page";
       ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Welcome, <?php echo $_SESSION['username']?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div>Product count</div>
                                    <div class="huge">
                                        <?php
                                        echo $count_product;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div>User count</div>
                                    <?php
                                        $result = $db->query(sprintf("SELECT COUNT(*) FROM db_licenses INNER JOIN db_products ON db_products.id = db_licenses.product_id INNER JOIN db_users ON db_users.id = db_products.user_id WHERE db_users.id = %d",
                                            $_SESSION['user_id']))->fetch_array();
                                        echo '<div class="huge">'.$result[0].'</div>';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>