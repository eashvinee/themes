<h2>Quiz <a href="<?php echo home_url("/my-account?tab=quiz-add-new"); ?>" class="btn">Add New</a></h2>
<style>

  </style>
<?php

// 1. Determine the current page number
    // Checks for 'paged' (standard archive/blog) or 'page' (static front page)
    $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
    $paged = ( get_query_var( 'page' ) ) ? absint( get_query_var( 'page' ) ) : $paged; // Prioritize 'page' if on a static front page

    $posts_per_page = 5; // Define how many posts per page

    $args = array(
        'post_type'      => 'timesquiz', // Can be any post type, e.g., 'quiz'
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,           // Crucial for pagination logic
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        // 'ignore_sticky_posts' => true, // Use this if you don't want sticky posts affecting the query
    );

    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ):
    ?>

<table class="table table-hover quiz-table">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Title</th>
      <th scope="col">Start</th>
      <th scope="col">End</th>
      <th scope="col">Status</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>

    <?php
    
      
        while ( $the_query->have_posts() ):
            $the_query->the_post();
            $post_id=get_the_ID();
            ?>
            <tr>
              <th scope="row"><?php echo $post_id; ?></th>
              <td><?php echo get_the_title(); ?></td>
              <td><?php echo get_post_meta($post_id, '_quiz_start_date', true); ?></td>
              <td><?php echo get_post_meta($post_id, '_quiz_end_date', true); ?></td>
              <td><?php echo get_post_meta($post_id, '_quiz_status', true); ?></td>
              <td>
                <a href="<?php echo home_url("/my-account?tab=quiz-edit&id=".$post_id); ?>" class="btn-link">Edit Quiz</a> / 
                <a href="<?php echo home_url("/my-account?tab=quiz-mcq&id=".$post_id); ?>" class="btn-link">View MCQ</a>
              </td>
            </tr>
            <?php 
        endwhile;

        

    // Restore original post data

?>
  </tbody>
  </table>
  <?php 
  // 2. Display Pagination Links
        $total_pages = $the_query->max_num_pages;

        if ( $total_pages > 1 ) {
            echo '<nav class="pagination text-center">';
            
            // Generate pagination links
            $paginate_args = array(
                'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                'format'    => '?paged=%#%',
                'current'   => max( 1, $paged ),
                'total'     => $total_pages,
                'prev_text' => '&#171; Previous',
                'next_text' => 'Next &#187;',
                'type'      => 'list', // Output as an unordered list
            );
            
            echo paginate_links( $paginate_args );
            
            echo '</nav>';
        }
            wp_reset_postdata();

      else:
        echo '<p>No posts found.</p>';
      endif;
  
  
