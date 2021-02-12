<?php
	if (isset($_GET["id"])) {
		$img = $_GET["id"];
	}
	else {
		die;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="user-scalable=1.0,width=device-width,initial-scale=0.8"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
	<style type="text/css">
		body {
			margin:0px;
			font-family: 'Ubuntu', sans-serif!important;
			background-color:purple
		}
		::moz-selection {
			background:transparent;
		}
		::selection {
		  	background:transparent;
		}
		#preloader {
			position:fixed;
			z-index:100;
			background-image:linear-gradient(45deg, purple, cornflowerblue);
			top:0px;
			left:0px;
			width:100vw;
			height:100vh;
			display:table;
		}
		.white-line {
			margin-top:2px;
			height:3px;
			width:40px;
			border-radius:3px;
			background-color:gray
		}
		.load-icon {
			position:relative;
			top:calc(50vh - 20px);
			color:white;
		}
		.board {
			max-width:100%;
			box-shadow:0px 0px 10px steelblue;
			box-sizing:border-box;
    		background-color:steelblue;
    		border-radius:5px;
    		border:8px solid steelblue;
    		border-bottom:12px solid steelblue
		}
		.cells {
			background-color:#333333;
			padding:0px;
			margin:0px;
			cursor:pointer;
			box-sizing:border-box;
		}
		#source {
			width:450px;
			display:inline-flex;
			height:450px;
		}
		.hov {
			border:1px solid lightgray;
			position:relative;
			box-sizing:border-box;
			box-shadow:inset 0px 0px 20px lightgray;
			border-radius:2px;
			cursor:pointer!important;
		}
		.hov:hover {
			filter:brightness(80%);
		}
		.preview {
			height:60px;
			width:60px;
			margin-left:10px;
			border:2px solid cornflowerblue;
			border-radius:4px;
			cursor:pointer;
		}
		.preview:active {
			transform:scale(4);
			position:fixed;
			top:120px;
			left:120px;
			z-index:200
		}
		#stopwatch {
			background-color:#2a2b2d;
			border-radius:10px;
			color:white;
			font-weight:bold;
			width:200px;
			font-size:20px;
			max-width:95%;
			padding:10px;
			border:4px solid gray;
		}
		@keyframes active {
			from {
				box-shadow:inset 0px 0px 30px lightblue
			}
			to {
				box-shadow:inset 0px 0px 10px hotpink	
			}
		}
	</style>
	<title>Puzzle Game</title>
</head>
<div id="preloader">
	<div class="load-icon">
		<center>
			<i class="fas fa-circle-notch fa-spin fa-2x"></i>
		</center>
	</div>
</div>
<body>
	<center>
		<br/>
		<br/>
		<div id="stopwatch">
			<i class="fas fa-clock" style="color:gray"></i> <div id="time">00:00:00</div>
			<div class="white-line"></div>
		</div>
		<br/>
		<br/>
		<table class="board" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td colspan="2" style="height:100px;max-height:25vh">
					<img src="<?php echo $img; ?>" class="preview">				
				</td>
				<td class="cells" data-occupied="false" id="x0"></td>
			</tr>
			<tr>
				<td id="x1" data-occupied="true" class="cells put"></td>
				<td id="x2" data-occupied="true" class="cells put"></td>
				<td id="x3" data-occupied="true" class="cells"></td>
			</tr>
			<tr>
				<td id="x4" data-occupied="true" class="cells put"></td>
				<td id="x5" data-occupied="true" class="cells put"></td>
				<td id="x6" data-occupied="true" class="cells put"></td>
			</tr>
			<tr>
				<td id="x7" data-occupied="true" class="cells put"></td>
				<td id="x8" data-occupied="true" class="cells put"></td>
				<td id="x9" data-occupied="true" class="cells put"></td>
			</tr>
		</table>
	</center>
	<img src="<?php echo $img; ?>" id="source" style="display:none"/>
	<canvas id="dummy" height="450" width="450" style="display:none;"></canvas>
