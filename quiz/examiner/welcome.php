<?php
    $current_user = wp_get_current_user();

    $first_name = $current_user->user_firstname;
    $last_name  = $current_user->user_lastname;
?>
<div class="row">
    <div class="col-md-8">
        <h2>Welcome <?php echo $first_name.' '.$last_name; ?>,</h2>
        <p>You are logged in as a <b>quiz controller</b> on this dashboard. This is your dedicated space to manage everything related to quizzes. From here, you can easily create new quizzes and add multiple-choice questions with different answer options. The dashboard gives you full control to manage quizzes in a simple and organized way.</p>
        <p>You can also view the list of participants for each quiz and export the participant details whenever needed. This helps you keep proper records and review quiz performance without any hassle.</p>
        <p>If any quiz results in a winner certificate assigned to you, it will be available for download from the same dashboard. All quiz-related information, including history and certificates, is available in one place, so you do not need to switch between different pages.</p>
        <p>The dashboard is designed to be user-friendly and easy to understand, even for first-time users. Everything is clearly laid out so you can focus on managing quizzes instead of figuring out how the system works.</p>
        <p>In addition, you can set prizes for winners on a quiz-by-quiz basis. This allows you to decide rewards in advance and manage winner benefits smoothly.</p>
    </div>
    <div class="col-md-4">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <p class="card-text text-primary">Please select the quiz you want to make live. All published quizzes will automatically appear in the selection box.</p>
                <form class="row g-3" method="post">
                    <div class="col-auto">
                        <select class="form-select" name="timesquiz_live_box">
                            <option value="">Select Quiz For Live</option>
                            <?php $quizs=Quiz\ExaminarQuiz::liveQuizsBox(); 
                            //print_r($quizs);
                            foreach($quizs as $id=>$name):
                                $live_id=get_option('timesquiz_live_quiz', true);
                            ?>
                            <option value="<?php echo $id; ?>"  <?php echo selected($live_id, $id); ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary mb-3">Quiz Live</button>
                    </div>
                    <p class="help-text">Your selected quiz is now live. Participants can start taking it.</p>
                </form>
            </div>
        </div>
    </div>
</div>
