<?php 



if(empty($_GET['id'])){ echo "Wrong id"; return; }

$quiz_id=$_GET['id'];

$quiz_post=get_post($quiz_id);
$questions=Quiz\ExaminarMcqs::getQuestions($quiz_id);

?>
<div class="card shadow-sm">
    <div class="card-header">
        <h2 class="h5 mb-0">Quiz</h2>
    </div>
    <div class="card-body">
        <h3><?php echo $quiz_post->post_title; ?></h3>
        <p><?php echo $quiz_post->post_content; ?></p>
        <hr/>
        <ul>
            <li><stronge>Start Date:</stronge> <?php echo get_post_meta( $quiz_id, '_quiz_start_date', true ); ?></li>
            <li><stronge>End Date:</stronge> <?php echo get_post_meta( $quiz_id, '_quiz_end_date', true ); ?></li>
            <li><stronge>Status:</stronge> <?php echo get_post_meta( $quiz_id, '_quiz_status', true ); ?></li>
            <li><stronge>Number of Questions:</stronge> <strong><?php echo count($questions); ?></strong></li>
        </ul>
        <a href="<?php echo home_url("my-account/?tab=quiz-edit&id=".$quiz_id); ?>" class="btn-link">Quiz Edit</a>
    </div>
</div>    
<div class="card shadow-sm">
    <div class="card-header">
        <h2 class="h5 mb-0">Questions (MCQs)</h2>
    </div>
    <div class="card-body">
        <form id="quizDetailsForm" method="post" enctype='multipart/form-data'>
        <?php wp_nonce_field( 'quiz_mcqform_action', 'quiz_mcqform_nonce' ); ?>
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>" />
        <input type="hidden" name="timesaction" value="examinar_quizmcq" />
        <div id="questionsContainer">
            <?php 
            $key=1;
            foreach($questions as $question):
        
            ?>
            <div class="card question-card border-primary mb-3" data-question-id="<?php echo $key; ?>">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Question <?php echo $key; ?></span>
                </div>
                <div class="card-body"> 
                    <div class="mb-3">
                        <label class="form-label">Question</label>
                        <input type="text" name="mcq[<?php echo $key; ?>][ques]" value="<?php echo $question['question']; ?>" class="form-control question-text" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Option (A)</label>
                            <input type="text" name="mcq[<?php echo $key; ?>][op][a]" class="form-control option-text"  value="<?php echo $question['options']['a']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Option (B)</label>
                            <input type="text"  name="mcq[<?php echo $key; ?>][op][b]" class="form-control option-text"   value="<?php echo $question['options']['b']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Option (C)</label>
                            <input type="text"  name="mcq[<?php echo $key; ?>][op][c]" class="form-control option-text"   value="<?php echo $question['options']['c']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Option (D)</label>
                            <input type="text"  name="mcq[<?php echo $key; ?>][op][d]" class="form-control option-text"   value="<?php echo $question['options']['d']; ?>" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label fw-bold">Correct Answer</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="mcq[<?php echo $key; ?>][ans]" type="radio" value="a" <?php echo checked($question['answer'],'a'); ?> required>
                                <label class="form-check-label" >Option (A)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="mcq[<?php echo $key; ?>][ans]" type="radio" value="b"  <?php echo checked($question['answer'],'b'); ?>>
                                <label class="form-check-label" >Option (B)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="mcq[<?php echo $key; ?>][ans]" type="radio" value="c"  <?php echo checked($question['answer'],'c'); ?>>
                                <label class="form-check-label">Option (C)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="mcq[<?php echo $key; ?>][ans]" type="radio" value="d"  <?php echo checked($question['answer'],'d'); ?>>
                                <label class="form-check-label">Option (D)</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label  fw-bold">Day</label>
                        <input type="text" value="<?php echo $question['day']; ?>" class="form-control question-text" readonly="readonly" required>
                    </div>
                </div>
            </div>
            <?php $key++; endforeach; ?>    
        </div>

        
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                💾 Save 
            </button>
            <a href="<?php echo home_url("my-account/?tab=quiz"); ?>" class="btn-link">Back</a>
        </div>
    </form>   
    </div>
</div>

        