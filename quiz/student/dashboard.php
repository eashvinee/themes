<div class="container-fluid py-5">
    <div class="conatiner">
        <div class="row">
            <div class="col-md-3">
                <ul class="list-group"> 
                    <li class="list-group-item"> <a href="<?php echo home_url('my-account'); ?>">Dashboard</a> </li> 
                    <li class="list-group-item"> <a href="<?php echo home_url('my-account/?tab=certificates'); ?>">Certificates</a> </li> 
                    <li class="list-group-item">Prize</li> 
                    <li class="list-group-item">Profile</li> 
                    <li class="list-group-item"><a href="<?php echo wp_logout_url(home_url('my-account')); ?>">Logout<i class="fa fa-arrow-right ms-3"></i></a></li>
                </ul>
            </div>
            <div class="col-md-9">
                <?php do_action("times_student_dashboard"); ?>
            </div>
        </div>
    </div>
</div>