</body>
<script type="text/javascript">
	var current_selection = 0;
	var overall_time = 0;
	var counter;
	var ingameplay = true;
	function init() {
		try {
			var current_td = document.querySelector('td[data-occupied="false"]');
			current_td.style.animation = "active 0.6s infinite ease-in-out alternate-reverse forwards";
			var other_tds = document.querySelectorAll('td[data-occupied="true"]');
			for (let i = 0; i < other_tds.length;i++) {
				other_tds.style.animation = "";
			}
		}
		catch (err) {}
	}
	function move(x) {
		if (ingameplay == true) {
			var moveto = document.querySelector('td[data-occupied="false"]');
			var cell_tag = parseInt(moveto.id.replace("x",""));
			var element_tag = parseInt(x.parentNode.id.replace("x",""));
			var cnt = 0;
			if ((cell_tag == element_tag+1) && (cell_tag != 1 && cell_tag != 4 && cell_tag != 7)) {
				cnt++;
			}
			if ((cell_tag == element_tag-1) && (cell_tag != 0 && cell_tag != 3 && cell_tag != 6 && cell_tag != 9)) {
				cnt++;
			}
			if (Math.abs(cell_tag - element_tag) == 3) {
				cnt++;
			}
			if (cnt == 1) {
				var clone = x.cloneNode();
				x.parentNode.setAttribute("data-occupied", "false");
				moveto.setAttribute("data-occupied", "true");
				moveto.innerHTML = "";
				moveto.appendChild(clone);
				x.remove();
				init();
				isgameover();
			}
		}
	}
	function isgameover() {
		var cells = document.querySelectorAll(".cells");
		var tiles = document.querySelectorAll('img[tagger]');
		var cnt = 0;
		for (let i = 0; i < tiles.length; i++) {
			if (tiles[i].parentNode.id.replace("x","") == atob(tiles[i].getAttribute("tagger"))) {
				cnt++;
			}
		}
		if (cnt == 9) {
			gameover(0);
		}
	}
	document.body.onload = function() {
		var arr = new Array();
		var initial_img = new Array();
		function shuffle(arr) {
	        if (arr.length === 1) {return arr};
	        const rand = Math.floor(Math.random() * arr.length);
	        return [arr[rand], ...shuffle(arr.filter((_, i) => i != rand))];
	    }
		function cutphoto() {
			var img = document.getElementById("source");
			var can = document.getElementById("dummy");

			var ctx = can.getContext("2d");
			ctx.drawImage(img, 0, 0, 450, 450);
			var map = [
				[0,0,150,150],[150,0,300,150],[300,0,300,300],
				[0,150,150,300],[150,150,300,300],[300,150,300,450],
				[0,300,150,450],[150,300,300,450],[300,300,300,600]
			];
			var cnt = 0;
			for (let i = 0; i < map.length; i++) {
				cnt++;

				var rgb = ctx.getImageData(map[i][0],map[i][1],map[i][2],map[i][3]);

				var newCan = document.createElement('canvas');
			    newCan.width = 150;
			    newCan.height = 150;
			    var newCtx = newCan.getContext('2d');
			    
			    newCtx.putImageData(rgb, 0, 0);
			    if (cnt != 3) {
					arr.push([newCan.toDataURL(), i+1]);
				}
				else {
					initial_img = [newCan.toDataURL(), i+1];
				}
			}
			return shuffle(arr);
		}
		var result = cutphoto();
		var cells = document.querySelectorAll(".put");
		for (let i = 0; i < result.length; i++) {
			var im = document.createElement("img");
			im.setAttribute("style", "margin-bottom:-4px;width:100px;max-width:32vw;height:100px;max-height:25vh;cursor:pointer");
			im.className = "hov";
			im.setAttribute("tagger", btoa(result[i][1]));
			im.src = result[i][0];
			im.setAttribute("onclick", "move(this)");
			cells[i].innerHTML = "";
			cells[i].appendChild(im);
		}
		var ims = document.createElement("img");
		ims.setAttribute("style", "margin-bottom:-4px;width:100px;max-width:32vw;height:100px;max-height:25vh;cursor:pointer");
		ims.className = "hov";
		ims.setAttribute("tagger", btoa(initial_img[1]));
		ims.src = initial_img[0];
		ims.setAttribute("onclick", "move(this)");
		document.getElementById("x3").innerHTML = "";
		document.getElementById("x3").className = "cells put";
		document.getElementById("x3").appendChild(ims);
		init();
		document.getElementById("preloader").style.display = "none";
		counter = setInterval(function() {
			overall_time++;
			var h = Math.floor(overall_time / 3600);
			var m = Math.floor((overall_time % 3600)/60);
			var s = Math.floor((overall_time % 3600)%60);
			h = (h <= 9)?"0"+h:h;
			m = (m <= 9)?"0"+m:m;
			s = (s <= 9)?"0"+s:s;
			document.getElementById("time").innerHTML = h+":"+m+":"+s;
		}, 1000);
	}
	document.body.oncontextmenu = function(event) {
		event.preventDefault();
	}
	function gameover(pattern) {
		clearInterval(counter);
		ingameplay = false;
		if (pattern == 0) {
			//Finished
		}
		else {
			//Timeup
		}
	}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" integrity="sha512-F5QTlBqZlvuBEs9LQPqc1iZv2UMxcVXezbHzomzS6Df4MZMClge/8+gXrKw2fl5ysdk4rWjR0vKS7NNkfymaBQ==" crossorigin="anonymous"></script>
</html>