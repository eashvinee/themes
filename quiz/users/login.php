<?php     global $times_error; 

if ( is_user_logged_in() ):
    ?><script> window.location.href='<?php echo home_url('/'); ?>'; </script><?php
    return;
endif; ?>
<form  method="post" id="loginForm" autocomplete="off">
  <?php wp_nonce_field( 'loginform_action', 'loginform_nonce' ); ?>
  <input type="hidden" name="timesaction" value="timeslogin" />

  <?php if(!empty($times_error)): ?>
  <div class="alert alert-danger" role="alert">
    <?php foreach($times_error as $error) echo $error.'<br/>'; ?>
  </div>
  <?php endif; ?>
  <div class="row g-3">
    <div class="col-12">
      <div class="form-floating">
        <input type="text" class="form-control border-0" placeholder="Enter Username" name="uname" required>
        <label for="uname">Username</label>
      </div>
    </div>
    <div class="col-12">
      <div class="form-floating">
        <input type="password" class="form-control border-0" placeholder="Enter Password" name="psw" required>
        <label for="psw">Password</label>
      </div>
    </div>
    <div class="col-12">
        
    <button type="submit"  class="btn-login-submit">Login</button>
    <label>
      <input type="checkbox" checked="checked" name="remember"> Remember me
    </label>
    </div>
    <div class="col-12">

    <!--button type="button" class="cancelbtn">Cancel</button-->
    <span class="psw"><a href="<?php echo wp_lostpassword_url(); ?>">Forgot password?</a> | <a href="<?php echo home_url('/my-account?type=register'); ?>">Register</a></span>

    </div>
  </div>
</form>
