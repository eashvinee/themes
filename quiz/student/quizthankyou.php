<div class="container">
    <div class="card shadow-lg quiz-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Thank You</h5>
        </div>
        <div class="card-body p-4">
            <?php 
            $quiz_id=$_GET['id'];
            // $mcqids=times_get_quizmcq_id($quiz_id);
             $student_id=get_current_user_id();
            $mcqs=get_post_meta($quiz_id, 'quiz_mcqresult_'.$student_id, true);
            /*echo "<pre>";
            print_r($mcqs);
            echo "</pre>";*/

            $start=get_post_meta($quiz_id,'_quiz_start_date', true);
            $end=get_post_meta($quiz_id,'_quiz_end_date', true);

            $today = date('Y-m-d');

            ?>
            <p>We appreciate your participation!</p>
            <?php if($today < $end): ?>
            <p>Get ready for your next quiz on <strong class="text-info"><?php echo date('Y-m-d',strtotime('+1 day')); ?></strong>.</p>
            <?php endif; ?>
            
            <div class="d-grid gap-2 d-md-block">
                <a href="<?php echo home_url('/my-account?tab=certificate&type=participant&id='.$quiz_id); ?>" class="btn btn-success btn-lg">
                        ✅ Download Certificate
                </a>
                <a class="btn  btn-lg" href="<?php echo home_url('/my-account/'); ?>"><i class="bi bi-house"></i> Return to Dashboard</a>
            </div>
        </div>
    </div>
</div>