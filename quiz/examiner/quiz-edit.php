<h2>Quiz Edit</h2>
<?php

if(empty($_GET['id'])){ echo "Wrong id"; return; }

$quiz_id=$_GET['id'];
$quiz_post=get_post($_GET['id']); 

?>
<div class="card shadow-sm mb-5">
    <div class="card-header">
        <h2 class="h5 mb-0">Quiz Details <a href="<?php echo home_url("my-account/?tab=quiz-mcq&id=".$quiz_id); ?>" class="btn-link">View MCQ</a></h2>
    </div>
    <div class="card-body">
        <form id="quizDetailsForm" method="post" enctype='multipart/form-data'>
            <?php wp_nonce_field( 'quiz_createform_action', 'quiz_createform_nonce' ); ?>
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>" />
            <input type="hidden" name="timesaction" value="examinar_quizedit" />
            <div class="mb-3">
                <label for="quizName" class="form-label fw-bold">Quiz Name</label>
                <input type="text" name="title" class="form-control" id="quizName" value="<?php echo $quiz_post->post_title; ?>" placeholder="e.g., General Knowledge Test" required>
            </div>
            <div class="mb-3">
                <label for="quizDescription" class="form-label fw-bold">Quiz Description</label>
                <textarea  name="description" class="form-control" id="quizDescription" rows="3" placeholder="A brief summary of the quiz." required><?php echo $quiz_post->post_content; ?></textarea>
            </div>
            <?php /*
             <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="quizImage" class="form-label fw-bold">Quiz Image</label>
                    <input name="thumb" class="form-control" type="file"  accept="image/*">
                    <?php 
                    $thumb= get_post_meta($quiz_id,'_quiz_thumb', true);
                    if($thumb>0):?>
                        <div class="form-text"> <a href="<?php echo wp_get_attachment_url($thumb); ?>" target="_blank">View Thumb</a></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="certimg" class="form-label fw-bold">Certificate Image</label>
                    <input name="certimg" class="form-control" type="file"  accept="image/*">
                    <?php 
                    $certimg= get_post_meta($quiz_id,'_quiz_certimg', true);
                    if($certimg>0):?>
                        <div class="form-text"> <a href="<?php echo wp_get_attachment_url($certimg); ?>" target="_blank">View Certificte</a></div>
                    <?php endif; ?>
                </div>
            </div> */ ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="quizStart" class="form-label fw-bold">Quiz Start Date/Time</label>
                    <input name="start_date" type="date" value="<?php echo get_post_meta($quiz_id,'_quiz_start_date', true); ?>" class="form-control" id="quizStart" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="quizEnd" class="form-label fw-bold">Quiz End Date/Time</label>
                    <input name="end_date" type="date" value="<?php echo get_post_meta($quiz_id,'_quiz_end_date', true); ?>" class="form-control" id="quizEnd" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="quizStatus" class="form-label fw-bold">Status</label>
                <?php $quiz_status= get_post_meta($quiz_id, '_quiz_status', true); ?>
                <select class="form-control" name="status">
                    <option value="draft"  <?php echo selected($quiz_status, 'draft'); ?>>Draft</option>
                    <option value="publish" <?php echo selected($quiz_status, 'publish'); ?>>Publish</option>
                </select>
                <div class="form-text">Select quiz status.</div>
            </div>
            
            <div class="mb-3">
                <button type="submit" class="btn btn-primary btn-lg">Save</button>
                <a href="<?php echo home_url("my-account/?tab=quiz"); ?>" class="btn-link">Back</a>

            </div>

        </form>
    </div>
</div>