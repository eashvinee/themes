<?php 


namespace Quiz;

class QuizPanel{
    public function __construct(){
        add_action('init', [$this, 'binding']);
    }
    public static function getMCQsForQuiz($quiz_id) {

        $allowed_mcqs =self::daysPassed($quiz_id);
        $user_id=get_current_user_id();


        $attempted_mcq_ids=self::getAttemptedMCQs($quiz_id);
        $attempted_count=count($attempted_mcq_ids);

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
    }
    public static function getAttemptedMCQs($quiz_id){
        $user_id=get_current_user_id();

        // Get attempted MCQs by participant
        $answers=get_post_meta($quiz_id, 'quiz_mcqresult_'.$user_id, true); 

        $mcqs=[];
        if(!empty($answers['choices'])){
            foreach($answers['choices'] as $id=>$val){
                $mcqs[]=$id;
            }
        }

        return $mcqs;
    }    
    public static function daysPassed($quiz_id){

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
        $start = new \DateTime($quiz_start_date);
        $current = new \DateTime($today);
        $daysPassed = $start->diff($current)->days + 1;

        // Total MCQs allowed till today
        return  $daysPassed;
    }

    public function binding(){

        if(isset($_POST['studentquiz'])){

            $data=$_REQUEST;

            //print_r($data);

            if($_POST['studentquiz'] == 'participate'){
                $this->assignParticipate($data);
            }
            if($_POST['studentquiz'] == 'quizmcq'){
                $this->setAttemptedMCQs($data);
            }

        }
    
    }

    public function assignParticipate($data){

        $quiz_id=$data['id'];

        $student_id=get_current_user_id();
        $participates=get_post_meta($quiz_id, 'quiz_participate_'.$student_id, true);

        //@reset value: attempted MCQs by participant
        //update_post_meta($quiz_id, 'quiz_mcqresult_'.$student_id, []);

        if(empty($participates)){
            $participate=[
                'student_id'=>$student_id,
                'title'=>get_the_title($quiz_id),
                'type'=> 'participate',
                'time'=> date('Y-m-d H:i:s', time()),
            ];
            update_post_meta($quiz_id, 'quiz_participate_'.$student_id, $participate);
        }


        $redirect_to=$data['redirect_to'];
        wp_redirect($redirect_to);
        die;
    }
    
    public function setAttemptedMCQs($data){
        $quiz_id=$data['id'];

        //print_r($data);
        if(isset($data['qz'])){
            $student_id=get_current_user_id();
            $previous=get_post_meta($quiz_id, 'quiz_mcqresult_'.$student_id, true);

            $result=(!empty($previous['result']))? $previous['result'] : 0;
            $choices=(!empty($previous['choices']))? $previous['choices'] : [];
            
            if(!empty($data['qz'])){
                foreach($data['qz'] as $id=>$choice_ans){
                    $correct_ans=get_comment_meta( $id, '_mcq_ans',true );
                    if($choice_ans == $correct_ans){
                        $result++;
                    }
                    $choices[$id]=[
                        'choice'=> $choice_ans,
                        'correct'=>$correct_ans,
                        'date'=> date('Y-m-d H:i:s', time()),
                    ];
                }

                $quiz=['result'=> $result, 'choices'=>$choices];
                update_post_meta($quiz_id, 'quiz_mcqresult_'.$student_id, $quiz);
            }            
        }

        $redirect_to=$data['redirect_to'];
        wp_redirect($redirect_to);
        die;
    }





}