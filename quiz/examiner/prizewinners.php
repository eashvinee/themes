<?php 
if(empty($_GET['id'])){ echo "Wrong id"; return; }
$prize_id=$_GET['id']; 
?>
<h2>Winner List <a href="<?php echo home_url("/my-account?tab=prizes"); ?>" class="btn">Back</a></h2>
<h3>Prize Name: <?php echo get_the_title($prize_id); ?></h3>
<p>Here is the list of prize winners. Your can see the winners prize selection .</p>
<?php

  $args = array(
        'post_id'     => $prize_id,  
        'type'        => 'timesprize',  
        'status'      => 'approve',  
        'order'       => 'ASC',
    );

    $winners = get_comments( $args );

   // print_r($winners);
    if (!empty($winners)):
    ?>

<table class="table table-hover quiz-table">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Prize</th>
    </tr>
  </thead>
  <tbody>
<?php
foreach($winners as $winner):
  $comment_ID=$winner->comment_ID;
    ?>
    <tr>
      <td class="winner-<?php  echo $comment_ID;?>"><?php echo get_comment_meta( $comment_ID, 'email', true ); ?></td>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <?php 
endforeach;
?>
  </tbody>
  </table>
  <?php 
  else:
    echo '<p>No winners found.</p>';
  endif;
  
  
