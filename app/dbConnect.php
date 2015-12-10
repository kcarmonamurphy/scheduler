<?php
$servername = "mysql12.000webhost.com";
$dbname = "a6215077_sched";
$username = "a6215077_goat";
$password = "soylent123"; //must have numbers, letters, no spaces, no symbols
//$emails = array()


function createUser($name, $email){
	if (checkUser($email) === 0){
		$sql = "INSERT INTO User (name, email) VALUES (" . $name . ", " . $email . ")";
		runSQL($sql);
		mailRegConfirm();
	}
}
function createEvent($emails, $owner){

	foreach ($emails as $i => $email){
		if ($i ==0){
			$organizer = "1";
		}
		else{
			$organizer = "0";
		}
		$isUser = checkUser($email);
		if ($isUser){
			//send e-mail
			$sql = "INSERT INTO Attendees (email, owner) VALUES (" . $email . ", " . $organizer . "); SELECT LAST_INSERT_ID();";
			$link = "http://kevcom.ca/" . runSQL($sql);
			//send e-mail to $isUser;
			//$link = "http://kevcom.ca/" . scheduleID
			mailInviteExists($email, $isUser, $link, $owner);
		}
		else{
			$sql = "INSERT INTO Attendees (email, owner) VALUES (" . $email . ", " . $organizer . "); SELECT LAST_INSERT_ID();";
			$link = "http://kevcom.ca/" . runSQL($sql);
			mailInvitation($email, $link, $owner);
		}
	}
}
function buildSchedule($eventId, $email){
	//joins
	$columns = array();
	//$columns = []; //depends on PHP version
	for ($i = 0; $i < 7; $i++){
		for ($j = 0; $j < 48; $j++){
			$columns[] = strval($i) . "_" . strval($j);
		}
	}
	$sql = "UPDATE Events";
	foreach($columns as $column){
		$sql .= "SET " . $column . "= (SELECT SUM(" . $column . ") FROM Events WHERE id=" . $eventId . ")|(SELECT SUM(" . $column . ") FROM Schedule WHERE email=" . $userId . "), ";
	}
	$sql .= ";";
	runSQL($sql);
}

function updateSchedule($timings, $email){
	//write to schedule
	$sql = "INSERT INTO Schedule ON DUPLICATE KEY UPDATE ";
	for ($i = 0; $i < 7; $i++){
		for ($j = 0; $j < 48; $j++){
			$index = strval($i) . "_" . strval($j);
			$sql .= $index . "=" . $timings[i][j] . ", ";
		}
	}
	$sql .= "WHERE email=". $email . ";";
	runSQL($sql);
}

function getSchedule($eventId){
	$sql = "SELECT * FROM Events WHERE id = " . $eventId . " LIMIT 1;";
	$response = runSQL($sql);
	$timings;
	foreach ($response as $i => $available){
		if ($i != "id"){
			$index = explode('_', $i);
			$timings[$index[0]][$index[1]] = $available;
		}
	}
	return $timings;
}

function checkUser($email){
	//check if $email is in list of users
	$sql = "SELECT name FROM User WHERE email = " . $email . " LIMIT 1;";
	$isUser = runSQL($sql);
	return $isUser;
}
function runSQL($sql){

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}
	$result = $conn->query($sql);

	//return $conn->error;
	$conn->close();
	return $result;
}

$app->get('/deleteUser/{id}', function ($id)  { // Match the root route (/) and supply the application as argument
    $sql = "DELETE FROM User WHERE id = " . $id . " LIMIT 1;";
    runSQL($sql);

    return $app['twig']->render( // Render the page index.html.twig
        'blog.html.twig',
        array(
            'articles' => $app['articles'], // Supply arguments to be used in the template
        )
    );
});

?>
