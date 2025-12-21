<?php

define( 'TIMES_TEMPLATE_URI', get_template_directory_uri().'/quiz' );
define( 'TIMES_TEMPLATE_DIR', get_template_directory().'/quiz' );


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


include_once TIMES_TEMPLATE_DIR . '/shortcode.php';
include_once TIMES_TEMPLATE_DIR . '/users.php';
include_once TIMES_TEMPLATE_DIR . '/examiner/quiz-submission.php';
include_once TIMES_TEMPLATE_DIR . '/class-student-quiz-control.php';
include_once TIMES_TEMPLATE_DIR . '/class-student-certificate-generate.php';
//include_once TIMES_TEMPLATE_DIR . '/student/quiz-join.php';

//form submission 
add_action('init', function(){
    if(isset($_REQUEST['timesaction'])){
        do_action('timesaction_'.$_REQUEST['timesaction']);
    }
});

function times_register_quiz_post_type() {
        $labels = array(
            'name'                  => 'Quizzes',
            'add_new'               => 'Add New',
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            //'rewrite'            => array( 'slug' => 'timesquiz' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-welcome-learn-more', // Nice icon for quizzes
            'supports'           => array( 'title' ), // , 'editor', 'thumbnail', 'custom-fields'
            'show_in_rest'       => false, // Enable for Gutenberg and future REST API access
        );

        register_post_type( 'timesquiz', $args );
}
add_action( 'init', 'times_register_quiz_post_type' ) ;


add_action("times_header_nav", function(){
    ?><div class="topnav"><?php
    if (is_user_logged_in()): ?>
        <a href="<?php echo home_url("/my-account"); ?>">My Account</a>  &nbsp;&nbsp;  
        <a href="<?php echo wp_logout_url(get_permalink()); ?>" class="btn btn-primary rounded-pill px-3">Logout<i class="fa fa-arrow-right ms-3"></i></a>
    <?php else: ?>    
        <a href="<?php echo home_url("/my-account"); ?>" class="btn btn-primary rounded-pill px-3">Login<i class="fa fa-arrow-right ms-3"></i></a>
    <?php endif; ?></div><?php  
});

add_filter('show_admin_bar', '__return_false');


function times_get_quizmcq_id($quiz_id){
    global $wpdb;

    $query="SELECT comment_ID, comment_content FROM $wpdb->comments 
            WHERE comment_type='quizmcq' AND comment_post_ID = %d AND comment_approved = '1'  
            ORDER BY RAND() LIMIT 5";

	return $wpdb->get_results($wpdb->prepare($query, $quiz_id));

}


function times_remove_admin_menus() {
    // The slug for the Comments menu item is 'edit-comments.php'
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'times_remove_admin_menus');
function times_disable_comment_feeds() {
    // Remove the link to the comment feed from the head section
    remove_action('wp_head', 'feed_links_extra', 3);
    
    // Remove the comment feed for all posts/pages
    remove_action('wp_head', 'post_comments_feed_link', 10, 1);
    
    // Redirect all comment feed requests to the main post feed
    // or a 404 page (404 is generally cleaner).
    if (is_comment_feed()) {
        wp_die( __('No comment feed available!'), '', array('response' => 404) );
    }
}
add_action('template_redirect', 'times_disable_comment_feeds');
