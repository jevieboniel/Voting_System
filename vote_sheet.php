<?php include('db_connect.php'); ?>
<?php
	$voting = $conn->query("SELECT * FROM voting_list where is_default = 1 ");
	foreach ($voting->fetch_array() as $key => $value) {
		$$key = $value;
	}

	$vchk = $conn->query("SELECT DISTINCT(voting_id) FROM votes WHERE user_id = " . $_SESSION['login_id'])->num_rows;
	if ($vchk > 0) {
		header('Location: voting.php?page=view_vote');
	}

	$vote = $conn->query("SELECT * FROM voting_list WHERE id = " . $id);
	foreach ($vote->fetch_array() as $key => $value) {
		$$key = $value;
	}

	$opts = $conn->query("SELECT * FROM voting_opt WHERE voting_id = " . $id);
	$opt_arr = array();
	$set_arr = array();

	while ($row = $opts->fetch_assoc()) {
		$opt_arr[$row['category_id']][] = $row;
		$set_arr[$row['category_id']] = array('id' => '', 'max_selection' => 1);
	}

	$settings = $conn->query("SELECT * FROM voting_cat_settings WHERE voting_id = " . $id);
	while ($row = $settings->fetch_assoc()) {
		$set_arr[$row['category_id']] = $row;
	}
?>

<style>
	.candidate {
		margin: auto;
		width: 16vw;
		padding: 10px;
		cursor: pointer;
		border-radius: 3px;
		margin-bottom: 1em;
	}
	.candidate:hover {
		background-color: #80808030;
		box-shadow: 2.5px 3px #00000063;
	}
	.candidate img {
		height: 14vh;
		width: 8vw;
		margin: auto;
	}
	span.rem_btn {
		position: absolute;
		right: 0;
		top: -1em;
		z-index: 10;
		display: none;
	}
	span.rem_btn.active {
		display: block;
	}
	.candidate-details {
		font-size: 14px;
		margin-top: 5px;
	}
</style>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<form action="" id="manage-vote">
					<input type="hidden" name="voting_id" value="<?php echo $id ?>">
					<div class="col-lg-12">
						<div class="text-center">
							<h3><b><?php echo $title ?></b></h3>
							<small><b><?php echo $description; ?></b></small>
						</div>

						<?php
						$cats = $conn->query("SELECT * FROM category_list WHERE id IN (SELECT category_id FROM voting_opt WHERE voting_id = '$id')");
						while ($row = $cats->fetch_assoc()):
						?>
							<hr>
							<div class="row mb-4">
								<div class="col-md-12">
									<div class="text-center">
										<h3><b><?php echo $row['category'] ?></b></h3>
										<small>Max Selection: <b><?php echo $set_arr[$row['id']]['max_selection']; ?></b></small>
									</div>
								</div>
							</div>
							<div class="row mt-3">
								<?php foreach ($opt_arr[$row['id']] as $candidate) { ?>
									<div class="candidate" style="position: relative;" data-cid='<?php echo $row['id'] ?>' data-max="<?php echo $set_arr[$row['id']]['max_selection'] ?>" data-name="<?php echo $row['category'] ?>">
										<input type="checkbox" name="opt_id[<?php echo $row['id'] ?>][]" value="<?php echo $candidate['id'] ?>" style="display: none">
										<span class="rem_btn">
											<label class="btn btn-primary"><span class="fa fa-check"></span></label>
										</span>
										<div class="item" data-id="<?php echo $candidate['id'] ?>">
											<div style="display: flex; justify-content: center;">
												<img src="assets/img/<?php echo $candidate['image_path'] ?>" alt="">
											</div>
											<br>
											<div class="text-center">
												<large class="text-center"><b><?php echo ucwords($candidate['opt_txt']) ?></b></large>
												<div class="candidate-details">
													<p><b>Course:</b> <?php echo $candidate['course'] ?? 'N/A'; ?></p>
													<p><b>Year:</b> <?php echo $candidate['year'] ?? 'N/A'; ?></p>
													<p><b>Partylist:</b> <?php echo $candidate['partylist'] ?? 'Independent'; ?></p>
													<p><b>Block:</b> <?php echo $candidate['block_no'] ?? 'N/A'; ?></p>
													<large><p><b><?php echo $candidate['positions'] ?? 'N/A'; ?></b></p></large>
												</div>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						<?php endwhile; ?>
					</div>
					<hr>
					<button class="btn btn-block btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$('.candidate').click(function () {
		var chk = $(this).find('input[type="checkbox"]').prop("checked");

		if (chk == true) {
			$(this).find('input[type="checkbox"]').prop("checked", false);
		} else {
			var arr_chk = $("input[name='opt_id[" + $(this).attr('data-cid') + "][]']:checked").length;
			if ($(this).attr('data-max') == 1) {
				$("input[name='opt_id[" + $(this).attr('data-cid') + "][]']").prop("checked", false);
				$(this).find('input[type="checkbox"]').prop("checked", true);
			} else {
				if (arr_chk >= $(this).attr('data-max')) {
					alert_toast("Choose only " + $(this).attr('data-max') + " for " + $(this).attr('data-name') + " category", "warning");
					return false;
				}
			}
			$(this).find('input[type="checkbox"]').prop("checked", true);
		}

		$('.candidate').each(function () {
			if ($(this).find('input[type="checkbox"]').prop("checked") == true) {
				$(this).find('.rem_btn').addClass('active');
			} else {
				$(this).find('.rem_btn').removeClass('active');
			}
		});
	});

	// ✅ Validation before submission
	$('#manage-vote').submit(function (e) {
		e.preventDefault();

		let isValid = true;
		let checkedCategories = new Set();
		let requiredCategories = new Set();

		// Find required category IDs
		$('.candidate').each(function () {
			requiredCategories.add($(this).data('cid'));
		});

		// Find categories with at least one selection
		$('input[type="checkbox"]:checked').each(function () {
			let name = $(this).attr('name');
			let categoryId = name.match(/opt_id\[(\d+)\]/);
			if (categoryId && categoryId[1]) {
				checkedCategories.add(parseInt(categoryId[1]));
			}
		});

		// Compare sets
		requiredCategories.forEach(function (catId) {
			if (!checkedCategories.has(catId)) {
				isValid = false;
			}
		});

		if (!isValid) {
			alert_toast("Please select at least one candidate in each category before submitting.", "warning");
			return false;
		}

		// Submit via AJAX
		start_load();
		$.ajax({
			url: 'ajax.php?action=submit_vote',
			method: 'POST',
			data: $(this).serialize(),
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Vote successfully submitted");
					setTimeout(function () {
						location.reload();
					}, 1500);
				}
			}
		});
	});
</script>
