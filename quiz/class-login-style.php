<?php 


namespace Quiz;

class LoginStyle{
    public function __construct(){
        add_action('login_head', [$this,'style']);
    }
    public function style(){
        ?>
        <style>
             body.login {background: #fff;color: #000;}
             body.login #login {width: 360px;padding: 20px;}
             body.login form {background: #f1f1f1;padding: 30px 25px;border-radius: 12px;border: 1px solid #f1f1f1;box-shadow: 0 8px 25px rgba(0,0,0,0.35);}
             body.login #login h1 a {background-image: url("https://toitctc.com/wp-content/uploads/2025/12/New-Project.jpg");background-size: contain;width: 200px;height: 70px;}
             body.login .input, body.login input[type="text"], 
             body.login input[type="password"] {border-radius: 8px;padding: 10px;font-size: 15px;}
             body.login .input:focus {border-color: #3d7dff;box-shadow: 0 0 0 1px #3d7dff;}
             body.login .button-primary {background: #3d7dff !important;border-color: #3d7dff !important;padding: 8px 0;font-size: 16px;border-radius: 8px;color: #fff;text-shadow: none !important;}
             body.login .button-primary:hover {background: #1f5ce0 !important;border-color: #1f5ce0 !important;}
             body.login #nav a,body.login #backtoblog a {color: #888 !important;}
             .privacy-policy-page-link{display: none;}
             .login #nav{display: none;}
        </style>
        <?php
    }
}