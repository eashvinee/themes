<?php 


//[timesquiz  id="291"]

add_shortcode('timesquiz', 'timesquiz_shortcode');
function timesquiz_shortcode($atts, $content){

    if(empty($atts['id'])){ return; }
    $quiz_id=$atts['id'];

    //$quizview=(isset($_POST['timesquiz'])) ? $_POST['timesquiz'] :'view';
    ob_start();
    ?><div class='timesquiz-box'><?php
    
        $quiz=get_post($quiz_id);
        $status=get_post_meta( $quiz_id, '_quiz_status', true );
        if($status == 'publish'):
            $start=get_post_meta( $quiz_id, '_quiz_start_date', true );
            $end=get_post_meta( $quiz_id, '_quiz_end_date', true );
            ?>
            <div class="card mb-3" >
                <div class="row g-0">
                    <div class="col-md-4">
                        <svg aria-label="Placeholder: Image cap" class="bd-placeholder-img card-img-top" height="100%" preserveAspectRatio="xMidYMid slice" role="img" width="100%" xmlns="http://www.w3.org/2000/svg"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="50%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text></svg>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $quiz->post_title; ?></h5>
                            <p class="card-text"><?php echo $quiz->post_content; ?></p> 
                            <p class="card-text">
                                <small class="text-body-secondary"><?php echo date('M j, Y', strtotime($start)); ?></small> <span>to</span>
                                <small class="text-body-secondary"><?php echo date('M j, Y', strtotime($end)); ?></small>
                            </p>
                            <a href="<?php echo home_url("/my-account?tab=quizstart&id=".$quiz_id); ?>"  class="btn btn-primary">Join Quiz</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        endif;


    ?></div><?php
    return ob_get_clean();
}

