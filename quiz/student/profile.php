<?php         
    $user=wp_get_current_user();
    $user_id=$user->ID;
?>
<form method="post" action="" class="p-4 border rounded">
  <?php wp_nonce_field( 'student_profileform_action', 'student_profileform_nonce' ); ?>
  <input type="hidden" name="timesaction" value="student_profileupdate" >
  <h2 class="h5 mb-0">Profile Edit</h2>
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="<?php echo $user->display_name; ?>" placeholder="Enter name" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Class</label>
    <input type="text" name="class" class="form-control"  value="<?php echo get_user_meta($user_id, 'student_class', true); ?>" placeholder="Enter class name" required>
  </div>
  <div class="mb-3">
    <label class="form-label">School Name</label>
    <input type="text" name="school" class="form-control" value="<?php echo get_user_meta($user_id, 'student_school', true); ?>"  placeholder="Enter school name"  required>
  </div>
  <div class="mb-3">
    <label class="form-label">City</label>
    <input type="text" name="city" class="form-control" value="<?php echo get_user_meta($user_id, 'student_city', true); ?>"  placeholder="Enter city name"  required>
  </div>
  <div class="mb-3">
    <label class="form-label">State</label>
    <input type="text" name="state" class="form-control" value="<?php echo get_user_meta($user_id, 'student_state', true); ?>"  placeholder="Enter state name"  required>
  </div>
  <div class="mb-3">
    <label class="form-label">Parent's Name</label>
    <input type="text" name="parent" class="form-control" value="<?php echo get_user_meta($user_id, 'student_parent', true); ?>"  placeholder="Enter parent name"  required>
  </div>
  <div class="mb-3">
    <label class="form-label">Phone Number</label>
    <input type="text" name="phone" class="form-control" value="<?php echo get_user_meta($user_id, 'student_phone', true); ?>"  placeholder="Enter phone number"  required>
  </div>
  <div class="mb-3">
    <label class="form-label">Gurdian Email</label>
    <input type="text"  class="form-control" readonly="readonly" value="<?php echo $user->user_email; ?>">
  </div>
  <button type="submit" class="btn btn-primary">Update Profile</button>

</form>
