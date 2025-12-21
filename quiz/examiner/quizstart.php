<div class="quizstart-conatiner">
    <div class="quizstart-wrapper">

    <div class="container">
        <div class="card shadow-lg quiz-card">
            <div class="card-body p-4">
                <div class="error-container">
        
                    <i class="bi bi-lock-fill icon-lock"></i>
                    
                    
                    <h1 class="display-5 text-danger mb-3">Permission Denied</h1>
                    
                    <p class="lead mb-4">
                        You do not have the necessary authorization to view this page or resource.<br/> 
                        The page may require a student user role.
                    </p>
                    
                    <hr>

                    <h3 class="h5 mt-4 mb-3 text-secondary">What you can do next:</h3>

                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        
                        <a href="<?php echo home_url('/'); ?>" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-house-door-fill me-2"></i> Go to Homepage
                        </a>

                        <a href="<?php echo home_url('/my-account'); ?>" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-person-circle me-2"></i> My Account
                        </a>

                        <?php /*<a href="/contact" class="btn btn btn-outline-warning btn-lg">
                            <i class="bi bi-headset me-2"></i> Contact Support
                        </a> */?>
                    </div>
                    

                </div>
                
            </div>
        </div>
    </div>


    </div>
</div>
