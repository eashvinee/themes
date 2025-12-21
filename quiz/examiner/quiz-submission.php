<?php

class Quiz_Examinar_Submission{
    private $error=[];
    public function __construct(){
        add_action("timesaction_examinar_quizcreate",[$this, 'quizcreate'] );
        add_action("timesaction_examinar_quizedit",[$this, 'quizedit'] );
        add_action("timesaction_examinar_quizmcq",[$this, 'quizmcq'] );
    }

    public function quizmcq(){
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


    public function quizedit(){


        $quiz_data=$_POST;

        $quiz_id= $quiz_data['quiz_id'];

        // 1. Validation and Sanitization
        $title       = isset( $quiz_data['title'] ) ? sanitize_text_field( $quiz_data['title'] ) : '';
        $description = isset( $quiz_data['description'] ) ? wp_kses_post( $quiz_data['description'] ) : '';
        
       $thumb_id    = 0;
        if(!empty($_FILES['thumb']) && $_FILES['thumb']['size']> 0){
            $thumb_id    = $this->upload_media('thumb');
        }
        $certimg_id= 0;
        if(!empty($_FILES['certimg']) && $_FILES['certimg']['size']> 0){
            $certimg_id    = $this->upload_media('certimg');
        }



        $start_date  = isset( $quiz_data['start_date'] ) ? sanitize_text_field( $quiz_data['start_date'] ) : '';
        $end_date    = isset( $quiz_data['end_date'] ) ? sanitize_text_field( $quiz_data['end_date'] ) : '';
        $status      = isset( $quiz_data['status'] ) ? sanitize_text_field( $quiz_data['status'] ) : 'draft';


        $update_args = array(
            'ID'            => $quiz_id,
            'post_title'    => $title,
            'post_content'  => $description, // Maps to the Description/Body field
            //'post_status'   => 'publish',    // Publish the quiz immediately (can change to 'draft')
            'post_type'     => 'timesquiz',       // The slug of your custom post type
           // 'post_author'   => get_current_user_id(), // Assign to the current logged-in user
        );


        $result = wp_update_post( $update_args, true );

        if ( is_wp_error( $result ) ) {
            return $result; // Return the WP_Error object
        } 

        update_post_meta( $quiz_id, '_quiz_start_date', $start_date );
        update_post_meta( $quiz_id, '_quiz_end_date', $end_date );
        update_post_meta( $quiz_id, '_quiz_status', $status );
        update_post_meta( $post_id, '_quiz_thumb', $thumb_id );
        update_post_meta( $post_id, '_quiz_certimg', $certimg_id );

        wp_redirect($_REQUEST['_wp_http_referer']);
        die;
    }


    public function quizcreate(){
        //print_r($_REQUEST);
        $quiz_id=$this->insert_quiz_post( $_REQUEST );


        if(empty($this->error)){
            $link=home_url("/my-account/?tab=quiz-edit&id=".$quiz_id);
            wp_redirect($link);
            die;
            //wp_redirect($_REQUEST['_wp_http_referer']);
        }
    }
    function insert_quiz_post( $quiz_data ) {

      

        // 1. Validation and Sanitization
        $title       = isset( $quiz_data['title'] ) ? sanitize_text_field( $quiz_data['title'] ) : '';
        $description = isset( $quiz_data['description'] ) ? wp_kses_post( $quiz_data['description'] ) : '';
        
        $thumb_id    = 0;
        if(!empty($_FILES['thumb']) && $_FILES['thumb']['size']> 0){
            $thumb_id    = $this->upload_media('thumb');
        }
        $certimg_id= 0;
        if(!empty($_FILES['certimg']) && $_FILES['certimg']['size']> 0){
            $certimg_id    = $this->upload_media('certimg');
        }


        $start_date  = isset( $quiz_data['start_date'] ) ? sanitize_text_field( $quiz_data['start_date'] ) : '';
        $end_date    = isset( $quiz_data['end_date'] ) ? sanitize_text_field( $quiz_data['end_date'] ) : '';
        $status      = isset( $quiz_data['status'] ) ? sanitize_text_field( $quiz_data['status'] ) : 'draft';

        if ( empty( $title ) ) {
            $this->error['missing_title']='Quiz title is required for insertion.';
        }

        // 2. Prepare Post Array for wp_insert_post()
        $post_args = array(
            'post_title'    => $title,
            'post_content'  => $description, // Maps to the Description/Body field
            'post_status'   => 'publish',    // Publish the quiz immediately (can change to 'draft')
            'post_type'     => 'timesquiz',       // The slug of your custom post type
           // 'post_author'   => get_current_user_id(), // Assign to the current logged-in user
        );

        // 3. Insert the Post
        $post_id = wp_insert_post( $post_args );

        if ( is_wp_error( $post_id ) ) {
            $this->error['post_msg']=$post_id -> get_error_message();
        }

        // 4. Update Custom Meta Fields
        if ( $post_id ) {
            update_post_meta( $post_id, '_quiz_start_date', $start_date );
            update_post_meta( $post_id, '_quiz_end_date', $end_date );
            update_post_meta( $post_id, '_quiz_status', $status );
            update_post_meta( $post_id, '_quiz_thumb', $thumb_id );
            update_post_meta( $post_id, '_quiz_certimg', $certimg_id );

            update_post_meta( $post_id, '_quiz_author_id', get_current_user_id() );

            
            // Success
            return $post_id;
        }

    }

    function upload_media($file_name){
        $file_info = $_FILES[$file_name];
         if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }
        if ( ! function_exists( 'media_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
        }
        // Use media_handle_upload to move the file and create the attachment post
        $uploaded_file_id = media_handle_upload( $file_name, 0, array(), array(
            'test_form' => false, // We handle security via nonce above
            'test_upload' => false // Allow upload
        ) );

        if ( is_wp_error( $uploaded_file_id ) ) {
            $this->error['thumb_'.$file_name]=$uploaded_file_id->get_error_message();
        }

        $image_id = $uploaded_file_id;
    }

}

new Quiz_Examinar_Submission();