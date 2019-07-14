<!DOCTYPE html>
<html>

<head>
    <title>Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
<!--    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>-->
    <link href='https://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Ranga:400,700' rel='stylesheet' type='text/css'>
    
    <!-- CSS Libs -->
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-switch.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/checkbox3.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="/assets/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/select2-bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/datepicker/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/jqueryui/jquery-ui.css">
    <!-- CSS App -->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/themes/flat-blue.css">
    <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap.js"></script>
    
    <script type="text/javascript">
    var BASE_URL = "<?php echo base_url(); ?>";
    </script>
    
</head>

<body class="flat-blue">
    <div class="app-container">
        <div class="row content-container">
            <nav class="navbar navbar-default navbar-fixed-top navbar-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-expand-toggle">
                            <i class="fa fa-bars icon"></i>
                        </button>
                        <ol class="breadcrumb navbar-breadcrumb">
                            <li><?php echo $bc1;?></li>
                            <li class="active"><?php echo $bc2;?></li>
                        </ol>
                        <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                            <i class="fa fa-th icon"></i>
                        </button>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if($user->type != 3){?>
                        <li style="height: 60px;line-height: 60px;padding: 0 20px;text-transform: uppercase;text-transform: uppercase;color: #353d47;font-size: 1.3em;"><?php echo $location->name;?></li>
                        <?php } ?>

                        <li class="dropdown profile">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $user->fname." ".$user->lname;?> <span class="caret"></span></a>
                            <ul class="dropdown-menu animated fadeInDown">

                                <li>
                                    <div class="profile-info">
                                        <h4 class="username"><?php echo $user->fname." ".$user->lname;?></h4>
                                        <p><?php echo $user->email;?></p>
                                        <div class="btn-group margin-bottom-2x" role="group">
                                            <button type="button" class="btn btn-default"><i class="fa fa-user"></i> Profile</button>
                                            <?php if($user->type != 3){?>
                                            <button onclick="window.location='<?php echo site_url('user/logout');?>'" type="button" class="btn btn-default"><i class="fa fa-sign-out"></i> Logout</button>
                                            <?php }else{ ?>
                                            <button onclick="window.location='<?php echo site_url('doctor/logout');?>'" type="button" class="btn btn-default"><i class="fa fa-sign-out"></i> Logout</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="side-menu sidebar-inverse">
                <nav class="navbar navbar-default" role="navigation">
                    <div class="side-menu-container">
                        <div class="navbar-header">
                            <?php if($this->session->userdata('dr_logged')){?>
                            <a class="navbar-brand" href="<?php echo site_url('doctor');?>">
                                <div class="icon fa fa-paper-plane"></div>
                                <div class="title">Doctor</div>
                            </a>
                            <?php }else{ ?>
                            <a class="navbar-brand" href="<?php echo site_url('/');?>">
                                <div class="icon fa fa-paper-plane"></div>
                                <div class="title">Admin</div>
                            </a>
                            <?php } ?>
                            <button type="button" class="navbar-expand-toggle pull-right visible-xs">
                                <i class="fa fa-times icon"></i>
                            </button>
                        </div>
                        <ul class="nav navbar-nav">
                            <?php if($user->type == '3'){?>
                            <li>
                                <a href="<?php echo site_url('doctor');?>">
                                    <span class="icon fa fa-tachometer"></span><span class="title">Patient Search</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if($user->type != '3'){?>
                            <li class="<?php if($class == 'home') echo 'active';?> panel panel-default dropdown">
                                <a data-toggle="collapse" href="#dropdown-dashbord">
                                    <span class="icon fa fa-tachometer"></span><span class="title">Dashboard</span>
                                </a>
                                <!-- Dropdown level 1 -->
                                <div id="dropdown-dashbord" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                            <li><a href="<?php echo site_url('home/stats');?>"># / $</a></li>
                                            <?php if($user->type == '1'){?><li><a href="<?php echo site_url('home/compare');?>">Compare</a></li><?php } ?>
                                            <li><a href="<?php echo site_url('home/inventory');?>">Inventory</a></li>
                                            <li><a href="<?php echo site_url('injmeds/view');?>">Inj/Meds Report</a></li>
                                            <li><a href="<?php echo site_url('home/queue');?>">Queue</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            <li class="<?php if($class == 'patient') echo 'active';?> panel panel-default dropdown">
                                <a data-toggle="collapse" href="#dropdown-table">
                                    <span class="icon fa fa-table"></span><span class="title">Patients</span>
                                </a>
                                <!-- Dropdown level 1 -->
                                <div id="dropdown-table" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                            <li><a href="<?php echo site_url('patient/add');?>">Add Patient</a>
                                            </li>
                                            <li><a href="<?php echo site_url('patient');?>">View All</a>
                                            </li>
                                            <li><a href="<?php echo site_url('patient/search');?>">Search Patient</a>
                                            </li>
                                            <li><a href="<?php echo site_url('appoint');?>">Appointments</a>
                                            </li>
                                            <?php if($user->type == '1'){?>
                                            <li><a href="<?php echo site_url('patient/freeze');?>">Freeze</a>
                                            </li> 
                                            <?php } ?>
                                            <li><a href="<?php echo site_url('appoint/patient');?>">Check Appts.</a>
                                            </li> 
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <?php if($user->type == '1'){?>
                            <li class="<?php if($class == 'product') echo 'active';?> panel panel-default dropdown">
                                <a data-toggle="collapse" href="#dropdown-form">
                                    <span class="icon fa fa-file-text-o"></span><span class="title">Products</span>
                                </a>
                                <!-- Dropdown level 1 -->
                                <div id="dropdown-form" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                            <li>
						<a href="<?php echo site_url('product/add');?>">Add Product</a>
						<a href="<?php echo site_url('product');?>">View Products</a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <?php } ?>
                            <!-- Dropdown-->
                            <li class="panel panel-default dropdown">
                                <a data-toggle="collapse" href="#component-example">
                                    <span class="icon fa fa-cubes"></span><span class="title">Prescriptions</span>
                                </a>
                                <!-- Dropdown level 1 -->
                                <div id="component-example" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                            <li><a href="<?php echo site_url('order/pending');?>">Orders to Prescriptions</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <!-- Dropdown-->
                            <?php if($user->type == '1'){?>
                            <li class="<?php if($class == 'user') echo 'active';?> panel panel-default dropdown">
                                <a data-toggle="collapse" href="#dropdown-example">
                                    <span class="icon fa fa-slack"></span><span class="title">Users</span>
                                </a>
                                <!-- Dropdown level 1 -->
                                <div id="dropdown-example" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                            <li><a href="<?php echo site_url('user/add');?>">Add User</a></li>
                                            <li><a href="<?php echo site_url('user');?>">View Users</a></li>
                                            <li><a href="<?php echo site_url('schedule');?>">Employee Schedule Weekly</a></li>
                                            <li><a href="<?php echo site_url('schedule/reportsn');?>">Employee Schedule Reports</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <?php } ?>
			    <!-- Dropdown-->
                            <?php if($user->type == '1'){?>
                            <li class="<?php if($class == 'location') echo 'active';?> panel panel-default dropdown">
                                <a data-toggle="collapse" href="#locations-menu">
                                    <span class="icon fa fa-location-arrow"></span><span class="title">Manage</span>
                                </a>
                                <!-- Dropdown level 1 -->
                                <div id="locations-menu" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                            <li><a href="<?php echo site_url('medications');?>">Current Medications</a></li>
                                            <li><a href="<?php echo site_url('category');?>">Product Categories</a></li>
                                            <li><a href="<?php echo site_url('ri');?>">Regular Inventory</a></li>
                                            <li><a href="<?php echo site_url('adz');?>">Mail Adz</a>
                                            <li><a href="<?php echo site_url('locations');?>">Locations</a>
                                            <li><a href="<?php echo site_url('logs/test');?>">HTML TEST</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <?php } ?>
                            <!-- Dropdown-->
                           
                            <li class="panel panel-default dropdown">
                                <a data-toggle="collapse" href="#report-icon">
                                    <span class="icon fa fa-bars"></span><span class="title">Reports</span>
                                </a>
                                <!-- Dropdown level 1 -->
                                <div id="report-icon" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                             <?php if($user->type == '1'){?>
                                            <li><a href="<?php echo site_url('logs');?>">Patients Log</a></li>
                                            <li><a href="<?php echo site_url('logs/activities');?>">User Activities</a></li>
                                            <?php } ?>
                                            <li><a href="<?php echo site_url('logs/patient');?>">Patient Prescription Log</a></li>
                                              <?php if($user->type == '1'){?>
                                            <li><a href="<?php echo site_url('logs/prescriptions');?>">Print Prescriptions</a></li>
                                            <li><a href="<?php echo site_url('order/removed');?>">Removed Orders</a></li>                                            
                                            <?php } ?>
                                            <li><a href="<?php echo site_url('reports');?>">Smart Reports</a></li>
                                            <li><a href="<?php echo site_url('logs/presno');?>">Prescription # Check</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                           
                            <!-- Dropdown-->
                            <li class="panel panel-default dropdown">
                                <a data-toggle="collapse" href="#dropdown-icon">
                                    <span class="icon fa fa-archive"></span><span class="title">Marketing</span>
                                </a>
                                <!-- Dropdown level 1 -->
                                <div id="dropdown-icon" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                            <li><a href="<?php echo site_url('appoint/freeb12');?>">Free B-12 Promo</a></li>
                                            <li><a href="<?php echo site_url('marketing/customList');?>">Custom Lists</a></li>
                                            <li><a href="<?php echo site_url('promos');?>">Promotions</a></li>
                                            <li><a href="<?php echo site_url('promos/general');?>">General Promotions</a></li>
                                            <li><a href="<?php echo site_url('mail/templates');?>">Mail Templates</a></li>
                                            <li><a href="<?php echo site_url('marketing/coupons');?>">Coupons</a></li>
                                            <li><a href="<?php echo site_url('mail/mailQueue');?>">Mail Queue</a></li>
                                             <?php if($user->type == '1'){?>
                                            <li><a href="<?php echo site_url('promos/queueMsg');?>">Queue Alerts</a></li>
                                             <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                </nav>
            </div>
            <!-- Main Content -->
            <div class="container-fluid">
                <div class="side-body">