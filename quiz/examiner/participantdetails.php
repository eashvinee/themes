<?php
if(empty($_GET['qid'])){ echo "Wrong id"; return; }
if(empty($_GET['uid'])){ echo "Wrong id"; return; }

$quiz_id=$_GET['qid'];
$user_id=$_GET['uid'];
//$quiz_post=get_post($quiz_id);
?>
<h2>Quiz ( <?php echo $quiz_id; ?> ) : <?php echo get_the_title($quiz_id); ///$quiz_post->post_title; ?> </h2>
<a class="mb-3" href="<?php echo home_url('/my-account/?tab=participantsview&id='.$quiz_id); ?>" class="btn-link">Back to Participants</a>

<?php

$participant=Quiz\ExaminarParticipants::getUserDetails($quiz_id, $user_id);

echo "<pre>";
print_r($participant);
echo "</pre>";
?>