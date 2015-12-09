<?php
$servername = "mysql12.000webhost.com";
$dbname = "a6215077_sched";
$username = "a6215077_goat";
$password = "soylent123"; //must have numbers, letters, no spaces, no symbols
//$emails = array()


function createUser($name, $email){
	$sql = "INSERT INTO User (name, email) VALUES (" . $name . ", " . $email . ")";
	runSQL($sql);
}
function createEvent($emails, $owner){
	foreach ($emails as $i){
		$isUser = checkUser($i);
		//if first element in $emails, $owner = TRUE
		if ($isUser){
			//send e-mail
			$sql = "INSERT INTO Meetings (email, owner) VALUES (" . $i . ", " . $owner . ")";
			//send e-mail to $isUser;
		}
		else{
			$sql = "INSERT INTO Meetings (email, owner) VALUES (" . $i . ", " . $owner . ")";
		}
	}
}
function buildSchedule(){
	//joins
	BIT_OR();
}
function checkUser($email){
	//check if $email is in list of users
	return false;
}
function runSQL($sql){

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->query($sql) === TRUE) {
	    echo "Command run successfully";
	} else {
	    echo "Error running command: " . $conn->error;
	}
	$conn->close();
}

?>