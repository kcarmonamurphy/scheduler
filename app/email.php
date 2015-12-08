<?php

require 'vendor/autoload.php';
Dotenv::load(__DIR__);


$url = 'https://api.sendgrid.com/';
$user = 'USERNAME';
$pass = '
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';

$toAddress
$fromName

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

function mailRegConfirm($toAddress, $link, $linkNo, $fromName){
    $message = file_get_contents('templates/confirm.html');
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

    $sendgrid = new SendGrid($sendgrid_username, $sendgrid_password);

    $email
        ->setFrom('kevin@kevcom.ca');


    //try sending out the response
    try {
        $sendgrid->send($email);
    } catch(\SendGrid\Exception $e) {
        echo $e->getCode();
        foreach($e->getErrors() as $er) {
            echo $er;
        }
    }
}

?>