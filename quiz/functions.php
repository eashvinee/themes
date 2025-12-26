<?php

define( 'TIMES_TEMPLATE_URI', get_template_directory_uri().'/quiz' );
define( 'TIMES_TEMPLATE_DIR', get_template_directory().'/quiz' );
/*
add_action('init', function(){
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
},1);*/

function times_enqueue_scripts() {

  wp_enqueue_style('bootstrap.min',TIMES_TEMPLATE_URI. '/assets/css/bootstrap.min.css',[],time(),'all');
  wp_enqueue_style('times.base',TIMES_TEMPLATE_URI. '/assets/css/style.css',[],time(),'all');

  wp_enqueue_script( 'bootstrap.bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js', ['jquery'], null, true );
  wp_enqueue_script( 'times.main', TIMES_TEMPLATE_URI. '/assets/js/main.js', [], time(), true );
  wp_localize_script('times.main', 'TIMES_OPTION', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce'    => wp_create_nonce('times_nonce'),
  ]);

}
add_action( 'wp_enqueue_scripts', 'times_enqueue_scripts' );

include_once TIMES_TEMPLATE_DIR . '/helpers.php';
//include_once TIMES_TEMPLATE_DIR . '/class-student-quiz-control.php';
include_once TIMES_TEMPLATE_DIR . '/class-student-certificate-generate.php';

include_once TIMES_TEMPLATE_DIR . '/class-template-loader.php';
new Quiz\TemplateLoader();

include_once TIMES_TEMPLATE_DIR . '/class-users-auth.php';
new Quiz\UsersAuth();

include_once TIMES_TEMPLATE_DIR . '/class-login-style.php';
new Quiz\LoginStyle();

include_once TIMES_TEMPLATE_DIR . '/class-examinar-quiz.php';
new Quiz\ExaminarQuiz();

include_once TIMES_TEMPLATE_DIR . '/class-examinar-mcqs.php';
new Quiz\ExaminarMcqs();

include_once TIMES_TEMPLATE_DIR . '/class-examinar-participants.php';
new Quiz\ExaminarParticipants();

include_once TIMES_TEMPLATE_DIR . '/class-quiz-panel.php';
new Quiz\QuizPanel();


include_once TIMES_TEMPLATE_DIR . '/class-examinar-prize.php';
new Quiz\ExaminarPrize();

include_once TIMES_TEMPLATE_DIR . '/class-student-prize.php';
new Quiz\StudentPrize();


add_filter('wp_mail_from_name', function($name) {
    return 'Times Foundation';
});
add_filter('wp_mail_from', function($email) {
    return 'no-reply@toitctc.com';
});