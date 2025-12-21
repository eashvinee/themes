<?php 
    $quiz_id=$_GET['id'];
    //$mcqs=times_get_quizmcq_id($quiz_id);
    
    $current_user = wp_get_current_user();
    $student_id=$current_user->ID;

    $first_name = $current_user->user_firstname;
    $last_name  = $current_user->user_lastname;

    $key=1;

    $mcqs=times_get_mcq_to_show($quiz_id);

   if(!empty($mcqs)):
?>
<form method="post" > 
<?php wp_nonce_field( 'studentquiz_action', 'studentquiz_nonce' ); ?>
<input type="hidden" name="redirect_to" value="<?php echo 'my-account/?tab=quizstart&next=quizthankyou&id='.$quiz_id; ?>" />
<input type="hidden" name="studentquiz" value="quizmcq" />
<div class="container">
            <?php
            $participate=get_post_meta($quiz_id, 'quiz_participate_'.$student_id, true);
                /*echo "<pre>";
                echo $first_name . ' ' . $last_name;
                print_r($quiz_participate);
                echo "</pre>";*/

                

            ?>
    <?php foreach($mcqs as $mcq): 
       $options= get_comment_meta( $mcq->comment_ID, '_mcq_options',true );
                 
        ?>
    <div class="card shadow-lg quiz-card quizCard <?php if($key > 1 ) echo 'd-none';  ?> next-<?php echo $key; ?>" >
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Quiz Progress</h5>
            <span class="badge bg-warning text-dark h4">Question <?php echo $key; ?> of <?php echo count($mcqs); ?></span>
        </div>

        <div class="card-body p-4">
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Hello  <?php echo $current_user->display_name; ?></h4>
                <p>For today ( <?php echo date('M j, Y', time()); ?> ), you have <?php echo count($mcqs); ?> multiple-choice questions. Choose only one answer for each question.</p>
                <p>Please do not refresh the page or close the browser.</p>
                <hr>
                <p class="mb-0">Best of luck and enjoy the quiz! 😊</p>
            </div>
            <?php //print_r($mcq_count); ?>
            <div class="mb-4">
                <h3 class="text-primary mb-3">Question <?php echo $key; ?>:</h3>
                <p class="h4" ><?php echo $mcq->comment_content; ?></p>
            </div>

            <div class="list-group">
                <?php foreach($options as $opkey=>$oplabel): ?>
                <label class="list-group-item option-item">
                        <input class="form-check-input me-3 d-none" type="radio" name="qz[<?php echo $mcq->comment_ID; ?>]" value="<?php echo $opkey; ?>">
                        <span class="option-text"><span><?php echo $opkey; ?>)</span> <?php echo $oplabel; ?></span>
                        <span class="option-indicator"><i class="fa fa-check-circle text-success"></i></span>
                </label>
                <?php endforeach; ?>
            </div>

        </div>
        <div class="card-footer  p-3">
            <?php if($key < count($mcqs)): ?>
                <?php if($key > 1): ?>
                    <a  class="btn btnQuizCard btn-lg float-start href="javascript:;" data-next="next-<?php echo $key-1; ?>">
                        Prev Question <i class="bi bi-arrow-right-short"></i>
                    </a>
                <?php endif; ?>    
                <a  class="btn btnQuizCard btn-lg float-end" href="javascript:;" data-next="next-<?php echo $key+1; ?>">
                    Next Question <i class="bi bi-arrow-right-short"></i>
                </a>
            <?php else: ?>
                 <?php if($key > 1): ?>
                    <a  class="btn btnQuizCard btn-lg float-start" href="javascript:;" data-next="next-<?php echo $key-1; ?>">
                        Prev Question <i class="bi bi-arrow-right-short"></i>
                    </a>
                <?php endif; ?>
                <button type="submit" class="btn btn-info  btn-lg float-end ">
                    Submit Quiz <i class="bi bi-check-lg"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
    <?php $key++; endforeach; ?>
</div>
            </form>
<script>
    jQuery(document).ready( function($){
        var TimesExam={
            init: function(){
                this.binding();
            },
            binding: function(){
                const $this=TimesExam;
                $(document).on('click', '.option-item', $this.checkQuiz);
                $(".btnQuizCard").click($this.nextCard);
            },
            nextCard: function(event){
                event.preventDefault();
                let next=$(this).data('next');
                $(".quizCard").addClass('d-none');
                $("."+next).removeClass('d-none');
            },
            checkQuiz: function(event){
                const $this=TimesExam;
                const $selectedOption = $(this);
                $this.resetQuiz(this);
                $selectedOption.addClass('active');
                $selectedOption.find('input[type="radio"]').prop('checked', true);

            },
            resetQuiz: function(self){
                const quiz=$(self).parents('.quiz-card');
                quiz.find('.option-item').removeClass('active');
                quiz.find('.option-item').find('input[type="radio"]').prop('checked',false);
            }    
        };
        TimesExam.init();

    });
    </script>
    <?php 

else:

?>
 <div class="card shadow-lg quiz-card " >
        <!--div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Quiz Progress</h5>
            <span class="badge bg-warning text-dark h4">Assign Attempt done by you. wait for next quiz.</span>
        </div-->
        <div class="card-body p-4">
            <script> window.location.href="<?php echo home_url("my-account/"); ?>";</script> 
        </div>
</div>
<?php
endif;