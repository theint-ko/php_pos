<div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>SG POS</span></a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="<?php echo $base_url;?>asset/images/img.jpg" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Welcome,</span>
                            <h2>John Doe</h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li><a href="index.html"><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                        
                    </li>
                    <li><a href="<?php echo $cp_base_url;?>shift.php"><i class="fa fa-ship"></i> Shifting<span class="fa fa-chevron-down"></span></a>
                    <li><a><i class="fa fa-edit"></i> Category<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="<?php echo $cp_base_url;?>category_create.php">Create</a></li>
                            <li><a href="<?php echo $cp_base_url;?>category_listing.php">List</a></li>
                        
                        </ul>
                            <li><a><i class="fa fa-cubes"></i> Item<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                            <li><a href="<?php echo $cp_base_url;?>item_create.php">Create</a></li>
                            <li><a href="<?php echo $cp_base_url;?>item_list.php">List</a></li>      
                        </ul>
                    </li>
                    
                    <li><a><i class="fa fa-percent"></i> Discount<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="<?php echo $cp_base_url;?>discount_create.php">Create</a></li>
                            <li><a href="<?php echo $cp_base_url;?>discount_list.php">List</a></li>      

                        </ul>
                    </li>

                    <li><a><i class="fa fa-ship"></i>Admin<span class="fa fa-ship"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="<?php echo $cp_base_url;?>admin_create.php">Create</a></li>
                            <li><a href="<?php echo $cp_base_url;?>admin_list.php">List</a></li>
                        
                        </ul>
                    </li>

                    <li><a><i class="fa fa-ship"></i>Cashier<span class="fa fa-ship"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="<?php echo $cp_base_url;?>cashier_create.php">Create</a></li>
                            <li><a href="<?php echo $cp_base_url;?>cashier_list.php">List</a></li>
                        
                        </ul>
                    </li>

                    <li><a><i class="fa fa-home"></i>Setting<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="<?php echo $cp_base_url;?>setting_create.php">Create</a></li>
                            <li><a href="<?php echo $cp_base_url;?>setting_list.php">List</a></li>
                        
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->


    </div>
</div>
