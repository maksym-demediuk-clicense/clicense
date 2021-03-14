<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/panel">License Manager</a>
    </div>
    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <?php echo $_SESSION['username']; ?> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="/panel/setting"><i class="fa fa-gear fa-fw"></i> Settings</a>
                </li>
                <li class="divider"></li>
                <li><a href="/panel/exit"><i class="fa fa-sign-out fa-fw"></i> Sign Out</a>
                </li>
            </ul>
        </li>
    </ul>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">

                <li>
                    <a href="/panel/products"><i class="fa fa-dashboard fa-fw"></i> Product list</a>
                </li>
                <?php
                $answer = $db->query(sprintf("SELECT `id`,`name`,`version`, `contract_address` FROM `db_products` WHERE `user_id` =  %d", $_SESSION["user_id"]));
                $count_product = $db->NumRows();
                if($count_product > 0){

                    while($plugins = $db->FetchArray($answer)) {

                        echo '
                        <li> 
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> '.$plugins['name'].' ('.$plugins['version'].')<span class="fa arrow"></span></a>
                             <ul class="nav nav-second-level">'
                                .'<li><a href="/panel/products/'.$plugins['id'].'/licenses"><i class="fa fa-users"></i> View licenses</a></li>';
								//.'<li><a href="/panel/products/'.$plugins['id'].'/logs"><i class="fa fa-bar-chart"></i> View logs</a></li>'
								//'<li><a href="/panel/products/'.$plugins['id'].'/change"><i class="fa fa-cog"></i> Edit product</a></li>';

								if (!empty($plugins['contract_address']))
								{
									echo '<li><a href="#" onclick="SyncProduct('. $plugins['id'] . ')"><i class="fa fa-refresh"></i> Sync with blockchain</a></li>';
									if(isset($_GET['plugin_id']) && $plugins['id'] == intval($_GET['plugin_id']) &&
										$_GET['sel'] == 'keys'){
											echo '<li><a href="#" class="generate-key" data-value="'.$plugins['id'].' "><i class="fa fa-key"></i> Generate key</a></li>';
										}else{
											echo '<li><a href="/panel/products/'.$plugins['id'].'/new_license"><i class="fa fa-key"></i> Add License</a></li>';
										}
								}
								else
								{
                                    echo '<li><a href="#" onclick="SyncProduct('. $plugins['id'] . ')"><i class="fa fa-refresh"></i> Sync required</a></li>';								
								}

                            echo'</ul>
                        </li>';
                    }
                }
                else{
                    echo '<li> Product list is empty </li>';
                }

                ?>
                <li>
                    <a href="/panel/products/new">Add product</a>
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>