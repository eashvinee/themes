<div class="container">
    <div class="bg-light rounded">
        <div class="row g-0">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="h-100 d-flex flex-column justify-content-center p-5">
                    <h1 class="mb-4">Register Form</h1>

                    <form method="post" id="registerForm">
                    <?php wp_nonce_field( 'registerform_action', 'registerform_nonce' ); ?>
                    <input type="hidden" name="timesaction" value="timesregister" />
                    <div class="alert alert-danger" role="alert"></div>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" name="student" class="form-control border-0" id="gname" placeholder="Student Name">
                                    <label for="gname">Name</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="email" name="email" class="form-control border-0" id="cname" placeholder="Email">
                                    <label for="cname">Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="password" name="pass" class="form-control border-0" id="school" placeholder="Password">
                                    <label for="school">Password</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button  type="submit">Join Now</button>
                            </div>
                            <div class="col-12">
                                <!--button type="button" class="cancelbtn">Cancel</button-->
                                <span class="psw"><a href="#">Forgot password?</a> | <a href="<?php echo home_url('/my-account'); ?>">Login</a></span>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s" style="min-height: 400px;">
                <div class="position-relative h-100">
                    <img class="position-absolute w-100 h-100 rounded" src="<?php echo home_url('/wp-content/uploads/2025/12/appointment-1.jpg');  ?>" style="object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</div>
