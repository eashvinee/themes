<?php 


namespace Quiz;

class StudentPrize{
    public function __construct(){
        add_action('init', [$this, 'prizeForm']);
        add_action('timesaction_student_profileupdate', [$this, 'profileUpdate']);
        
    }
    public function profileUpdate(){
        $user=wp_get_current_user();
        $user_id=$user->ID;
        $data=$_REQUEST;

        update_user_meta($user_id, 'student_class',$data['class']);
        update_user_meta($user_id, 'student_school',$data['school']);
        update_user_meta($user_id, 'student_city',$data['city']);
        update_user_meta($user_id, 'student_state',$data['state']);
        update_user_meta($user_id, 'student_parent',$data['parent']);
        update_user_meta($user_id, 'student_phone',$data['phone']);

        wp_update_user( array( 'ID' => $user_id, 'display_name' => $data['name'] ) );
        wp_redirect($_REQUEST['_wp_http_referer']);
        die;

    }
    public function prizeForm(){
        if(isset($_GET['studprz'])){
            include TIMES_TEMPLATE_DIR.'/student/prizefrom.php';
            die;
        }
    }
}