<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
    $vote = $conn->query("SELECT * FROM voting_opt where id=".$_GET['id']);
    foreach ($vote->fetch_array() as $key => $value) {
        $$key = $value;  // Dynamically set variables like $opt_txt, $category_id, etc.
    }
}
?>
<div class="container-fluid">
    <div class="col-lg-12">
        <form action="" id="manage-opt">
            <input type="hidden" name="voting_id" value="<?php echo $_GET['vid'] ?>">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">
            
            <!-- Category Selection -->
            <div class="form-group">
                <label for="" class="control-label">Category</label>
                <select name="category_id" class="custom-select browser-default">
                    <?php 
                        $cats = $conn->query("SELECT * FROM category_list order by id asc");
                        while($row = $cats->fetch_assoc()):
                    ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($category_id) && $category_id == $row['id'] ? 'selected' :'' ?>>
                        <?php echo $row['category'] ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Candidate Name -->
            <div class="form-group">
                <label for="" class="control-label">Candidate Name</label>
                <input type="text" class="form-control" name="opt_txt" value="<?php echo isset($opt_txt) ? $opt_txt :'' ?>">
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label for="" class="control-label">Image</label>
                <input type="file" class="form-control" name="img" onchange="displayImg(this,$(this))">
            </div>
            <div class="form-group">
                <img src="<?php echo isset($image_path) ? 'assets/img/'.$image_path :'' ?>" alt="" id="cimg">
            </div>

            <!-- New Fields: Course and Year -->
            <div class="form-group">
                <label for="course" class="control-label">Course</label>
                <select name="course" class="custom-select browser-default">
                    <option value="BSIT" <?php echo isset($course) && $course == 'BSIT' ? 'selected' : '' ?>>Bacheloer of Science in Information Technology</option>
                    <option value="BSCRIM" <?php echo isset($course) && $course == 'BSCRIM' ? 'selected' : '' ?>>Bacheloer of Science in Criminology</option>
                    <option value="BSOA" <?php echo isset($course) && $course == 'BSOA' ? 'selected' : '' ?>>Bacheloer of Science in Office Administration</option>
                    <option value="BEED" <?php echo isset($course) && $course == 'BEED' ? 'selected' : '' ?>>Bacheloer of Science in Education</option>
                    <option value="POL-SCI" <?php echo isset($course) && $course == 'BA-COM' ? 'selected' : '' ?>>Bacheloer of Science in Political</option>
                </select>
            </div>

            <div class="form-group">
                <label for="year" class="control-label">Year</label>
                <select name="year" class="custom-select browser-default">
                    <option value="1st Year" <?php echo isset($year) && $year == '1st Year' ? 'selected' : '' ?>>1st Year</option>
                    <option value="2nd Year" <?php echo isset($year) && $year == '2nd Year' ? 'selected' : '' ?>>2nd Year</option>
                    <option value="3rd Year" <?php echo isset($year) && $year == '3rd Year' ? 'selected' : '' ?>>3rd Year</option>
                    <option value="4th Year" <?php echo isset($year) && $year == '4th Year' ? 'selected' : '' ?>>4th Year</option>
                </select>
            </div>
            <!-- Block Number Field -->
            <div class="form-group">
                <label for="block_no" class="control-label">Block No.</label>
                <select name="block_no" class="custom-select browser-default">
                    <option value="" disabled <?php echo !isset($block_no) ? 'selected' : ''; ?>>Select Block No.</option>
                    <?php for ($i = 1; $i <= 30; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo isset($block_no) && $block_no == $i ? 'selected' : ''; ?>>
                            <?php echo $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <label for="partylist">Partylist</label>
                <select name="partylist" class="custom-select browser-default" required>
                    <option value="" disabled selected>Select Partylist</option>
                    <option value="ASLE" <?php echo (isset($partylist) && $partylist == 'ASLE') ? 'selected' : ''; ?>>ASLE</option>
                    <option value="SVEA" <?php echo (isset($partylist) && $partylist == 'SVEA') ? 'selected' : ''; ?>>SVEA</option>
                </select>

                <!-- Position Field -->
<div class="form-group">
    <label for="positions" class="control-label">Categories</label>
    <select name="positions" id="positions" class="custom-select browser-default" required>
        <option value="" disabled <?php echo !isset($positions) ? 'selected' : ''; ?>>Select Category</option>
        <option value="National" <?php echo isset($positions) && $positions == 'President' ? 'selected' : ''; ?>>National</option>
        <option value="Provincial" <?php echo isset($positions) && $positions == 'Vice President' ? 'selected' : ''; ?>>Provincial</option>
        <option value="Municipal" <?php echo isset($positions) && $positions == 'Secretary' ? 'selected' : ''; ?>>Municipal</option>
    </select>
</div>

        </form>
    </div>
</div>

<style>
    img#cimg{
        max-height: 10vh;
        max-width: 6vw;
    }
</style>

<script>
    function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cimg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#manage-opt').submit(function(e){
    e.preventDefault();
    start_load();

    $.ajax({
        url: 'ajax.php?action=save_opt',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        error: function(err) {
            console.log(err);
        },
        success: function(resp) {
            if (resp == 1) {
                alert_toast('Data successfully saved.','success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else if (resp == 2) {
                alert_toast('Data successfully updated.','success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                alert_toast(resp, 'error');
            }
        }
    });
});
</script>
