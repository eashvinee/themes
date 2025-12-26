<?php 


namespace Quiz;

class ExaminarMcqs{
    private $error=[];

    public function __construct(){
        add_action("timesaction_examinar_quizmcq",[$this, 'mcqs'] );
    }

    public function mcqs(){
        $mcq_data=$_POST;
        //print_r($mcq_data);

        $quiz_id=$mcq_data['quiz_id'];
        $mcqs= $this->updatemcqs($quiz_id);

        $mcqs=$mcq_data['mcq'];

        foreach($mcqs as $mcq):
            $data_args = array(
                'comment_post_ID'      => $quiz_id,
                'comment_author'       => 'System', // Can be the Examiner's name or 'System'
                'comment_author_email' => get_bloginfo( 'admin_email' ),
                'comment_author_url'   => '',
                'comment_content'      => $mcq['ques'], // The main Question text
                'comment_type'         => 'quizmcq',      // *** CRITICAL: The custom type identifier ***
                'comment_parent'       => 0,
                //'user_id'              => get_current_user_id(),
                //'comment_date'         => $time,
                //'comment_date_gmt'     => get_gmt_from_date( $time ),
                'comment_approved'     => 1,
                'comment_author_IP'     => '',//127.1.0.0',
            );

            // 3. Insert the Comment (MCQ Entry)
            $mcq_entry_id = wp_insert_comment( wp_filter_comment( $data_args ) );

            if ( is_wp_error( $mcq_entry_id ) ) {
                echo $mcq_entry_id-> get_error_message();
            }else{
                update_comment_meta( $mcq_entry_id, '_mcq_options', $mcq['op'] );
                update_comment_meta( $mcq_entry_id, '_mcq_ans', $mcq['ans'] );
                
            }
        endforeach;

        $link=home_url("/my-account/?tab=quiz-mcq&id=".$quiz_id);
        wp_redirect($link);
        die;

        //die;
    }
     public function updatemcqs($quiz_id){
        $args = array(
            'post_id'     => $quiz_id,  
            'type'        => 'quizmcq',  
            'status'      => 'all', 
            'fields'       => 'ids' 
        );

        $ids = get_comments( $args );
        if(!empty($ids))
        foreach ( $ids as $id ) {
            $result = wp_delete_comment( $id , true ); 
        }

    }

    public static function getQuestions($quiz_id){
         $args = array(
            'post_id'     => $quiz_id,  
            'type'        => 'quizmcq',  
            'status'      => 'approve',  
            'order'       => 'ASC',
        );

        $comments = get_comments( $args );

        $questions=self::schema($quiz_id);

        $days=count($questions);

        if(!empty($comments)){
            $key=0;
            foreach($comments as $comment){
        
                $options        = get_comment_meta($comment->comment_ID, '_mcq_options', true );
                $answer = get_comment_meta( $comment->comment_ID, '_mcq_ans', true );

                $quesDay=$key+1;
                $args= [
                    'mcqid'    => $comment->comment_ID,
                    'question'  => wp_kses_post( $comment->comment_content ),
                    'options'   => is_array( $options ) ? $options : [],
                    'answer'    =>  $answer,
                    'day'   => 'day'.$quesDay
                ];

                $questions[$key]=$args;

                //echo "$days == $key <br/>";
                if($days < $key){ break;}
                $key++;
            }
        }

        return $questions;

    }

    static function schema($quiz_id){
        $quiz_start =get_post_meta($quiz_id,'_quiz_start_date', true);
        $quiz_end   =get_post_meta($quiz_id,'_quiz_end_date', true);

        // Calculate total days passed (including today)
        $start = new \DateTime($quiz_start);
        $end = new \DateTime($quiz_end);
        $days = $start->diff($end)->days;

        $response=[];
        if($days>0){
            for($i=0; $i<=$days; $i++){
                $quesDay=$i+1;
                $response[]=[
                    'mcqid' =>'', 
                    'question' => '', 
                    'options' =>[
                        'a'=> '',
                        'b'=> '',
                        'c'=> '',
                        'd'=> '',
                    ],
                    'answer' =>'', 
                    'day' =>'day'.$quesDay	
                ];
            }
        }

        return $response;

    }
}