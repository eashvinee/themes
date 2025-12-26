<?php

if(empty($_GET['id'])){ echo "Wrong id"; return; }

$prize_id=$_GET['id'];

?>
<form method="post" action="" class="p-4 border rounded">
  <?php wp_nonce_field( 'quiz_prizeform_action', 'quiz_prizeform_nonce' ); ?>
  <input type="hidden" name="timesaction" value="examinar_prizeedit" />
  <input type="hidden" name="prize_id" value="<?php echo $prize_id; ?>" />

  <h2 class="h5 mb-0">Prize Edit</h2>
  <div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" name="title" class="form-control" value="<?php echo get_the_title($prize_id); ?>" placeholder="Enter prize title" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Position</label>
    <input type="text" name="position" class="form-control"  value="<?php echo get_post_meta($prize_id, 'position', true); ?>" placeholder="e.g. 1st, 2nd, 3rd" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Claim Final Date</label>
    <input type="date" name="claim_date" class="form-control" value="<?php echo get_post_meta($prize_id, 'claim_date', true); ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Prizes</label>
    <textarea 
        name="prizes" 
        class="form-control" 
        rows="3"
        placeholder="Example: Mobile Phone | Smart Watch | Gift Voucher"
        required><?php echo get_post_meta($prize_id, 'prizes', true); ?></textarea>
    <small class="text-muted">Separate each prize using | (pipe symbol)</small>
  </div>

  <div class="mb-3">
    <label class="form-label">Student Emails</label>
    <textarea 
        name="emails" 
        class="form-control" 
        rows="3"
        placeholder="example1@mail.com | example2@mail.com"
        required><?php echo get_post_meta($prize_id, 'emails', true); ?></textarea>
    <small class="text-muted">Separate emails using | (pipe symbol)</small>
  </div>

  <button type="submit" class="btn btn-primary">Save Prize</button>
   <a href="<?php echo home_url("my-account/?tab=prizes"); ?>" class="btn-link">Back</a>

</form>