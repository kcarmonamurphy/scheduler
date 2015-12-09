<?php

//$emails = array()

function createUser($email, $name){

}
function createEvent($emails){
	foreach ($emails as $i){
		if (checkUser($i)){
			//send e-mail 
		}
		else{
			//
		}
	}
}
function buildSchedule(){
	//joins
}
function checkUser($email){
	//check if $email is in list of users
	return false;
}

?>