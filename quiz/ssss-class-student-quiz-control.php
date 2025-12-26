<?php 


class Student_Quiz_Control{

    public function __construct(){

        add_action('init', [$this, 'binding']);
    }
    public function binding(){

        if(isset($_POST['studentquiz'])){

            $data=$_REQUEST;

            //print_r($data);

            if($_POST['studentquiz'] == 'participate'){
                $this->participate($data);
            }
            if($_POST['studentquiz'] == 'quizmcq'){
                $this->quizmcq($data);
            }

        }
    
    }

    public function participate($data){

        $quiz_id=$data['id'];
        $this->assign_participate($quiz_id);

        $redirect_to=$data['redirect_to'];
        wp_redirect($redirect_to);
        die;
    }
    public function assign_participate($quiz_id){
        $student_id=get_current_user_id();
        $quiz_participates=get_post_meta($quiz_id, 'quiz_participate_'.$student_id, true);

        //@reset value: attempted MCQs by participant
        //update_post_meta($quiz_id, 'quiz_mcqresult_'.$student_id, []);

        if(empty($quiz_participates)){
            $participate=[
                'student_id'=>$student_id,
                'title'=>get_the_title($quiz_id),
                'type'=> 'participate',
                'time'=> date('Y-m-d H:i:s', time()),
            ];
            update_post_meta($quiz_id, 'quiz_participate_'.$student_id, $participate);
        }



    }
    public function quizmcq($data){
        $quiz_id=$data['id'];

        //print_r($data);
        if(isset($data['qz'])){
            $student_id=get_current_user_id();

            $previous=get_post_meta($quiz_id, 'quiz_mcqresult_'.$student_id, true);
            $quiz=$this->assign_mcqs($data['qz'], $previous);
            //print_r([$previous, $quiz]);
            update_post_meta($quiz_id, 'quiz_mcqresult_'.$student_id, $quiz);
        }

        $redirect_to=$data['redirect_to'];
        wp_redirect($redirect_to);
        die;
    }

    public function assign_mcqs($mcqs, $previous){

        $result=(!empty($previous['result']))? $previous['result'] : 0;
        $choices=(!empty($previous['choices']))? $previous['choices'] : [];

        
        if(!empty($mcqs)){
            //$choices=[];
            //$result=0;
            foreach($mcqs as $id=>$choice_ans){
                $correct_ans=get_comment_meta( $id, '_mcq_ans',true );
                if($choice_ans == $correct_ans){
                    $result++;
                }
                $choices[$id]=[
                    'choice'=> $choice_ans,
                    'correct'=>$correct_ans
                ];
            }

            return ['result'=> $result, 'choices'=>$choices];
        }
        return $previous;

    }

}

new Student_Quiz_Control();





function times_get_mcq_to_show($quiz_id) {

    $allowed_mcqs =times_get_mcq_day_passed($quiz_id);
    $user_id=get_current_user_id();

    // Get attempted MCQs by participant
    //$answer=get_post_meta($quiz_id, 'quiz_mcqresult_'.$user_id, true); //['result'=> $result, 'choices'=>$choices];
    //$attempted_mcqs=(!empty($answer['choices'])) ? $answer['choices'] : 0;

    $attempted_mcq_ids=times_get_attempted_mcqs($quiz_id);

    $attempted_count=count($attempted_mcq_ids);
    //return ['allowed'=>$allowed_mcqs, 'attempted'=>$attempted_mcqs];

    // MCQs to show now
    $mcq_to_show = $allowed_mcqs - $attempted_count;

    $mcq_to_show=max(0, $mcq_to_show);

    global $wpdb;

    $query="SELECT comment_ID, comment_content FROM $wpdb->comments 
            WHERE comment_type='quizmcq' AND comment_post_ID = {$quiz_id} AND comment_approved = '1'";

    if(!empty($attempted_mcq_ids)){
        $placeholders=implode(',',$attempted_mcq_ids);
        $query .=" AND comment_ID NOT IN ($placeholders) ";
    }

    $query .=" LIMIT $mcq_to_show"; // ORDER BY RAND() 

    
    return $wpdb->get_results($query);
	//return $wpdb->get_results($wpdb->prepare($query, $quiz_id));

    // Avoid negative values
    //return max(0, $mcq_to_show);
}

function times_get_attempted_mcqs($quiz_id){
    $user_id=get_current_user_id();

    // Get attempted MCQs by participant
    $answers=get_post_meta($quiz_id, 'quiz_mcqresult_'.$user_id, true); //['result'=> $result, 'choices'=>$choices];

    $mcqs=[];
    if(!empty($answers['choices'])){
        foreach($answers['choices'] as $id=>$val){
            $mcqs[]=$id;
        }
    }

    return $mcqs;
}

function times_get_mcq_day_passed($quiz_id){

    $quiz_start_date=get_post_meta($quiz_id,'_quiz_start_date', true);
    $quiz_end_date=get_post_meta($quiz_id,'_quiz_end_date', true);

    // Today's date
    $today = date('Y-m-d');

    // Stop if before quiz start
    if ($today < $quiz_start_date) {
        return 0;
    }

    // Use end date if today is after quiz end
    if ($today > $quiz_end_date) {
        $today = $quiz_end_date;
    }

    // Calculate total days passed (including today)
    $start = new DateTime($quiz_start_date);
    $current = new DateTime($today);
    $daysPassed = $start->diff($current)->days + 1;

    // Total MCQs allowed till today
   return  $daysPassed;


}