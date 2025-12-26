<div class="container">
    <div class="card shadow-lg quiz-card">
        <div class="card-body p-4">
            <?php 
            $quiz_id=$_GET['id'];
            //$mcqid=times_get_quizmcq_id($quiz_id);

            
            $quiz=get_post($quiz_id);

            $start=get_post_meta($quiz_id,'_quiz_start_date', true);
            $end=get_post_meta($quiz_id,'_quiz_end_date', true);

            
            $forQuizMCQs=Quiz\QuizPanel::getMCQsForQuiz($quiz_id);

            ?>

            <h2 class="h4 text-primary mb-3">📋 <?php echo $quiz->post_title; ?></h2>
            <p class="lead" id="quizDescription"><?php echo $quiz->post_content; ?></p>
            <hr>
            <div class="row text-center mb-4">
                <div class="col-md-6 border-end">
                    <p class="fw-bold mb-0 text-success">Start Time</p>
                    <p class="h5" id="quizStartTime"><?php echo date('M j, Y', strtotime($start)); ?></p>
                </div>
                <div class="col-md-6">
                    <p class="fw-bold mb-0 text-danger">End Time</p>
                    <p class="h5" id="quizEndTime"><?php echo date('M j, Y', strtotime($end)); //M j, Y \a\t h:i A T ?></p>
                </div>
            </div>
            <?php if(!empty($forQuizMCQs)): ?>
            <hr>
            <div class="row text-center mb-4">
                <div class="col-md-6 border-end">
                    <p class="fw-bold mb-0 text-success">Today</p>
                    <p class="h5" id="quizStartTime"><?php echo date('M j, Y', time()); ?></p>
                </div>
                <div class="col-md-6">
                    <p class="fw-bold mb-0 text-danger">Number Of Questions</p>
                    <p class="h5" id="quizEndTime"><?php echo count($forQuizMCQs); ?></p>
                </div>
            </div>
            <hr>

            <h2 class="h4 text-primary mb-3">⚙️ Instructions & Rules</h2>
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item">Select only <strong>one</strong> option per question.</li>
                <li class="list-group-item">Once you submit an answer, you <strong>cannot</strong> go back.</li>
            </ul>

            <div class="d-grid gap-2">
                
                <form method="post">
                    <?php wp_nonce_field( 'studentquiz_action', 'studentquiz_nonce' ); ?>
                    <input type="hidden" name="redirect_to" value="<?php echo 'my-account/?tab=quizstart&next=quizmcq&id='.$quiz_id; ?>" />
                    <input type="hidden" name="studentquiz" value="participate" />
                    <button type="submit" class="btn btn-success btn-lg">✅ Begin Quiz Now</button>
                </form>
            
            </div>
            <?php else: ?>
            <div class="alert alert-warning" role="alert">
                You have already taken today’s quiz. There are no questions available for you right now.
            </div>
            <div class="d-grid gap-2">
                
                <a href="<?php echo home_url('/my-account'); ?>" class="btn  btn-lg">
                    Go My Account
                </a>
                <a href="<?php echo home_url('/my-account?tab=certificate&type=participant&id='.$quiz_id); ?>" class="btn btn-success btn-lg">
                    ✅ Download Certificate
                </a>

            </div>
                <?php endif; ?>

            
        </div>
    </div>
</div>