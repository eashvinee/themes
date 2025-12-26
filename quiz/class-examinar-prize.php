<?php 


namespace Quiz;

class ExaminarPrize{
    private $error=[];

    public function __construct(){
        add_action("timesaction_examinar_prizecreate",[$this, 'create'] );
        add_action("timesaction_examinar_prizeedit",[$this, 'edit'] );
        add_action("timesaction_examinar_sendwinner",[$this, 'sendwinner'] );
    }
    public function sendwinner() {

        $data     = $_REQUEST;
        $prize_id = intval($data['id']);

        $emails   = get_post_meta($prize_id, 'emails', true);
        $emails   = explode("|", $emails);

        foreach ($emails as $email) {

            $email = trim($email);
            if (empty($email)) continue;

            $existing = get_comments([
                'post_id'   => $prize_id,
                'type'      => 'timesprize',
                'meta_query'=> [
                    [
                        'key'   => 'email',
                        'value' => $email,
                    ]
                ],
                'number' => 1
            ]);

            if (!empty($existing)) {
                // Comment already exists
                $comment_ID = $existing[0]->comment_ID;

            } else {

                // ➕ Create new comment
                $comment_data = [
                    'comment_post_ID'      => $prize_id,
                    'comment_author'       => 'System',
                    'comment_author_email' => get_bloginfo('admin_email'),
                    'comment_content'      => '',
                    'comment_type'         => 'timesprize',
                    'comment_approved'     => 1,
                ];

                $comment_ID = wp_insert_comment($comment_data);

                if (is_wp_error($comment_ID)) {
                    continue;
                }

                update_comment_meta($comment_ID, 'email', $email);
                update_comment_meta($comment_ID, 'prize_id', $prize_id);
            }

            // Get student info
            $student = get_user_by('email', $email);
            if ($student instanceof WP_User) {
                update_comment_meta($comment_ID, 'student_id', $student->ID);
                update_comment_meta($comment_ID, 'student_name', $student->display_name);
            }

            // Send mail only once
            $this->sendPrizFrom($email, $comment_ID, $prize_id);
        }

        update_post_meta($prize_id, 'sendwinners', 'all');

        wp_redirect(home_url('/my-account/?tab=prizes'));
        exit;
    }
    /*public function sendwinner(){
        $data=$_REQUEST;

        $prize_id=$data['id'];
        $emails=get_post_meta( $prize_id, 'emails', true );
        $emails =explode("|", $emails);
        $winners=get_post_meta( $prize_id, 'sendwinners', true);

        if($winners == 'all'){ 
          $this->resendWinnerForm($prize_id);
        }

        

        foreach($emails as $email):
            $args = array(
                'comment_post_ID'      => $prize_id,
                'comment_author'       => 'System', 
                'comment_author_email' => get_bloginfo( 'admin_email' ),
                'comment_author_url'   => '',
                'comment_content'      => '', 
                'comment_type'         => 'timesprize',   
                'comment_parent'       => 0,
                'comment_approved'     => 1,
                'comment_author_IP'     => '',//127.1.0.0',
            );

            $comment_ID = wp_insert_comment( wp_filter_comment( $args ) );

            if ( is_wp_error( $comment_ID ) ) {
                echo $comment_ID-> get_error_message();
            }else{
                update_comment_meta( $comment_ID, 'email', $email );
                update_comment_meta( $comment_ID, 'prize_id', $prize_id );
                $student = get_user_by('email', $email);

                if($student instanceof WP_User){
                    
                    update_comment_meta( $comment_ID, 'student_id', $student->ID );
                    update_comment_meta( $comment_ID, 'student_name', $student->display_name );
                }

                $this->sendPrizFrom($email, $comment_ID, $prize_id);
            }
        endforeach;
        
        update_post_meta( $prize_id, 'sendwinners', 'all');

        $link=home_url("/my-account/?tab=prizes");
        wp_redirect($link);
        die;
    }* /

    public function resendWinnerForm($prize_id){
        $args = array(
            'post_id'     => $prize_id,  
            'type'        => 'timesprize',  
            'status'      => 'all', 
            'fields'       => 'ids' 
        );

        $ids = get_comments( $args );
        if(!empty($ids))
        foreach ( $ids as $id ) {
            $result = wp_delete_comment( $id , true ); 
        }

    }*/
    public function sendPrizFrom($to, $comment_ID, $prize_id){

        $display_name=get_comment_meta( $comment_ID, 'student_name',true);

        $subject = 'Congratulations! You Won  ' .$position.' Prize! ';
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );
        $prize_title=get_the_title($prize_id);
        $message = "<h2>Hello {$display_name}</h2>
        <p>Thank You for particiate in Quiz {$prize_title}</p>
        <h3>Congratulations! You Won  {$position} Prize! </h3>
        <p>Fill the form and select your active prize: <a href='" . home_url('/?studprz='.$comment_ID) . "'>Click here</a></p>
        <p>Thank You</p>
        <p>Team Times Foundations</p>";

        wp_mail( $to, $subject, $message, $headers );

    }

    public function edit(){

        $data=$_POST;

        $title  = isset( $data['title'] ) ? sanitize_text_field( $data['title'] ) : '';
        $position = $data['position'];
        $claim_date = $data['claim_date'];
        $prizes = $data['prizes'];
        $emails = $data['emails'];


        $post_id= $data['prize_id'];



        $update_args = array(
            'ID'            => $post_id,
            'post_title'    => $title,
            'post_content'  => '', 
            'post_type'     => 'timesprize',       
        );


        $result = wp_update_post( $update_args, true );

        if ( is_wp_error( $result ) ) {
            return $result; // Return the WP_Error object
        } 

        update_post_meta( $post_id, 'position', $position );
        update_post_meta( $post_id, 'claim_date', $claim_date );
        update_post_meta( $post_id, 'prizes', $prizes );
        update_post_meta( $post_id, 'emails', $emails );
        update_post_meta( $post_id, '_author_id', get_current_user_id() );

        wp_redirect($_REQUEST['_wp_http_referer']);
        die;
    }
    public function create(){
        $data=$_REQUEST;

        $title  = isset( $data['title'] ) ? sanitize_text_field( $data['title'] ) : '';
        $position = $data['position'];
        $claim_date = $data['claim_date'];
        $prizes = $data['prizes'];
        $emails = $data['emails'];
        

        if ( empty( $title ) ) {
            $this->error['missing_title']='Prize title is required for insertion.';
        }

        // 2. Prepare Post Array for wp_insert_post()
        $post_args = array(
            'post_title'    => $title,
            'post_content'  => '', 
            'post_status'   => 'publish',   
            'post_type'     => 'timesprize', 
        );

        // 3. Insert the Post
        $post_id = wp_insert_post( $post_args );

        if ( is_wp_error( $post_id ) ) {
            $this->error['post_msg']=$post_id -> get_error_message();
        }

        // 4. Update Custom Meta Fields
        if ( $post_id ) {
            update_post_meta( $post_id, 'position', $position );
            update_post_meta( $post_id, 'claim_date', $claim_date );
            update_post_meta( $post_id, 'prizes', $prizes );
            update_post_meta( $post_id, 'emails', $emails );
            update_post_meta( $post_id, '_author_id', get_current_user_id() );
        }


        if(empty($this->error)){
            $link=home_url("/my-account/?tab=prize-edit&id=".$post_id);
            wp_redirect($link);
            die;
        }
    }

   

}