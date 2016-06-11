<?php
	session_start();
	require_once('mysql-lib.php');
	require_once('debug.php');	
	$pageName = "learning-material";
	
	//check login status
	if(!isset($_SESSION["studentID"])){
		
	}
	
	//check whether a request is GET or POST 
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_POST["quizid"]) && isset($_POST["quiztype"]) && isset($_POST["week"])){
			$quizID = $_POST["quizid"];
			$quizType = $_POST["quiztype"];
			$week = $_POST["week"];
		} else {
			
		}
	} else {
		
	}
	
	$conn = null;
	
	try {
		$conn = db_connect();
		
		//get learning material
		$materialRes = getLearningMaterial($conn, $quizID);
		
	} catch(Exception $e){
		if($conn != null) {
			db_close($conn);
		}
			
		debug_err($pageName, $e);
		$feedback["message"] = $e->getMessage();
		echo json_encode($feedback);
		exit;
	}
	
	db_close($conn);

?>

<html>
    <head>
        <title>Quiz</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/info.css" />
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href='https://fonts.googleapis.com/css?family=Raleway:400|Open+Sans' rel='stylesheet' type='text/css'>
        <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="js/jquery-1.12.3.js"></script>
    </head>
    <body>
         <script>         
            // decide quiz type and begin quiz
            function beginQuiz()
			{
                //list of question type
				<?php 
					switch($quizType) {
						case "MCQ": 
							echo 'document.getElementById("formQuizBegin").setAttribute("action", "multiple-choice-question.php");';
							break;
						case "SAQ":
							 echo 'document.getElementById("formQuizBegin").setAttribute("action", "short-answer-question.php");';
							 break;
						case "Matching":
							echo 'document.getElementById("formQuizBegin").setAttribute("action", "matching-question.php");';
							break;
						case "Poster":
							echo 'document.getElementById("formQuizBegin").setAttribute("action", "poster-editor.php");';
							break;
						default:
							break;
					}
				?>
				
				document.getElementById("formQuizBegin").submit();
			}
        </script>
        
        <div class="content"> 
		<div class="contentHeader">
			<img src="css/n1.jpg" alt="logo" style='width:100%; height:70vh;'/>
		</div>
           
		<div class="info" id="panel0" style="top:58vh; padding-bottom:10px;">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="heading" style="color: black; max-height:10vh; text-align:center; border-bottom: 1px solid #eee;">
                                <h1 style='padding: 0px;'> 
								<i>	<?php echo $materialRes->TopicName; ?> </i>                          
                                </h1> 
					</div>
                   
		<div class="para" style="padding-left:15px; padding-right:15px; padding-top:8px; text-align:center;">
			<div style="color:black; justify-content:center; align-items:center;">
			<i>
			  <?php echo $materialRes->Content; ?></i>
			</div>
		</div>
                        <br>
                        <br>
						<form id="formQuizBegin" method=post>
                            <button type="button" onclick="beginQuiz()" class="btn btn-default btn-lg btn-block" style="background-color:darkseagreen;">BEGIN QUIZ </button>
							<input  type=hidden name="quizID" value=<?php echo $quizID; ?>></input>
                            <input  type=hidden name="quizType" value=<?php echo $quizType; ?>></input>
							<input  type=hidden name="week" value=<?php echo $week; ?>></input>
                            <input  type=hidden name="status" value=<?php echo $status; ?>></input>
                           <!--
						   implement logic 
						   -->
                        </form>
					</div>
                </div>
            </div>
        </div>
        <script>
           
        </script>
    </body>
</html>
