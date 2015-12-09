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
	}
}
function createEvent($emails, $owner){

	foreach ($emails as $i => $email){
		if ($i ==0){
			$organizer = "TRUE";
		}
		else{
			$organizer = "FALSE";
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
		}
	}
}
function buildSchedule(){
	//joins
	BIT_OR();
}
function getSchedule($eventId){
	$sql = "SELECT * FROM "
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

?>
