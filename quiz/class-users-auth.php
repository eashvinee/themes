<?php 


namespace Quiz;

class UsersAuth{
    public function __construct(){
        add_filter( 'wp_nav_menu_items', [$this, 'loginoutLink'], 10, 2 );
        add_action("timesaction_timeslogin", [$this, 'loginAction']);
        add_action("timesaction_timesregister", [$this, 'registerAction']);
        add_filter( 'login_redirect', [$this, 'loginRedirect'], 10, 3);

        add_action( 'after_setup_theme',[$this,'addUserRole']);
    }
    public function loginRedirect($redirect_to, $request, $user){
        if ( ! empty( $user->roles && is_array( $user->roles ) ) ) {
                
            if ( in_array( 'examiner', $user->roles )) {
                return home_url('/my-account');
            }
            if (in_array( 'student', $user->roles ) ) {

                $quiz_id= get_option('timesquiz_live_quiz');
                if(empty($quiz_id)){ 
                    $redirect_to = home_url('/my-account');
                }else{
                    $redirect_to = home_url("/my-account?tab=quizstart&id=".$quiz_id);
                }


                //return home_url('/quiz');
            }
            if ( in_array( 'administrator', $user->roles ) ) {
                return admin_url();
            }
        }
        return $redirect_to;
    }

    public function loginoutLink($items, $args){
        if ( $args->theme_location == 'right-header-menu' ) {
            if (is_user_logged_in()) {
                $items .= '<li class="menu-item auth-link">';

                $user = wp_get_current_user();

                if ( ! empty( $user->roles ) && is_array( $user->roles ) ) {
                    if (in_array( 'student', $user->roles ) || in_array( 'examiner', $user->roles ) ){
                        $items .= '<a class="myaccount-link" href="'. home_url("/my-account") .'">My Account</a>';
                    }
                }
                $items .= '<a  class="logout-link " href="'. wp_logout_url(home_url()) .'">Log Out</a></li>';
            } else {
                $items .= '<li class="menu-item auth-link"><a  class="login-link" href="'. home_url('/login') .'">Log In</a></li>';
            }
        }
        return $items;
    }
    public function loginAction(){
        global $times_error;
        if ( ! wp_verify_nonce( $_POST['loginform_nonce'], 'loginform_action' ) ){
            $times_error['nonce']='Security check failed. Please refresh and try again.';
            return;
        }
        $username = isset( $_POST['uname'] ) ? sanitize_user( $_POST['uname'] ) : '';
        $password = isset( $_POST['psw'] ) ? sanitize_text_field( $_POST['psw'] ) : '';

        if ( empty( $username ) || empty( $password ) ) {
            $times_error['empty']='Username/Email and Password are required.';
            return;
        }

        // Try to sign the user in
        $creds = array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => isset( $_POST['remember'] ) ? true : false,
        );

        $user = wp_signon( $creds, false );

        if ( is_wp_error( $user ) ) {
            $times_error['login']=$user->get_error_message();
        } else {
            // Successful login
            wp_clear_auth_cookie();
            do_action('wp_login', $user->ID);
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, true);
            

            $redirect_to = home_url('/quiz');// $_POST['_wp_http_referer'];
            $redirect_url = apply_filters( 'login_redirect', $redirect_to, '', $user );

