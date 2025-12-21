<?php


//add roles
add_action( 'after_setup_theme', function(){

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

} );


 add_filter( 'login_redirect', function($redirect_to, $request, $user){

    if ( ! empty( $user->roles && is_array( $user->roles ) ) ) {
        
        // 1. Examiner Redirection: Redirect to the admin dashboard
        if ( in_array( 'examiner', $user->roles ) || in_array( 'student', $user->roles ) ) {
            return $redirect_to;
        }

        if ( in_array( 'administrator', $user->roles ) ) {
            return admin_url();
        }

        /*// 2. Student Redirection: Redirect only to the custom 'my-account' page
        if ( in_array( 'student', $user->roles ) ) {
            return home_url("/my-account" );
        }*/
        
    }


    return $redirect_to;

 }, 10, 3 );


 function times_ajax_login_handler() {

    if ( ! wp_verify_nonce( $_POST['loginform_nonce'], 'loginform_action' ) ){
        wp_send_json_error( array( 'message' => 'Security check failed. Please refresh and try again.' ) );
        wp_die();
    }

    //print_r($_POST);
    // Sanitize and get inputs
    $username = isset( $_POST['uname'] ) ? sanitize_user( $_POST['uname'] ) : '';
    $password = isset( $_POST['psw'] ) ? sanitize_text_field( $_POST['psw'] ) : '';

    if ( empty( $username ) || empty( $password ) ) {
        wp_send_json( array( 
            'status'=> 'failed', 
            'message' => 'Username/Email and Password are required.' 
        ) );
        wp_die();
    }

    // Try to sign the user in
    $creds = array(
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => isset( $_POST['remember'] ) ? true : false,
    );

    $user = wp_signon( $creds, false );



    if ( is_wp_error( $user ) ) {
        // Return the error message to the frontend
        print_r($user->get_error_message());
        /*wp_send_json( array(
            'status'=> 'failed', 
            'message' => 'Login failed: ' . $user->get_error_message() 
        ) );*/
    } else {
        // Successful login
        wp_clear_auth_cookie();
        do_action('wp_login', $user->ID);
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true);
        
        $redirect_to =  $_POST['_wp_http_referer'];
        // Use the 'login_redirect' filter to determine the final destination URL
        // (This will utilize the custom redirection logic defined in custom-roles-and-redirect.php)
        $redirect_url = apply_filters( 'login_redirect', $redirect_to, '', $user );

        wp_redirect($redirect_url);
        die;
        /*wp_send_json( array(
            'status'=> 'success',
            'message' => 'Login successful! Redirecting...',
            'redirect' => $redirect_url
        ) );*/
    }

    //wp_die();
}

// Hook the function to handle both logged-in and logged-out users
//add_action( 'wp_ajax_custom_user_login', 'custom_ajax_login_handler' );
//add_action( 'wp_ajax_nopriv_timeslogin', 'times_ajax_login_handler' );
add_action("timesaction_timeslogin", 'times_ajax_login_handler');





function times_register_user( $full_name, $email, $password, $role = 'student' ) {
    // 1. Sanitize and Validate Inputs
    $full_name = sanitize_text_field( $full_name );
    $email = sanitize_email( $email );
    $password = sanitize_text_field( $password ); // wp_create_user hashes this securely

    if ( ! is_email( $email ) ) {
        return new WP_Error( 'invalid_email', 'The provided email address is invalid.' );
    }

    if ( empty( $password ) || strlen( $password ) < 6 ) {
        return new WP_Error( 'invalid_password', 'Password must be at least 6 characters long.' );
    }

    // 2. Prepare Username (User Login)
    // Use the email prefix as a default username, or a sanitized version of the name.
    /*$username_part = explode( '@', $email );
    $username = sanitize_user( $username_part[0] . mt_rand(10, 99) ); // Add random number to avoid conflict
    
    // Fallback if email prefix is too short or problematic
    if ( strlen( $username) < 4 ) {
         $username = sanitize_user( strtolower( str_replace(' ', '', $full_name) . mt_rand(10, 99) ) );
    }

    // Ensure username is unique
    $i = 0;
    $original_username = $username;
    while ( username_exists( $username ) ) {
        $i++;
        $username = $original_username . $i;
    }*/

    $username=$email;

    // 3. Create the User
    $user_id = wp_create_user( $username, $password, $email );

    if ( is_wp_error( $user_id ) ) {
        // Return the WP_Error object if creation failed (e.g., email already exists)
        return $user_id;
    }

    // 4. Update User Meta (Name) and Role
    if ( $user_id ) {
        // Split the full name into first and last name
        $name_parts = explode( ' ', $full_name );
        $first_name = array_shift( $name_parts );
        $last_name = implode( ' ', $name_parts );

        // Update display name, first name, and last name
        wp_update_user( array(
            'ID'           => $user_id,
            'display_name' => $full_name,
            'first_name'   => $first_name,
            'last_name'    => $last_name,
            'role'         => $role // Assign the specified role (e.g., 'student' or 'examiner')
        ) );
    }


    return $user_id;
}


