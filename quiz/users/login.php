

<div class="container">
    <div class="bg-light rounded">
        <div class="row g-0">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="h-100 d-flex flex-column justify-content-center p-5">
                  <h1 class="mb-4">Login Form</h1>
                  <form  method="post" id="loginForm">
                    <?php wp_nonce_field( 'loginform_action', 'loginform_nonce' ); ?>
                    <input type="hidden" name="timesaction" value="timeslogin" />
                    <!--div class="imgcontainer">
                      <img src="<?php echo TIMES_TEMPLATE_URI;  ?>/assets/img/img_avatar2.png" alt="Avatar" class="avatar">
                    </div-->
                    <div class="alert alert-danger" role="alert"></div>

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
                          
                      <button type="submit">Login</button>
                      <label>
                        <input type="checkbox" checked="checked" name="remember"> Remember me
                      </label>
                      </div>
                      <div class="col-12">

                      <!--button type="button" class="cancelbtn">Cancel</button-->
                      <span class="psw"><a href="#">Forgot password?</a> | <a href="<?php echo home_url('/my-account?type=register'); ?>">Register</a></span>

                      </div>
                    </div>
                  </form>
                </div>
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s" style="min-height: 400px;">
                <div class="position-relative h-100">
                    <img class="position-absolute w-100 h-100 rounded" src="<?php echo home_url('/wp-content/uploads/2025/12/appointment-1.jpg');  ?>" style="object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php /*
    <style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}
.mt-2 {
    margin-top: 1.1rem !important;
}
button {
margin-top:10px;
  background-color: #000;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 50%;
}

button:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #fe5d37;
}

.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
}

img.avatar {
  width: 40%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens * /
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style> */ ?>