<?php 


namespace Quiz;

class ExaminarParticipants{

    public function __construct(){
        add_action("timesaction_examiner_participants_exportcsv", [$this,'exportCSV']);
    }

    public function exportCSV(){
        $quiz_id=$_REQUEST['id'];


        global $wpdb;
        $sql="SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$quiz_id AND meta_key LIKE 'quiz_mcqresult_%' ";

        $results = $wpdb->get_results( $sql );

        $response=[];
        if($results){
            foreach($results as $result){
                $data=maybe_unserialize($result->meta_value);
                
                $display_name ='';
                $user_id = str_replace('quiz_mcqresult_', '', $result->meta_key);
                $user = get_userdata( $user_id );

                if ( $user ) {
                    $display_name = $user->display_name;
                }

                $response[]=[
                    'UserId'=> $user_id,
                    'QuizId'=> $quiz_id,
                    'Student Name'=>$display_name,
                    'Class'=> get_user_meta($user_id, 'student_class', true),
                    'School Name'=>get_user_meta($user_id, 'student_school', true),
                    'City'=>get_user_meta($user_id, 'student_city', true),
                    'State'=>get_user_meta($user_id, 'student_state', true),
                    'Parent`s Name'=>get_user_meta($user_id, 'student_parent', true),
                    'Phone'=>get_user_meta($user_id, 'student_phone', true),
                    'Gurdian Email'=>$user->user_email,
                    'Result'=>$data['result'] ,
                ];
            }

        }



        

        $filename = "participant_list_". $quiz_id.'-'. date('Y-m-d') . ".csv";

        // Set headers to force download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Open a file pointer to the output stream
        $output = fopen('php://output', 'w');

        $flag=true;
        // Loop through the data and write each row to the CSV file
        foreach ($response as $row) {
            if($flag){ 
                $head=array_keys($row);
                fputcsv($output, $head);    
                $flag=false;
            }
            fputcsv($output, $row);
        }

        // Close the file pointer
        fclose($output);

        // Stop further script execution
        exit();
    }

    public static function getParticipants($quiz_id){

        global $wpdb;
        $sql="SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$quiz_id AND meta_key LIKE 'quiz_mcqresult_%' ";

        $results = $wpdb->get_results( $sql );

        $response=[];
        if($results){
            foreach($results as $result){
                $data=maybe_unserialize($result->meta_value);
                
                $display_name ='';
                $user_id = str_replace('quiz_mcqresult_', '', $result->meta_key);
                $user = get_userdata( $user_id );

                if ( $user ) {
                    $display_name = $user->display_name;
                }

                $response[]=[
                    'user_id'=> $user_id,
                    'user_name'=> $display_name,
                    'quiz_id'=> $quiz_id,
                    'user_id'=> $user_id,
                    'meta_key'=> $result->meta_key,
                    'result'=>$data['result'] ,
                ];
            }

        }

        return $response;

    }
    public static function getUserDetails($quiz_id, $user_id){

        $user = get_userdata( $user_id );
        
        if ( $user ) {
            $display_name = $user->display_name;
        }

        $results=get_post_meta($quiz_id, 'quiz_mcqresult_'.$user_id, true);
        $response=[];
        $response['user']=[
            'display_name'=> $display_name,
        ];
        $response['results']=$results;
        return $response;

    }    
}