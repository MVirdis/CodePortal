<div class="stats">
	<div>
	<?php
		
		$result = getProfilePicture();

		displayPicture($result);

	?>
	<br>
	<a id="change_button" style="color: rgb(0,0,238); font-size: 14px; cursor: pointer;">Change profile picture.</a><br>
	<span style="color: black; font-size: 12px;">or</span><br>
	<a id="delete_button" href="./utils/interactionDB.php?action=rm" style="color: rgb(0,0,238); font-size: 14px;">Remove profile picture.</a>
	<form id="change_form" class="hidden" action="./utils/uploadUtil.php" enctype="multipart/form-data" method="post">
		Select an Image to upload (format jpeg):
		<input type="file" name="fileToUpload">
		<input type="submit" name="submit" value="Submit">
	</form>
	<script>
		var a = document.getElementById('change_button');
		var change_form = document.getElementById('change_form');
		a.addEventListener("click", function (event) {
			change_form.style.display = "inline-block";
		});
	</script>
	</div>
	<br>
	<span>Your Statistics:</span>
	<div>
		Uploaded Codes: <span><?php echo getNumOfCodes(); ?></span><br>
		Number of Requests: <span><?php echo getNumOfRequests(); ?></span><br>
		Number of Comments: <span><?php echo getNumOfComments(); ?></span><br>
		Experience: <span><?php echo getExperience(); ?></span>
	</div>
</div>