<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<style type="text/css">
		.animator {
			background-color:#f2c2c2;
		}
		.hov {
			border-radius:10px;
			background-color:#f2a2a2;
			transition:0.3s;
		}
		.hov:hover {
			transform:scale(1.2);
			filter:saturate(50%);
		}
	</style>
</head>
<body class="animator">
	<center>
		<div class="container p-4">
			<div class="row m-3 mt-6">
				<div class="col-sm-12">
					<center><h4 style="font-family:segoe ui;font-weight:bold">Select a Puzzle to Start</h4></center>
				</div>
			</div>
			<div class="row">
			<?php
				foreach (glob("./assets/img/*") as $file) {?>
					<div class="col-sm-4 p-4">
						<div class="card col-sm-12 hov" style="height:300px;box-shadow:0px 0px 5px #f2f2f2">
							<img onclick="location.href='./game.php?id=<?php echo $file; ?>'" src="<?php echo $file; ?>" style="width:100%;height:100%;cursor:pointer">
						</div>
					</div>
				<?php
				}
			?>
			</div>
		</div>
	</center>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>