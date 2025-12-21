<?php 
if(empty($_GET['id'])){ echo "Wrong id"; return; }

$quiz_id=$_GET['id'];

  // Arguments for WP_Comment_Query (used by get_comments)
    $args = array(
        'post_id'     => $quiz_id,  
        'type'        => 'quizmcq',  
        'status'      => 'approve',  
        //'orderby'     => 'comment_date_gmt', 
        'order'       => 'ASC',
        //'number'      => -1,      
        //'hierarchical' => false,
    );

    // Fetch the comments/MCQ entries
    $questions = get_comments( $args );

    $quiz_post=get_post($quiz_id);
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
                    
                    $mcqs= [];
                    foreach($questions as $ques): 
                       $mcq_id = $ques->comment_ID;
        
                        $options        = get_comment_meta( $mcq_id, '_mcq_options', true );
                        $answer = get_comment_meta( $mcq_id, '_mcq_ans', true );

                        $mcqs[] = [
                            'mcqid'    => $mcq_id,
                            'question'  => wp_kses_post( $ques->comment_content ),
                            'options'   => is_array( $options ) ? $options : [],
                            'answer'    =>  $answer ,
                            // Include other useful comment data
                            'date'      => $ques->comment_date,
                        ];

                     endforeach;

                     if(empty($mcqs)){
                        $mcqs[0]=[
                            'question' => '',
                            'options' => [
                                    'a' => '',
                                    'b' => '',
                                    'c' => '',
                                    'd' => ''
                            ],
                            'answer' => ''
                        ];
                     }
                     //echo "<pre>";
                     //print_r($mcqs);
                    //echo "</pre>";

                    $key=1;
                    foreach($mcqs as $mcq):
                     ?>
                    <div class="card question-card border-primary mb-3" data-question-id="<?php echo $key; ?>">
                        <?php /*if(!empty($mcq['mcqid'])): ?>
                        <input type="hidden" name="mcq[<?php echo $key; ?>][mcqid]" value="<?php echo $mcq['mcqid']; ?>" />
                        <?php endif;*/ ?>
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Question <?php echo $key; ?></span>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn <?php if($key == 1) echo 'd-none'; ?>">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                        <div class="card-body"> 
                            <div class="mb-3">
                                <label class="form-label">Question</label>
                                <input type="text" name="mcq[<?php echo $key; ?>][ques]" value="<?php echo $mcq['question']; ?>" class="form-control question-text" required>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Option (A)</label>
                                    <input type="text" name="mcq[<?php echo $key; ?>][op][a]" class="form-control option-text"  value="<?php echo $mcq['options']['a']; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Option (B)</label>
                                    <input type="text"  name="mcq[<?php echo $key; ?>][op][b]" class="form-control option-text"   value="<?php echo $mcq['options']['b']; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Option (C)</label>
                                    <input type="text"  name="mcq[<?php echo $key; ?>][op][c]" class="form-control option-text"   value="<?php echo $mcq['options']['c']; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Option (D)</label>
                                    <input type="text"  name="mcq[<?php echo $key; ?>][op][d]" class="form-control option-text"   value="<?php echo $mcq['options']['d']; ?>" required>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label fw-bold">Correct Answer</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="mcq[<?php echo $key; ?>][ans]" type="radio" value="a" <?php echo checked($mcq['answer'],'a'); ?> required>
                                        <label class="form-check-label" >Option (A)</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="mcq[<?php echo $key; ?>][ans]" type="radio" value="b"  <?php echo checked($mcq['answer'],'b'); ?>>
                                        <label class="form-check-label" >Option (B)</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="mcq[<?php echo $key; ?>][ans]" type="radio" value="c"  <?php echo checked($mcq['answer'],'c'); ?>>
                                        <label class="form-check-label">Option (C)</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="mcq[<?php echo $key; ?>][ans]" type="radio" value="d"  <?php echo checked($mcq['answer'],'d'); ?>>
                                        <label class="form-check-label">Option (D)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $key++; endforeach; ?>    
                </div>

                <div class="d-grid gap-2">
                    <button type="button" id="addQuestionBtn" class="btn btn-outline-success mt-3">
                        ➕ Add New Question
                    </button>
                </div>

                <hr class="my-4">
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        💾 Save 
                    </button>
                    <a href="<?php echo home_url("my-account/?tab=quiz"); ?>" class="btn-link">Back</a>
                </div>
            </form>   
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            // Function to generate the HTML for a new question card
            function generateQuestionHtml(questionId) {
                return `
                    <div class="card question-card border-primary mb-3" data-question-id="${questionId}">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Question ${questionId}</span>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label  class="form-label">Question</label>
                                <input type="text"  name="mcq[${questionId}][ques]" class="form-control question-text" required>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Option (A)</label>
                                    <input type="text" name="mcq[${questionId}][op][a]" class="form-control option-text" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Option (B)</label>
                                    <input type="text" name="mcq[${questionId}][op][b]" class="form-control option-text" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Option (C)</label>
                                    <input type="text" name="mcq[${questionId}][op][c]" class="form-control option-text" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Option (D)</label>
                                    <input type="text" name="mcq[${questionId}][op][d]" class="form-control option-text" required>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label fw-bold">Correct Answer</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="mcq[${questionId}][ans]" type="radio" value="a">
                                        <label class="form-check-label">Option (A)</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="mcq[${questionId}][ans]" type="radio"  value="b">
                                        <label class="form-check-label" >Option (B)</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="mcq[${questionId}][ans]" type="radio"  value="c">
                                        <label class="form-check-label" >Option (C)</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="mcq[${questionId}][ans]" type="radio"  value="d">
                                        <label class="form-check-label">Option (D)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Global variable to track the next question ID
            let questionCounter = <?php echo $key; ?>;

            // Function to re-index and update labels/buttons
            /*function updateQuestionIndexing() {
                $('#questionsContainer .question-card').each(function(index) {
                    const newId = index + 1;
                    const $card = $(this);
                    
                    // Update card title
                    $card.find('.card-header span').text(`Question ${newId}`);
                    
                    // Update Remove button visibility
                    // Hide remove button if only one question remains
                    const removeBtn = $card.find('.remove-question-btn');
                    if ($('#questionsContainer .question-card').length === 1) {
                        removeBtn.addClass('d-none');
                    } else {
                        removeBtn.removeClass('d-none');
                    }

                    // For real-world use, you would also update IDs/names of all inputs here
                    // to ensure correct form submission data (omitted for brevity in this simple example).
                });
            }*/

            // Initial call to hide the remove button on the first question
            //updateQuestionIndexing();

            // Event listener for adding a new question
            $('#addQuestionBtn').click(function() {
                const newQuestionHtml = generateQuestionHtml(questionCounter);
                $('#questionsContainer').append(newQuestionHtml);
                questionCounter++;

                //updateQuestionIndexing(); // Update visibility after adding
            });

            // Event listener for removing a question (using delegation)
            $('#questionsContainer').on('click', '.remove-question-btn', function() {
                if ($('#questionsContainer .question-card').length > 1) {
                    $(this).closest('.question-card').remove();
                    //updateQuestionIndexing(); // Re-index after removal
                }
            });

            // Submission handler (Optional: For demonstration)
           /* $('button[type="submit"]').click(function(e) {
                e.preventDefault();
                alert('Quiz data collected (Check console for structure)!');
                
                // Example of collecting data (Simplified structure)
                const quizDetails = {
                    name: $('#quizName').val(),
                    description: $('#quizDescription').val(),
                    start: $('#quizStart').val(),
                    end: $('#quizEnd').val(),
                    // image handling would be complex (omitted)
                };

                const quizQuestions = [];
                $('#questionsContainer .question-card').each(function(index) {
                    const qId = index + 1;
                    const questionData = {
                        question: $(`#question_text_${qId}`).val(),
                        options: [
                            $(`#option_1_${qId}`).val(),
                            $(`#option_2_${qId}`).val(),
                            $(`#option_3_${qId}`).val(),
                            $(`#option_4_${qId}`).val()
                        ],
                        correct_answer: $(`input[name='correct_answer_${qId}']:checked`).val()
                    };
                    quizQuestions.push(questionData);
                });
                
                console.log('Quiz Details:', quizDetails);
                console.log('Quiz Questions:', quizQuestions);
            });*/
        });
    </script>