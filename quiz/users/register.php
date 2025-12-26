<?php global $times_error; ?>
<form method="post" id="registerForm" autocomplete="off">
<?php wp_nonce_field( 'registerform_action', 'registerform_nonce' ); ?>
<input type="hidden" name="timesaction" value="timesregister" />
  <?php if(!empty($times_error)): ?>
  <div class="alert alert-danger" role="alert">
    <?php foreach($times_error as $error) echo $error.'<br/>'; ?>
  </div>
  <?php endif; ?>
    <div class="row g-3">
        <div class="col-12">
            <div class="form-floating">
                <input type="text" name="student" <?php set_register_val('student');  ?> class="form-control border-0" placeholder="Student Name">
                <label for="gname">Name</label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <input type="text" name="class" <?php set_register_val('class');  ?> class="form-control border-0" placeholder="Class">
                <label>Class</label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <input type="text" name="school"  <?php set_register_val('school');  ?> class="form-control border-0" placeholder="School">
                <label for="gname">School</label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <input type="text" name="city"  <?php set_register_val('city');  ?> class="form-control border-0" placeholder="City">
                <label for="gname">City</label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <input type="text" name="state"  <?php set_register_val('state');  ?> class="form-control border-0" placeholder="State">
                <label for="gname">State</label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <input type="text" name="parent"  <?php set_register_val('parent');  ?> class="form-control border-0" placeholder="Parent's Name">
                <label for="gname">Parent's Name</label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <input type="text" name="phone"  <?php set_register_val('phone');  ?> class="form-control border-0" placeholder="Phone Number">
                <label for="gname">Phone Number</label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <input type="email" name="email"  <?php set_register_val('email');  ?> class="form-control border-0" id="cname" placeholder="Gurdian Email">
                <label for="cname">Gurdian Email</label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <input type="password" name="pass"   class="form-control border-0" id="pss" placeholder="Password">
                <label for="pss">Password</label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <input type="password" name="repass" class="form-control border-0" id="rpss" placeholder="Confirm Password">
                <label for="rpss">Confirm Password</label>
            </div>
        </div>
        <div class="col-12">
            <button  type="submit" class="btn-register-submit">Join Now</button>
        </div>
        <div class="col-12">
            <span class="psw"><a href="<?php echo wp_lostpassword_url(); ?>">Forgot password?</a> | <a href="<?php echo home_url('/login'); ?>">Login</a></span>
        </div>

    </div>

</form>
