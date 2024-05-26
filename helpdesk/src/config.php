<?php

$conn = new mysqli("localhost", "root", "", "helpdesk") or die("Connection failed");
$conn -> set_charset("utf8");

// Check connection
if ($mysqli -> conn) {
    echo "Failed to connect to MySQL: " . $mysqli -> conn;
    exit();
  }