            wp_redirect($redirect_url);
            die;
        }
    }

    public function registerAction(){

        //registerform_nonce
        global $times_error;
        global $times_regsiter_data;
        
        $data=$_POST;

        $times_regsiter_data=$data;

        if ( ! wp_verify_nonce( $_POST['registerform_nonce'], 'registerform_action' ) ){
            $times_error['nonce']='Security check failed. Please refresh and try again.';
            return;
        }

        if($this->validate_create_student($data)){ return ;}


        $email=sanitize_email($data['email']);

        $username = $email;
        
        // 2. Generate Random Password
        $password =$data['pass'];//'password';// wp_generate_password( 8, true );

        // 3. Prepare User Data for wp_insert_user
        $userdata = array(
            'user_login' => $username,
            'user_pass'  => $password,
            'user_email' => $email,
            'role'       => 'student',
        );

        // 4. Create the User
        $user_id = wp_insert_user( $userdata );

        if ( is_wp_error( $user_id ) ) {
            $times_error['nonce']=$user_id->get_error_message();
            return;            
        }

        update_user_meta($user_id, 'student_class',$data['class']);
        update_user_meta($user_id, 'student_school',$data['school']);
        update_user_meta($user_id, 'student_city',$data['city']);
        update_user_meta($user_id, 'student_state',$data['state']);
        update_user_meta($user_id, 'student_parent',$data['parent']);
        update_user_meta($user_id, 'student_phone',$data['phone']);

        $this->send_welcome_mail($username, $password, $data);


        $user = get_user_by( 'id', $user_id );
        wp_set_current_user( $user_id, $user->user_login );
        wp_set_auth_cookie( $user_id );
        do_action( 'wp_login', $user->user_login, $user );

        $quiz_id= get_option('timesquiz_live_quiz');
        if(empty($quiz_id)){ 
            $redirect_to = home_url('/my-account');
        }else{
            $redirect_to = home_url("/my-account?tab=quizstart&id=".$quiz_id);
        }

        //$redirect_to = home_url('/quiz');//  $_POST['_wp_http_referer'];

        $redirect_url = apply_filters( 'login_redirect', $redirect_to, '', $user );
        wp_redirect($redirect_url);
        die;

    }
    function send_welcome_mail($username, $password, $data){
        
        $to      = $data['email'];
        $subject = 'Welcome to ' . get_bloginfo( 'name' );
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );
        
        $message = "
        <h2>Welcome, " . esc_html( $data['student'] ) . "!</h2>
        <p>Your account has been created for <strong>" . esc_html( $data['school'] ) . "</strong>.</p>
        <p><strong>Login Details:</strong><br>
        Username: $username<br>
        Password: $password</p>
        <p>You can login here: <a href='" . home_url('/') . "'>Login</a></p>
        <p>Thank You</p>
        <p>Team Times Foundations</p>";

        wp_mail( $to, $subject, $message, $headers );

    }
    function validate_create_student($data) {
        global $times_error;
        $required_fields = ['student',  'phone', 'email', 'pass', 'repass']; //'class', 'school', 'city', 'state', 'parent',

        foreach ( $required_fields as $field ) {
            if ( empty( trim( $data[$field] ) ) ) {
                $name= $field;
                if($field == 'pass') $name="Password";
                if($field == 'repass') $name="Confirm password";
                $times_error[$field]="The field '$name' is required.";
            }
        }

        if ( ! is_email( $data['email'] ) ) {
            $times_error['invalid_email']="Please enter a valid email address.";
        }

 
        if (!empty($data['pass']) && strlen($data['pass']) < 6) {
            $times_error['weak_password'] = "Password must be at least 6 characters long.";
        }

        if (!empty($data['pass']) && !empty($data['repass'])) {
            if ($data['pass'] !== $data['repass']) {
                $times_error['password_mismatch'] = "Password and Confirm Password do not match.";
            }
        }

        if ( username_exists( $data['email'] ) ) {
            $times_error['username_taken']="This email is already registered.";
        }

        if ( ! empty( $times_error ) ) {
            return true;
        }

        return false;

    }

    private function generate_student_username($student, $phone) {
        // 1. Combine fields (e.g., Student name + last 4 digits of phone)
        $phone_suffix = substr($phone, -4);
        $raw_username = $student . '_' . $phone_suffix;

        // 2. Sanitize for WordPress (removes spaces and special characters)
        $username = sanitize_user($raw_username, true);

        // 3. Ensure uniqueness (adds -1, -2 if username already exists)
        $final_username = $username;
        $counter = 1;
        while (username_exists($final_username)) {
            $final_username = $username . $counter;
            $counter++;
        }

        return $final_username;
    }
   
    public function addUserRole(){
         add_role('examiner','Examiner',
            array(
                    'read'                 => true,  
                    'level_0'              => true,  
                    'view_submissions'     => true,  
                    'edit_dashboard'       => true,  
                )
            );

        add_role('student','Student', 
                array(
                    'read'                 => true, 
                    'level_0'              => true,
                    'submit_exam'          => true, 
                )
            );
    }

}