<?php
include ".htconfig.php";
if (!$DB_USER) die("Please copy .htconfig.php.template to .htconfig.php and fill in the settings");

$db = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8", $DB_USER, $DB_PASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_start();

function show_header($title) {
  include "header.php";
}
