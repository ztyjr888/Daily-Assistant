<?php
include_once 'gomain_mailHandle.php';
$wxGomainMailHandler = new WxGomainMailHandler();
$wxGomainMailHandler->sendEmail();
$wxGomainMailHandler->clearUserVisitCount();
//$wxGomainMailHandler->resetCounter();
?>
