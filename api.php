<?php
require "init.php";

if (isset($_POST["question"])) {
  if (preg_match('/[a-z]/', $_POST['question']) != 1) return;
  $frage = $db->quote($_POST["question"]);
  $ipaddr = $db->quote($_SERVER["REMOTE_ADDR"]);
  $db->query("INSERT INTO fragen (frage,upvotes,eindat,freigegeben,ipaddr) VALUES($frage,0,NOW(),0,$ipaddr)");
  touch("lastchange");

} elseif (isset($_POST["upvote"])) {
  $id = intval($_POST["upvote"]);
  if ($_SESSION["hasUpvoted"][$id]) {
    $db->query("UPDATE fragen SET upvotes=upvotes-1 WHERE id = $id");
  }else {
    $db->query("UPDATE fragen SET upvotes=upvotes+1 WHERE id = $id");
  }
  $_SESSION["hasUpvoted"][$id] = !$_SESSION["hasUpvoted"][$id];
  touch("lastchange");
  
} elseif (isset($_GET["read"])) {
  $timestamp = filemtime("lastchange");
  if (intval($_GET["since"]) >= $timestamp) die("false");

  header("Content-Type: application/json; charset=utf-8");
  if ($_GET["read"] == "alle") {
    $r = $db->query("SELECT * FROM fragen WHERE freigegeben <> 2 ORDER BY freigegeben ASC, upvotes DESC");
  } else {
    $r = $db->query("SELECT * FROM fragen WHERE freigegeben = 1 OR freigegeben=3 ORDER BY freigegeben ASC, upvotes DESC");
  }
  echo "{ \"timestamp\": $timestamp, \"data\": [\n";
  $komma="";
  while($d = $r->fetch()) {
    echo $komma.json_encode($d) . "\n";
    $komma=",";
  }
  echo "] }\n";
} else {
  header("HTTP/1.1 404 Not found");
  echo "nothing to do";
}
