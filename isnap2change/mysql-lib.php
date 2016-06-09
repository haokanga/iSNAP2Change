<?php  	
    /*
    ```Naming Convention```
    
    create: INSERT
    update: UPDATE
    delete: DELETE
    get...: SELECT fetch
    get...s: SELECT fetchAll
    
    ```variables order```
    $conn, $pkCol(alphabetical order), $non-pkCol(alphabetical order)
    e.g. $conn, $questionID, $studentID, $status, $week
    
    ```Function Order```
    create
    update
    delete
    get...
    get...s
    misc
    
    */

    /* db connection*/
    function db_connect(){

        $conn;

        $servername = "localhost";
        $username = "root";
        $password = ".kHdGCD2Un%P";

        $conn = new PDO("mysql:host=$servername; dbname=isnap2changedb", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        return $conn;
    
    }

    function db_close($connection){
        $connection = null;
    }
    /* db connection*/
         
    /* School */
    function createSchool($conn, $schoolName){                
        $updateSql = "INSERT INTO School(SchoolName)
         VALUES (?);";			
        $updateSql = $conn->prepare($updateSql);                
        $updateSql->execute(array($schoolName));
        return $conn->lastInsertId();
    }
    
    function updateSchool($conn, $schoolID, $schoolName){
        $updateSql = "UPDATE School 
            SET SchoolName = ?
            WHERE SchoolID = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($schoolName, $schoolID));
    }
    
    function deleteSchool($conn, $schoolID){
        $updateSql = "DELETE FROM School WHERE SchoolID = ?";			
        $updateSql = $conn->prepare($updateSql);
        $updateSql->execute(array($schoolID));
    }
    
    function getSchool($conn, $schoolID){
        $schoolSql = "SELECT SchoolName
                   FROM School WHERE SchoolID = ?";
        $schoolQuery = $conn->prepare($schoolSql);
        $schoolQuery->execute(array($schoolID));
        $schoolResult = $schoolQuery->fetch(PDO::FETCH_OBJ);
        return $schoolResult;
    }
    
    function getSchoolByName($conn, $schoolName){
        $schoolSql = "SELECT SchoolID
                   FROM School WHERE SchoolName = ?";
        $schoolQuery = $conn->prepare($schoolSql);
        $schoolQuery->execute(array($schoolName));
        $schoolResult = $schoolQuery->fetch(PDO::FETCH_OBJ);
        return $schoolResult;
    }
    
    function getSchools($conn){
        $schoolSql = "SELECT SchoolID, SchoolName
                   FROM School";
        $schoolQuery = $conn->prepare($schoolSql);
        $schoolQuery->execute();
        $schoolResult = $schoolQuery->fetchAll(PDO::FETCH_OBJ);
        return $schoolResult;
    }
    /* School */    
    
    /* Class */
    function createClass($conn, $schoolID, $className){                
        $updateSql = "INSERT INTO Class(ClassName, SchoolID)
             VALUES (?,?)";			
        $updateSql = $conn->prepare($updateSql);         
        $updateSql->execute(array($className, $schoolID));
        return $conn->lastInsertId();
    }
    
    function updateClass($conn, $classID, $schoolID, $className){
         $updateSql = "UPDATE Class 
            SET ClassName = ?, SchoolID = ?
            WHERE ClassID = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($className, $schoolID, $classID));
    }
    
    function deleteClass($conn, $classID){
        $updateSql = "DELETE FROM Class WHERE ClassID = ?";			
        $updateSql = $conn->prepare($updateSql);
        $updateSql->execute(array($classID));
    }
    
    function getClass($conn, $classID){
        $classSql = "SELECT *
                   FROM Class WHERE ClassID = ?";
        $classQuery = $conn->prepare($classSql);
        $classQuery->execute(array($classID));
        $classResult = $classQuery->fetch(PDO::FETCH_OBJ);
        return $classResult;
    }
    
    function getClassByName($conn, $className){
        $classSql = "SELECT *
                   FROM Class WHERE ClassName = ?";
        $classQuery = $conn->prepare($classSql);
        $classQuery->execute(array($className));
        $classResult = $classQuery->fetch(PDO::FETCH_OBJ);
        return $classResult;
    }
    
    function getClassNum($conn){
        $classNumSql = "SELECT count(*) as Count, SchoolID
                   FROM School NATURAL JOIN Class
                   GROUP BY SchoolID";
        $classNumQuery = $conn->prepare($classNumSql);
        $classNumQuery->execute();
        $classNumResult = $classNumQuery->fetchAll(PDO::FETCH_OBJ);
        return $classNumResult;
    }
    
    function getClasses($conn){
        $classSql = "SELECT *
                   FROM Class NATURAL JOIN School";
        $classQuery = $conn->prepare($classSql);
        $classQuery->execute();
        $classResult = $classQuery->fetchAll(PDO::FETCH_OBJ);
        return $classResult;
    }    
    
    function getStudentNum($conn){
        $studentNumSql = "SELECT count(*) as Count, ClassID
                   FROM   Student NATURAL JOIN Class
                   GROUP BY ClassID";
        $studentNumQuery = $conn->prepare($studentNumSql);
        $studentNumQuery->execute();
        $studentNumResult = $studentNumQuery->fetchAll(PDO::FETCH_OBJ);
        return $studentNumResult;
    }
    /* Class */
    
    /* Token */    
    function updateToken($conn, $classID, $tokenString, $type){
        $updateSql = "INSERT INTO Token(ClassID, `Type`, TokenString)
                                    VALUES (?,?,?) ON DUPLICATE KEY UPDATE TokenString = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($classID, $type, $tokenString, $tokenString));
    }
    
    function getTokens($conn){
        $tokenSql = "SELECT ClassID, `Type`, TokenString
                   FROM Token NATURAL JOIN Class";
        $tokenQuery = $conn->prepare($tokenSql);
        $tokenQuery->execute();
        $tokenResult = $tokenQuery->fetchAll(PDO::FETCH_OBJ); 
        return $tokenResult;
    } 
    /* Token */
    
    /* Student */   
    function deleteStudent($conn, $studentID){
        $updateSql = "DELETE FROM Student WHERE StudentID = ?";			
        $updateSql = $conn->prepare($updateSql);
        $updateSql->execute(array($studentID));
    }
    
    function getStudents($conn){        
        $studentSql = "SELECT * , DATE(SubmissionTime) AS SubmissionDate FROM Student NATURAL JOIN Class
               ORDER BY ClassID";
        $studentQuery = $conn->prepare($studentSql);
        $studentQuery->execute();
        $studentResult = $studentQuery->fetchAll(PDO::FETCH_OBJ);  
        return $studentResult;
    }
    
    function resetPassword($conn, $studentID){
        $updateSql = "UPDATE Student 
            SET Password = ?
            WHERE StudentID = ?";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array(md5('WelcomeToiSNAP2'), $studentID));
    }    
    /* Student */
    
    /* Week */
    function removeWeek($conn, $week){
        $updateSql = "SET SQL_SAFE_UPDATES=0;
            UPDATE Quiz SET Week = NULL WHERE Week = ?;
            SET SQL_SAFE_UPDATES=1;";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($week));
        return $updateSql;
    }
    
    function getMaxWeek($conn){
        $weekSql = "select MAX(Week) as WeekNum from Quiz";
        $weekQuery = $conn->prepare($weekSql);
        $weekQuery->execute();
        $weekResult = $weekQuery->fetch(PDO::FETCH_OBJ);
        return $weekResult;
    }
    /* Week */
       
    /* Quiz */
    function createQuiz($conn, $week, $quizType, $topicID){
        $updateSql = "INSERT INTO Quiz(Week, QuizType, TopicID)
             VALUES (?,?,?);";			
        $updateSql = $conn->prepare($updateSql);         
        $updateSql->execute(array($week, $quizType, $topicID));                     
        return $conn->lastInsertId(); 
    }
    
    function updateQuiz($conn, $quizID, $topicID, $week){
        $updateSql = "UPDATE Quiz 
                SET Week = ?, TopicID = ?
                WHERE QuizID = ?";			
        $updateSql = $conn->prepare($updateSql);         
        $updateSql->execute(array($week, $topicID, $quizID)); 
    }
    
    function deleteQuiz($conn, $quizID){
        $updateSql = "DELETE FROM Quiz WHERE QuizID = ?";			
        $updateSql = $conn->prepare($updateSql);
        $updateSql->execute(array($quizID));
    }
    
    function getStuQuizScore($conn, $quizID, $studentID){
        $pointsBySection = array('MCQ', 'Matching', 'Poster', 'Misc');
        $pointsByQuestion = array('SAQ');
        $score = 0;
        
        $quizTypeSql = "SELECT COUNT(*) FROM Quiz NATURAL JOIN Quiz_Record WHERE QuizID = ? AND StudentID = ? AND `Status`='GRADED'";
        $quizTypeQuery = $conn->prepare($quizTypeSql);
        $quizTypeQuery->execute(array($quizID, $studentID));
        if($quizTypeQuery->fetchColumn() > 0){            
            $quizTypeSql = "SELECT * FROM Quiz NATURAL JOIN Quiz_Record WHERE QuizID = ? AND StudentID = ? AND `Status`='GRADED'";
            $quizTypeQuery = $conn->prepare($quizTypeSql);
            $quizTypeQuery->execute(array($quizID, $studentID));
            $quizTypeResult = $quizTypeQuery->fetch(PDO::FETCH_OBJ);    
            
            $quizType = $quizTypeResult->QuizType; 

            if(in_array($quizType, $pointsBySection)){
                $pointsSql = "SELECT * FROM Quiz NATURAL JOIN ".$quizType."_Section WHERE QuizID = ?";
                $pointsQuery = $conn->prepare($pointsSql);
                $pointsQuery->execute(array($quizID));
                $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
                $score = $pointsResult->Points;
            } else if(in_array($quizType, $pointsByQuestion)){
                $pointsSql = "SELECT QuizID, StudentID, SUM(Grading) AS SumPoints FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question NATURAL JOIN SAQ_Question_Record WHERE QuizID = ? AND StudentID = ? ";
                $pointsQuery = $conn->prepare($pointsSql);
                $pointsQuery->execute(array($quizID, $studentID));
                $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
                $score = $pointsResult->SumPoints;
            }
        }  
        
        return $score;
    } 
    
    function getQuizNum($conn){
        $weekSql = "SELECT Week, COUNT(*) AS QuizNum FROM Quiz GROUP BY Week";
        $weekQuery = $conn->prepare($weekSql);
        $weekQuery->execute();
        $weekResult = $weekQuery->fetchAll(PDO::FETCH_OBJ); 
        return $weekResult;
    }
    
    function getQuizzes($conn){
        $quizSql = "SELECT QuizID, Week, QuizType, TopicName
                   FROM Quiz NATURAL JOIN Topic";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute();
        $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ);
        return $quizResult;
    }
    
    function getQuizzesByWeek($conn, $week){
        $quizSql = "SELECT QuizID, Week, QuizType, TopicName
                   FROM Quiz NATURAL JOIN Topic WHERE Week = ?";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute(array($week));
        $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ);
        return $quizResult;
    }
    
    function getQuizPoints($conn, $quizID){
        $pointsBySection = array('MCQ', 'Matching', 'Poster', 'Misc');
        $pointsByQuestion = array('SAQ');
        $points = 0;        
        
        $quizTypeSql = "SELECT COUNT(*) FROM Quiz WHERE QuizID = ?";
        $quizTypeQuery = $conn->prepare($quizTypeSql);
        $quizTypeQuery->execute(array($quizID));       
        if($quizTypeQuery->fetchColumn() > 0){
            $quizTypeSql = "SELECT QuizType FROM Quiz WHERE QuizID = ?";
            $quizTypeQuery = $conn->prepare($quizTypeSql);
            $quizTypeQuery->execute(array($quizID));
            $quizTypeResult = $quizTypeQuery->fetch(PDO::FETCH_OBJ);
            $quizType = $quizTypeResult->QuizType;
            
            if(in_array($quizType, $pointsBySection)){
                $pointsSql = "SELECT Points AS SumPoints FROM Quiz NATURAL JOIN ".$quizType."_Section WHERE QuizID = ?";
            } else if(in_array($quizType, $pointsByQuestion)){
                $pointsSql = "SELECT SUM(Points) AS SumPoints FROM Quiz NATURAL JOIN SAQ_Section NATURAL JOIN SAQ_Question WHERE QuizID = ?";
            }
            $pointsQuery = $conn->prepare($pointsSql);
            $pointsQuery->execute(array($quizID));
            $pointsResult = $pointsQuery->fetch(PDO::FETCH_OBJ);
            $points = $pointsResult->SumPoints;
        }
        
        return $points;
    }           
    /* Quiz */
    
    /* Topic */    
    function getTopic($conn, $topicID){
        $topicSql = "SELECT * FROM Topic WHERE TopicID = ?";
        $topicQuery = $conn->prepare($topicSql);
        $topicQuery->execute(array($topicID));
        $topicResult = $topicQuery->fetch(PDO::FETCH_OBJ);
        return $topicResult;
    }

    function getTopicByName($conn, $topicName){
        $topicSql = "SELECT * FROM Topic WHERE TopicName = ?";
        $topicQuery = $conn->prepare($topicSql);
        $topicQuery->execute(array($topicName));
        $topicResult = $topicQuery->fetch(PDO::FETCH_OBJ);
        return $topicResult;
    }
    
    function getTopics($conn){
        $topicSql = "SELECT * FROM Topic ORDER BY TopicID";
        $topicQuery = $conn->prepare($topicSql);
        $topicQuery->execute(array());
        $topicResult = $topicQuery->fetchAll(PDO::FETCH_OBJ); 
        return $topicResult;
    }
    /* Topic */
    
    /* MCQ */
    
    function createMCQSection($conn, $quizID, $points, $questionnaires){
        $updateSql = "INSERT INTO MCQ_Section(QuizID, Points, Questionnaires)
                    VALUES (?,?,?);";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($quizID, $points, $questionnaires)); 
    }
    
    function updateMCQSection($conn, $quizID, $points, $questionnaires){
        $updateSql = "UPDATE MCQ_Section
                    SET Points = ?, Questionnaires = ?
                    WHERE QuizID = ?;";			
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($points, $questionnaires, $quizID));
    }
    
    function getMCQQuiz($conn, $quizID){
        $quizSql = "SELECT QuizID, Week, TopicName, Points, Questionnaires, COUNT(MCQID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN MCQ_Section LEFT JOIN MCQ_Question USING (QuizID) WHERE QuizID = ? GROUP BY QuizID";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute(array($quizID));
        $quizResult = $quizQuery->fetch(PDO::FETCH_OBJ);
        return $quizResult;
    }
    
    function getMCQQuizzes($conn){
        $quizSql = "SELECT QuizID, Week, TopicName, Points, Questionnaires, COUNT(MCQID) AS Questions
                   FROM Quiz NATURAL JOIN Topic NATURAL JOIN MCQ_Section LEFT JOIN MCQ_Question USING (QuizID) WHERE QuizType = 'MCQ' GROUP BY QuizID";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute();
        $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ); 
        return $quizResult;
    }    
    /* MCQ */
    
    /* Option */
    function getOptions($conn, $quizID){
        $mcqQuesSql = "SELECT MCQID, Question, CorrectChoice, Content, Explanation
                   FROM   MCQ_Section NATURAL JOIN MCQ_Question
                                  NATURAL JOIN `Option`
                   WHERE  QuizID = ?
                   ORDER BY MCQID";                                
        $mcqQuesQuery = $conn->prepare($mcqQuesSql);
        $mcqQuesQuery->execute(array($quizID));
        $mcqQuesResult = $mcqQuesQuery->fetchAll(PDO::FETCH_OBJ); 
        return $mcqQuesResult;
    }
    
    function getMaxOptionNum($conn, $quizID){
        $optionNumSql = "SELECT MAX(OptionNum) AS MaxOptionNum FROM (SELECT COUNT(*) AS OptionNum FROM MCQ_Question natural JOIN `Option` WHERE QuizID = ? GROUP BY MCQID) AS OptionNumbTable;";								
        $optionNumQuery = $conn->prepare($optionNumSql);
        $optionNumQuery->execute(array($quizID));
        $optionNumResult = $optionNumQuery->fetch(PDO::FETCH_OBJ);
        return $optionNumResult;
    }
    /* Option */
    
    /* Learning_Material */
    function createEmptyLearningMaterial($conn, $quizID){
        $content='<p>Learning materials for this quiz has not been added.</p>';
        $updateSql = "INSERT INTO Learning_Material(Content,QuizID) VALUES (?,?);";
        $updateSql = $conn->prepare($updateSql);                            
        $updateSql->execute(array($content, $quizID));
    }
    
    function getLearningMaterial($conn, $quizID){
        $materialPreSql = "SELECT COUNT(*) 
                       FROM   Learning_Material
                       WHERE  QuizID = ?";							
        $materialPreQuery = $conn->prepare($materialPreSql);
        $materialPreQuery->execute(array($quizID));                
        if($materialPreQuery->fetchColumn() != 1){
                    
        }                
        $materialSql = "SELECT Content, TopicName 
                        FROM   Learning_Material NATURAL JOIN Quiz
                                                 NATURAL JOIN Topic
                        WHERE  QuizID = ?";
                                
        $materialQuery = $conn->prepare($materialSql);
        $materialQuery->execute(array($quizID));
        $materialRes = $materialQuery->fetch(PDO::FETCH_OBJ);
        return $materialRes;
    }
    /* Learning_Material */
    
    
    function calculateStudentScore($conn, $studentID){
        $score = 0;
        
        $quizSql = "SELECT * FROM Quiz NATURAL JOIN Quiz_Record WHERE StudentID = ? AND `Status`='GRADED'";
        $quizQuery = $conn->prepare($quizSql);
        $quizQuery->execute(array($studentID));
        $quizResult = $quizQuery->fetchAll(PDO::FETCH_OBJ);
        for($i=0; $i<count($quizResult);$i++){
            $score+= getStuQuizScore($conn, $quizResult[$i]->QuizID, $studentID);
        }
        
        return $score;
    } 
    
    function getStudentScore($conn, $studentID){
        $score = 0;
        
        $scoreSql = "SELECT COUNT(*) FROM Student WHERE StudentID = ? ";
        $scoreQuery = $conn->prepare($scoreSql);
        $scoreQuery->execute(array($studentID));
        if($scoreQuery->fetchColumn() > 0){
            $scoreSql = "SELECT * FROM Student WHERE StudentID = ? ";
            $scoreQuery = $conn->prepare($scoreSql);
            $scoreQuery->execute(array($studentID));
            $scoreResult = $scoreQuery->fetch(PDO::FETCH_OBJ);
            $score = $scoreResult->Score;
        }
        
        return $score;
    }
    
    function updateStudentScore($conn, $studentID){        
        $updateSql = "UPDATE Student 
                SET Score = ?
                WHERE StudentID = ?";			
        $updateSql = $conn->prepare($updateSql);         
        $updateSql->execute(array(calculateStudentScore($conn, $studentID), $studentID));        
    }
	
	function updateQuizRecord($conn, $quizID, $studentID, $status){
		$updateQuizRecordSql = "INSERT INTO Quiz_Record(QuizID, StudentID, Status)
							    VALUES (?,?,?) ON DUPLICATE KEY UPDATE Status = ?;";			
		
		$updateQuizRecordQuery = $conn->prepare($updateQuizRecordSql);                            
		$updateQuizRecordQuery->execute(array($quizid, $studentid, $status, $status));
	}
?>