/**
 * Example Usage of the registration function (e.g., inside a form handler).
 */


function times_ajax_register_handler(){
    // Assuming data comes from a POST request
    $name =$_POST['student'];
    $email =$_POST['email'];
    $password =$_POST['pass'];

    $new_user_id_or_error = times_register_user( $name, $email, $password, 'student' );

    if ( is_wp_error( $new_user_id_or_error ) ) {
        // Registration failed
        $error_message = $new_user_id_or_error->get_error_message();
        // Handle error (e.g., display message to user)
        //echo "Registration Error: " . $error_message;
        print_r($error_message);
        /*wp_send_json( array(
            'status'=> 'failed',
            'message' => $error_message,
            //'redirect' => $redirect_url
        ) );*/

        
    } else {
        // Registration succeeded
         //echo "User registered successfully with ID: " . $new_user_id_or_error;
        
        // Optional: Log the user in immediately
        $user = get_user_by( 'id', $new_user_id_or_error );
        wp_set_current_user( $user->ID, $user->user_login );
        wp_set_auth_cookie( $user->ID );
        do_action( 'wp_login', $user->user_login, $user );


        $redirect_to =  $_POST['_wp_http_referer'];


        $redirect_url = apply_filters( 'login_redirect', $redirect_to, '', $user );
        wp_redirect($redirect_url);
        die;
        /*wp_send_json( array(
            'status'=> 'success',
            'message' => 'Register successful! Redirecting...',
            'redirect' => $redirect_url
        ) );*/
    }
}

add_action("timesaction_timesregister", 'times_ajax_register_handler');

//add_action( 'wp_ajax_nopriv_timesregister', 'times_ajax_register_handler' );




add_action("times_dashboard", function(){
    // Check if a user is logged in
    if ( is_user_logged_in() ) {
        // Get the current user object
        $user = wp_get_current_user();
        if ( is_array( $user->roles ) && ! empty( $user->roles ) ) {
                ?><div class="times-quiz"><?php
            // 1. Examiner Redirection: Redirect to the admin dashboard
            if ( in_array( 'examiner', $user->roles )) {
                get_template_part('quiz/examiner/dashboard');
                //return home_url("/examiner/my-account" );
            }elseif ( in_array( 'student', $user->roles ) ) {
                get_template_part('quiz/student/dashboard');
            }else{
                echo 'Your permission denied.';
            }
            ?></div><?php
                
        }else{
                    echo 'Your permission denied.';
        }
    }else {
        if(isset($_REQUEST['type']) && $_REQUEST['type'] =='register'){
            get_template_part('quiz/users/register');
        }else{
            get_template_part('quiz/users/login');
        }
    }
 });

 add_action("times_userauth_form", function(){
    $type=(isset($_GET['type'])) ? $_GET['type'] : 'login';
    get_template_part('quiz/users/'.$type);
 });    

add_action("times_examinar_dashboard", function(){
    $type=(isset($_GET['tab'])) ? $_GET['tab'] : 'welcome';
    get_template_part('quiz/examiner/'.$type);
 });    
 add_action("times_student_dashboard", function(){
    $type=(isset($_GET['tab'])) ? $_GET['tab'] : 'welcome';
    get_template_part('quiz/student/'.$type);
 });    