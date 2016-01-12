<?php
$servername = "mysql12.000webhost.com";
$dbname = "a6215077_sched";
$username = "a6215077_goat";
$password = "soylent123"; //must have numbers, letters, no spaces, no symbols
//$emails = array()


function createEvent($emails, $owner, $timings){
	//$owner = name of the person who started the event

	//save $timings and create $eventId
	$sql =  "INSERT INTO Events (";	//0_0,...,6_47

	//columns
	for ($i = 0; $i < 7; $i++){
		for ($j = 0; $j < 48; $j++){
			$columns = strval($i) . "_" . strval($j);
			$sql .= $column . ", ";
		}
	}

	$sql .= ") VALUES ("			//val[0][0],...,val[6][47]

	for ($i = 0; $i < 7; $i++){
		for ($j = 0; $j < 48; $j++){
			$columns = strval($i) . "_" . strval($j);
			$sql .= $timings[$i][$j] . ", ";
		}
	}

	$sql .= "); SELECT LAST_INSERT_ID()";	//TODO INSERT
	$eventId = runSQL($sql);

	//save attendees
	foreach ($emails as $i => $email){
		$organizer = 0;						//will hold owner's name
		$link = "http://kevcom.ca/" . $eventId
		$hashId = 0;						//hash zeroed after each confirmation
		if ($i === 0){						//owner has already entered schedule, so hash remains 0
			$organizer = $owner;
			mailEventScheduled($email, $link, $owner);
		}
		else{
			//https://stackoverflow.com/questions/2293684/what-is-the-best-way-to-create-a-random-hash-string
			$hashId = bin2hex(mcrypt_create_iv(15, MCRYPT_DEV_URANDOM));	//renamed hash--> hashId because I fear hash is a keyword
			$link .= "/" . $hashId;
			mailInvitation($email, $link, $owner);
		}
		
		$sql = "INSERT INTO Attendees (eventId, email, owner, hashId) VALUES (" $eventId . ", " . $email . ", " . $organizer . ", " . $hashId . ");";
		runSQL($sql);
	}
	return $eventId;
	//TODO redirect to /{eventId}
}
function buildSchedule($eventId, $hashId, $timings){
	//combine $timings with current schedule

	$sql = "UPDATE Events SET ";

	for ($i = 0; $i < 7; $i++){
		for ($j = 0; $j < 48; $j++){
			$columns = strval($i) . "_" . strval($j);
			$sql .= $column . "= MAX((SELECT " . $column . " FROM Events WHERE id=" . $eventId . "), " . $timings[$i][$j] ."), ";
		}
	}

	$sql .= ";";
	runSQL($sql);

	$sql = "UPDATE Attendees SET hashId=0 WHERE hashId=" . $hashId . ";";	//set user as confirmed
	runSQL($sql);

	//get info to mail to people
	$sql = "SELECT email, owner FROM Attendees WHERE event=" . $eventId . ", owner!=0) ;";
	$owner = runSQL($sql);
	mailRSVP($owner[0], $eventId, $owner[1]);		//($email, $eventId, $ownerName)

	$sql = "SELECT hashId FROM Attendees WHERE hashId !=0 LIMIT 1;";	//check to see if anyone hasn't confirmed
	$confirmed = runSQL($sql);
	if ($confirmed == 0){							//everyone has confirmed; maybe I should use (!$confirmed)
		$sql = "SELECT email FROM Attendees WHERE event=" . $eventId . ";";
		$emails = runSQL($sql);
		mailEntered($emails, $eventId, $owner[1]);
	}
	//TODO redirect to /{eventId}
}

function getSchedule($eventId){
	$sql = "SELECT * FROM Events WHERE id = " . $eventId . " LIMIT 1;";	//this is run when the event page is loaded
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

$app->get('/{eventId}', function ($id)  { // Match the root route (/) and supply the application as argument
    $timings = getSchedule($id);

    return $app['twig']->render( // Render the page index.html.twig
    	'event.html.twig',
    	//TODO pass grid array
    	$timings = getSchedule($id);
		array(			
			'grid' => $timings,
			'daysOfWeek' => $app['daysOfWeek'],
			'timeSlots' => $app['timeSlots'],
		)
	);
});

$app->get('/{eventId}/{hashId}', function (Silex\Application $app, $eventId, $hashId)  { // Match the root route (/) and supply the application as argument

	if ($hashId == 0){						//user has already confirmed
    	$timings = getSchedule($eventId);	//redirect to event page
    }
    else{
    	//buildSchedule($eventId, $hashId, $timings)
        return $app['twig']->render( // Render the page index.html.twig
        	'guest.html.twig',
			array(
				'daysOfWeek' => $app['daysOfWeek'],
				'timeSlots' => $app['timeSlots'],
				'eventId' => $eventId,
				'hashId' => $hashId,//merge2event with eventId, hashId
			)
    	);
    }

$app->post('/merge2event', function(Request $request){
	$eventId = $request->get('eventId');
	$hashId = $request->get('hashId');
	$timings = $request->get('grid');
	buildSchedule($eventId, $hashId, $timings);
});


?>
