<?php 

add_filter('show_admin_bar', '__return_false');


function set_register_val($key){
    global $times_regsiter_data;

    if(!empty($times_regsiter_data[$key])){ 
        echo ' value="'.$times_regsiter_data[$key].'" '; 
    }
}


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


        $labels = array(
            'name'                  => 'Prizes',
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

        register_post_type( 'timesprize', $args );


}
add_action( 'init', 'times_register_quiz_post_type' ) ;




function times_remove_admin_menus() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'times_remove_admin_menus');

function times_disable_comment_feeds() {
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'post_comments_feed_link', 10, 1);
    if (is_comment_feed()) {
        wp_die( __('No comment feed available!'), '', array('response' => 404) );
    }
}
add_action('template_redirect', 'times_disable_comment_feeds');

