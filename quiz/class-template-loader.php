<?php 


namespace Quiz;

class TemplateLoader{
    public function __construct(){
        add_action('init', [$this,'timesAction']);

        add_action( 'template_redirect', [$this, 'quizPage']);

        add_action("times_dashboard", [$this, 'dashboard']);
        add_action("times_examinar_dashboard", [$this, 'examiner']);
        add_action("times_student_dashboard", [$this, 'student']);
        
        add_shortcode('times_login_form', [$this,'loginFormShortcode']);
        add_shortcode('times_regsiter_form', [$this,'registerFormShortcode']);
        add_shortcode('timesquiz', [$this,'timesquizBox']);

        add_action("wp_head", [$this, 'style']);

    }
    public function timesAction(){
        if(isset($_REQUEST['timesaction'])){
            do_action('timesaction_'.$_REQUEST['timesaction']);
        }
    }
    public function quizPage(){
        if ( ! is_user_logged_in() ) {
            if(is_page( 'quiz' ) || is_page( 'my-account' )){
                wp_redirect( home_url( '/home#joinnow' ) );
                exit();
            }

            
        }else{
            
            if(is_page( 'quiz' )){
                $user = wp_get_current_user();
                if ( ! empty( $user->roles ) && is_array( $user->roles ) ) {
                    if (!in_array( 'student', $user->roles ) ){
                        wp_redirect( home_url( '/home#joinnow' ) );
                        exit();
                    }
                }

            }
        }
    }
    public function examiner(){
        $type=(isset($_GET['tab'])) ? $_GET['tab'] : 'welcome';
        get_template_part('quiz/examiner/'.$type);    
    }
    public function student(){
        $type=(isset($_GET['tab'])) ? $_GET['tab'] : 'welcome';
        get_template_part('quiz/student/'.$type);    
    }
    public function dashboard(){
        $flag=false;
        if ( is_user_logged_in() ) {
            $user = wp_get_current_user();
            if ( is_array( $user->roles ) && ! empty( $user->roles ) ) {
                
                echo '<div class="times-quiz">';
                
                if ( in_array( 'examiner', $user->roles )) {
                    $flag=true;
                    get_template_part('quiz/examiner/dashboard');
                }
                
                if ( in_array( 'student', $user->roles ) ) {
                    $flag=true;
                    get_template_part('quiz/student/dashboard');
                }
                
                echo '</div>';
                    
            }
        }

        if($flag){ return; }

            ?><script> alert("Please login as student."); window.location.href="<?php echo home_url("/home#joinnow"); ?>"; </script><?php 
        
        /*else {
            if(isset($_REQUEST['type']) && $_REQUEST['type'] =='register'){
                get_template_part('quiz/users/register');
            }else{
                get_template_part('quiz/users/login');
            }
        }*/
    }
    public function registerFormShortcode(){
        ob_start();
        include TIMES_TEMPLATE_DIR . '/users/register.php';
        return ob_get_clean();
    }
    public function loginFormShortcode(){
        ob_start();
        include TIMES_TEMPLATE_DIR . '/users/login.php';
        return ob_get_clean();
    }
    //[timesquiz  id="291"]
    public function timesquizBox($atts, $content){
        $quiz_id= get_option('timesquiz_live_quiz');
        if(empty($quiz_id)){ return; }

        $start_date=get_post_meta( $quiz_id, '_quiz_start_date', true );
        $end_date=get_post_meta( $quiz_id, '_quiz_end_date', true );

               // Today's date
        $today = date('Y-m-d');

        // Stop if before quiz start
        if ($today < $start_date || $today > $end_date) {
            //echo "$today < $start_date || $today > $end_date";
            return ;
        }

        
        ob_start();
        include TIMES_TEMPLATE_DIR . '/users/timesquiz-box.php';
        return ob_get_clean();
    }
        public function style(){
        ?><style>
.page-id-14 .elementor-element-64ef507 .timeslive-quiz{ text-align: right;}
.page-id-14 .elementor-element-64ef507 .timeslive-quiz a{background-color: #F77D00;color: #FAFAFA;}
.page-id-14 .elementor-element-1fddb72 .elementor-button{ float:left; border-radius:10px;}
        </style><?php
    }

}