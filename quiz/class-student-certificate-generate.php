<?php

class Student_Certificate_Generate{
    public function __construct(){
         //$this->participate();
         $this->binding();
    }
    public function binding(){
        if (is_user_logged_in()){
            if(!empty($_GET['id']) && ($_GET['type'] == 'participant')){
                $this->participate();
            }
        }
    }
    public function participate(){
        $quiz_id=$_GET['id'];
        

        $current_user = wp_get_current_user();
        $student_id=$current_user->ID;

        $student_id=get_current_user_id();
        $isParticipate=get_post_meta($quiz_id, 'quiz_participate_'.$student_id, true);

        $display_name=$current_user->display_name;

                // Participant name (dynamic)
        $args=[
            'name'=> $display_name,
            'bgImage'=> TIMES_TEMPLATE_DIR."/assets/certs/participation-202512.png",
            'fontPath'=> TIMES_TEMPLATE_DIR."/assets/certs/UbuntuMono-Italic.ttf",
            'yAxies'=>650,
            'certName'=>sanitize_title($display_name).'_Q'.$quiz_id.'T'.time(),
        ];

        $this-> generator($args);

    }

    public function generator($args){
        $name = $args['name'];

        // File paths
        $backgroundImage = $args['bgImage']; // certificate background
        $fontPath = $args['fontPath'];               // font file

        // Load image
        $image = imagecreatefrompng($backgroundImage);

        // Get image width
        $imageWidth = imagesx($image);

        // Text settings
        $fontSize = 30;
        $angle = 0;
        $textColor = imagecolorallocate($image, 0, 0, 0);

        // Get text bounding box
        $bbox = imagettfbbox($fontSize, $angle, $fontPath, $name);

        // Calculate text width
        $textWidth = abs($bbox[2] - $bbox[0]);

        // Center horizontally
        $x = ($imageWidth - $textWidth) / 2;

        // Fixed Y position
        $y = $args['yAxies'];

        // Add name to image
        imagettftext( $image, $fontSize,$angle,$x,$y,$textColor, $fontPath,$name);

        // Force download
        header("Content-Type: image/png");
        header("Content-Disposition: attachment; filename={$args['certName']}.png");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");

        // Output image
        imagepng($image);

        // Free memory
        imagedestroy($image);
        exit;
    }
}

add_action('init', function(){
    //tab=certificate&type=participant
    if( isset($_GET['tab']) && ($_GET['tab'] == 'certificate' ) && isset($_GET['type'])){
        new Student_Certificate_Generate();
    }
});
