<?php

require 'classes/SessionManager.php';
$sessionManager = new SessionManager();
$sessionManager->destroySession();

header('location:index.php');