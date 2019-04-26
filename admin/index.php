<?php 
$inside_admin = true;
include '../core/inc/init.php';
include '../core/inc/admin_header.php';
if(!isset($_SESSION['admin_id'])){
    header('location: login.php');
    exit;
}

$members = $getFromA->getAll('customers');
$pending_orders = $getFromA->getAll('orders', 'where status = "pending"');
$payments = $getFromA->getAll('payments');
$total_earnings = 0;
foreach ($payments as $pay) {
    $total_earnings = $total_earnings + $pay->amount;
}
$total_products = $getFromA->getAll('products');
?>
<!-- BREADCRUMB-->
<section class="au-breadcrumb">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="au-breadcrumb-content">
                        <div class="au-breadcrumb-left">
                            <span class="au-breadcrumb-span">You are here:</span>
                            <ul class="list-unstyled list-inline au-breadcrumb__list">
                                <li class="list-inline-item active">
                                    <a href="#">Home</a>
                                </li>
                                <li class="list-inline-item seprate">
                                    <span>/</span>
                                </li>
                                <li class="list-inline-item">Dashboard</li>
                            </ul>
                        </div>
                        <a href='add_product.php' class="au-btn au-btn-icon au-btn--green">
                            <i class="zmdi zmdi-plus"></i>add item
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END BREADCRUMB-->

<!-- STATISTIC-->
<section class="statistic">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <div class="statistic__item">
                        <h2 class="number"><?php echo count($members)?></h2>
                        <span class="desc">Total members</span>
                        <div class="icon">
                            <i class="zmdi zmdi-account-o"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="statistic__item">
                        <h2 class="number"><?php echo count($pending_orders)?></h2>
                        <span class="desc">Pending orders</span>
                        <div class="icon">
                            <i class="zmdi zmdi-shopping-cart"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="statistic__item">
                        <h2 class="number"><?php echo '$'.$total_earnings?></h2>
                        <span class="desc">total earnings</span>
                        <div class="icon">
                            <i class="zmdi zmdi-money"></i>
                        </div>
                    </div>
                </div>
                 <div class="col-md-6 col-lg-6">
                    <div class="statistic__item">
                        <h2 class="number"><?php echo count($total_products)?></h2>
                        <span class="desc">total products</span>
                        <div class="icon">
                            <i class="zmdi zmdi-widgets"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END STATISTIC-->

<?php include('../core/inc/admin_footer.php'); ?>