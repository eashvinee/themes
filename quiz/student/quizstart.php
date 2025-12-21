<?php if(empty($_GET['id'])){ return;} ?>
<div class="quizstart-conatiner">
    <div class="quizstart-wrapper">
        <?php 
        
            $view=(isset($_GET['next'])) ? $_GET['next'] : 'quizrule';
            include TIMES_TEMPLATE_DIR . "/student/{$view}.php";
        
        ?>
    </div>
</div>


