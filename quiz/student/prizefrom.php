<?php 
    if(empty($_GET['studprz'])){  echo "Wrong Page"; die;}
    $winner_id=$_GET['studprz'];
    $prize_id=get_comment_meta( $winner_id, 'prize_id', true );
    $winner_email=get_comment_meta( $winner_id, 'email',true );
    
    $student_id=get_comment_meta( $winner_id, 'student_id', true );
    $student_name=get_comment_meta( $winner_id, 'student_name', true );
    $student_phone=get_comment_meta( $student_id, 'phone', true );
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prize Selection Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding-top: 50px; }
        .form-container { max-width: 600px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<?php
    $position=get_post_meta( $prize_id, 'position', true );
    $claim_date=get_post_meta( $prize_id, 'claim_date', true );
    $prizes=get_post_meta( $prize_id, 'prizes', true);

    if(!empty($prizes)){
        $prizes=explode("|", $prizes);
    }

?>

<div class="container form-container">
    <div class="text-center mb-5">
        <div class="display-4 text-success mb-2">🎉</div>
        <h1 class="fw-bold text-dark">Congratulations! You Won <?php echo $position; ?> Prize!</h1>
        <p class="lead text-muted">
            Your performance in the Quiz was outstanding. Out of all the participants, your score earned you the top spot on our leaderboard.
        </p>
        <div class="badge bg-warning text-dark p-2 px-3 fs-6 shadow-sm">
            Claim Till Date: <?php echo $claim_date; ?>
        </div>
    </div>

    <div class="alert alert-info border-0 shadow-sm mb-4">
        <strong>Kindly select your preferred prize</strong> from the options below and provide your contact details so we can arrange delivery.
    </div>
    <h2 class="mb-4 text-center">Select Your Prize</h2>
    
    <form id="prizeForm" method="post" class="needs-validation" novalidate>
        <input type="hidden" name="timesaction" value="studentPrizeForm" >
        <input type="hidden" name="winnerid" value="<?php echo $winner_id; ?>" >
        <input type="hidden" name="prizeid" value="<?php echo $prize_id; ?>" >
        <input type="hidden" name="winemail" value="<?php echo $winner_email; ?>" >
        <div class="mb-4">
            <label class="form-label d-block fw-bold">Available Prizes (Select at least one):</label>
           <?php foreach($prizes as $prize): 
                $inputid=sanitize_title($prize);
            ?> 
                <div class="form-check">
                    <input name="prize" class="form-check-input prize-checkbox" type="radio" value="<?php echo $prize; ?>" id="<?php echo $inputid; ?>">
                    <label class="form-check-label" border for="<?php echo $inputid; ?>"><?php echo $prize; ?></label>
                </div>
            <?php endforeach; ?>
            
            <div class="invalid-feedback" id="checkbox-error">
                Please select at least one prize before submitting.
            </div>
        </div>

        <hr>

        <div class="row g-3">
            <div class="col-12">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control disable" readonly="readonly" value="<?php echo $student_name; ?>">
                <div class="invalid-feedback">Please enter your name.</div>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control disable" readonly="readonly"  value="<?php echo $winner_email; ?>">
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control disable"  readonly="readonly"  value="<?php echo $student_phone; ?>">
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary w-100" type="submit">Submit Selection</button>
        </div>
    </form>
</div>

</body>
</html>