<?php

	session_start();
	//$studentid = 1;
	$_SESSION["studentid"] = 1;

?>

<html>
<head>
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script>


function startWeeklyTask($week){
    $("#week").val($week);
	document.getElementById("weeklytask").submit();	
}

function scoreboard(){
	document.getElementById("scoreboard").submit();	
}

</script>
</head>
<body>
<!--
<div id="a" align="center">
<form id="weeklytask1" action=weekly-task.php method=post>
<button type=button onclick="startWeeklyTask1()"> Week 1 </button>
<input  type=hidden name="week" value="1"></input>
</form>
<form id="weeklytask3" action=weekly-task.php method=post>
<button type=button onclick="startWeeklyTask3()"> Week 3 </button>
<input  type=hidden name="week" value="3"></input>
</form>
</div>
-->
<div align="center">
<form id="weeklytask" action=weekly-task.php method=post>
    <input  type=hidden id="week" name="week" value="0"></input>
	<button type=button onclick="startWeeklyTask(1)"> Week 1 </button>
	<button type=button onclick="startWeeklyTask(2)"> Week 2 </button>
	<button type=button onclick="startWeeklyTask(3)"> Week 3 </button>
	<button type=button onclick="startWeeklyTask(4)"> Week 4 </button>
	<button type=button onclick="startWeeklyTask(5)"> Week 5 </button>
	<button type=button onclick="startWeeklyTask(6)"> Week 6 </button>
	<button type=button onclick="startWeeklyTask(7)"> Week 7 </button>
	<button type=button onclick="startWeeklyTask(8)"> Week 8 </button>
	<button type=button onclick="startWeeklyTask(9)"> Week 9 </button>
	<button type=button onclick="startWeeklyTask(10)"> Week 10 </button>
</form>
</div>
<div align="center">
	<button type=button> Check Progress </button>
    <form id="scoreboard" action=scoreboard.php method=post>
	<button type=button onclick="scoreboard()"> Check Scoreboard </button>
    </form>
</form>
</div>
</body>
</html>


