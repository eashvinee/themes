<?php
    $current_user = wp_get_current_user();

    $first_name = $current_user->user_firstname;
    $last_name  = $current_user->user_lastname;
?>
<h2>Welcome <?php echo $first_name.' '.$last_name; ?>,</h2>
<p>You are logged in as a <b>quiz participant</b> on this dashboard. This is your personal space where you can easily see all the quizzes you have joined. From here, you can also download your participation certificates for each quiz you have completed. If you have won any quiz, your winner certificate will also be available for download in the same place.</p>
<p>This dashboard is designed to be simple and easy to use, so you can quickly find everything related to your quizzes without any confusion. You do not need to go anywhere else to check your quiz history or certificates.</p>
<p>If you are selected as a winner, you will get an option to choose your prize directly from this dashboard. You can review the available prizes and select the one you like. Once you make your selection, the prize request will be sent for processing.</p>
<p>Please make sure to check your dashboard regularly for new quizzes, updates, certificates, and winner announcements. We wish you the very best and hope you enjoy participating in our quizzes.</p>
<p>Good luck and happy learning!</p>
  