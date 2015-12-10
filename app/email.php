<?php

require 'vendor/autoload.php';
Dotenv::load(__DIR__);

$api_user = 'carmonk';
$api_key = '!R3S!49QGyM6a*xkmbdFPC89Kj';
$fromAddress = 'kevin@kevcom.ca'

function mailStartEditing($toAddress, $link, $fromName){
    $message = file_get_contents('templates/begin.html');
    $email = new SendGrid\Email();
    $email
        ->addTo($toAddress)
        ->setHtml($message)
        ->setSubject("Everyone has entered their schedule!")
        ->addSubstitution("%link%", $link)
        ->addSubstitution("%fromName%", $fromName);
    sendMail($email);
});

function mailInviteExists($toAddress, $toName, $link, $fromName){
    $message = file_get_contents('templates/permit.html');
    $email = new SendGrid\Email();
    $email
        ->addTo($toAddress)
        ->setHtml($message)
        ->setSubject("%fromName invited you to an event!")
        ->addSubstitution("%toName%", $toName)
        ->addSubstitution("%link%", $link)
        ->addSubstitution("%fromName%", $fromName);
    sendMail($email);
});

function mailInvitation($toAddress, $link, $fromName){
    $message = file_get_contents('templates/invitation.html');
    $email = new SendGrid\Email();
    $email
        ->addTo($toAddress)
        ->setHtml($message)
        ->setSubject("%fromName invited you to an event!")
        ->addSubstitution("%link%", $link)
        ->addSubstitution("%fromName%", $fromName);
    sendMail($email);
});

function mailEventScheduled($toAddress, $fromName){
    $message = file_get_contents('templates/organize.html');
    $email = new SendGrid\Email();
    $email
        ->addTo($toAddress)
        ->setHtml($message)
        ->setSubject("You just scheduled an event!")
        ->addSubstitution("%fromName%", $fromName);
    sendMail($email);
});

function mailAccessRequest($toAddress, $toName, $link, $fromName){
    $message = file_get_contents('templates/permitted.html');
    $email = new SendGrid\Email();
    $email
        ->addTo($toAddress)
        ->setHtml($message)
        ->setSubject("%toName% confirmed availability")
        ->addSubstitution("%toName%", $toName)
        ->addSubstitution("%link%", $link)
        ->addSubstitution("%fromName%", $fromName);
    sendMail($email);
});

function mailRegConfirm($toAddress, $fromName, $userId){
    $message = file_get_contents('templates/confirm.html');
    $link = "http://kevcom.ca/";
    $linkNo = "http://kevcom.ca/deleteUser/" . $userId;
    $email = new SendGrid\Email();
    $email
        ->addTo($toAddress)
        ->setHtml($message)
        ->setSubject("Confirm sign up to scheduler")
        ->addSubstitution("%link%", $link)
        ->addSubstitution("%linkNo%", $linkNo)
        ->addSubstitution("%fromName%", $fromName);
    sendMail($email);
});

function sendMail($email){

    $sendgrid = new SendGrid($api_user, $api_key);

    $url = 'https://api.sendgrid.com/';
    $request =  $url.'api/mail.send.json';

    // Generate curl request
    $session = curl_init($request);
    // Tell curl to use HTTP POST
    curl_setopt ($session, CURLOPT_POST, true);
    // Tell curl that this is the body of the POST
    curl_setopt ($session, CURLOPT_POSTFIELDS, $email);
    // Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, false);
    // Tell PHP not to use SSLv3 (instead opting for TLS)
    curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

    // obtain response
    $response = curl_exec($session);
    curl_close($session);

    // print everything out
    print_r($response);
}



?>