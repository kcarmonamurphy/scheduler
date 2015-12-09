<?php
//for setting up the columns of the table
$servername = "mysql12.000webhost.com";
$dbname = "a6215077_sched";
$username = "a6215077_goat";
$password = "soylent123"; //must have numbers, letters, no spaces, no symbols

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$days = array("Mon", "Tues", "Wed", "Thur", "Fri", "Sat", "Sun");

$sql = "CREATE TABLE Schedule ( id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY";
	foreach ($days as $i) {
		for ($j = 0; $j < 24; $j++){
			$sql .= ", " . $i . strval($j) . '0' . ' BOOL NOT NULL,';	//one for each day of the week, every half hour
			$sql .= $i . strval($j) . '5' . ' BOOL NOT NULL';
		}
	}
$sql .= ')';

if ($conn->query($sql) === TRUE) {
    echo "Table Schedule created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();

?>
