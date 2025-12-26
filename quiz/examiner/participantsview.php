<?php
if(empty($_GET['id'])){ echo "Wrong id"; return; }

$quiz_id=$_GET['id'];

//$quiz_post=get_post($quiz_id);
?>
<h2>Quiz ( <?php echo $quiz_id; ?> ) : <?php echo get_the_title($quiz_id); ///$quiz_post->post_title; ?> </h2>
<?php

$participants=Quiz\ExaminarParticipants::getParticipants($quiz_id);

?>
<a class="mb-3 btn-link" href="<?php echo home_url('/my-account/?tab=participants'); ?>">Back to List</a>
<a class="mb-3 float-end btn" href="<?php echo home_url('/my-account/?tab=participants&timesaction=examiner_participants_exportcsv&id='.$quiz_id); ?>">Export CSV</a>

<table class="table table-hover quiz-table">
    <thead>
        <tr>
            <th scope="col">Participant</th>
            <th scope="col">Result</th>
            <!--th scope="col">View</th-->
        </tr>
    </thead>
    <tbody>
        <?php foreach($participants as $participant):  ?>
            <tr class="user-<?php  echo $participant['user_id'];?>">
              <td><?php echo $participant['user_name']; ?></td>
              <td><?php echo $participant['result']; ?></td>
              <!--td>
                <a href="<?php //echo home_url("/my-account?tab=participantdetails&qid={$participant['quiz_id']}&uid=".$participant['user_id']); ?>" class="btn-link">View</a>  
              </td-->
            </tr>
        <?php  endforeach; ?>
  </tbody>
  </table>