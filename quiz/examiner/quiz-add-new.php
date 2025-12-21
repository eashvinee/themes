<h2>Quiz Add New</h2>
<div class="card shadow-sm mb-5">
    <div class="card-header bg-primary text-white">
        <h2 class="h5 mb-0">Quiz Details</h2>
    </div>
    <div class="card-body">
        <form id="quizDetailsForm" method="post" enctype='multipart/form-data'>
            <?php wp_nonce_field( 'quiz_createform_action', 'quiz_createform_nonce' ); ?>
            <input type="hidden" name="timesaction" value="examinar_quizcreate" />
            <div class="mb-3">
                <label for="quizName" class="form-label fw-bold">Quiz Name</label>
                <input type="text" name="title" class="form-control" id="quizName" placeholder="e.g., General Knowledge Test" required>
            </div>
            <div class="mb-3">
                <label for="quizDescription" class="form-label fw-bold">Quiz Description</label>
                <textarea  name="description" class="form-control" id="quizDescription" rows="3" placeholder="A brief summary of the quiz." required></textarea>
            </div>
            <?php /*
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="quizImage" class="form-label fw-bold">Quiz Image</label>
                    <input name="thumb" class="form-control" type="file"  accept="image/*">
                    <div class="form-text">Optional. quiz thumbnail.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="certimg" class="form-label fw-bold">Certificate Image</label>
                    <input name="certimg" class="form-control" type="file"  accept="image/*">
                    <div class="form-text">Optional. certificate image.</div>
                </div>
            </div>*/ ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="quizStart" class="form-label fw-bold">Quiz Start Date/Time</label>
                    <input name="start_date" type="date" class="form-control" id="quizStart" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="quizEnd" class="form-label fw-bold">Quiz End Date/Time</label>
                    <input name="end_date" type="date" class="form-control" id="quizEnd" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="quizStatus" class="form-label fw-bold">Status</label>
                <select class="form-control" name="status">
                    <option value="draft">Draft</option>
                    <option value="publish">Publish</option>
                </select>
                <div class="form-text">Select quiz status.</div>
            </div>
            
            <div class="mb-3">
                <button type="submit" class="btn btn-primary btn-lg">Save</button>
                <a href="<?php echo home_url("my-account/?tab=quiz"); ?>" class="btn-link">Back</a>

            </div>

        </form>
    </div>
</div>