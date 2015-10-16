<?php
require "init.php";

if (isset($_GET["logout"])) {
  unset($_SESSION["login"]);
  header("Location: .");
  die();
}
if (isset($_POST["password"])) {
  if ($_POST["password"] == $password) {
    $_SESSION["login"] = 1;
    header("Location: admin");
    die();
  } else {
    echo "Falsches Passwort<br>";
  }
}
if (!isset($_SESSION["login"])) {
  die("<form method=post><input type=password name=password><input type=submit></form>");
}
if (isset($_POST["freigabe"])) {
  $id = intval($_POST["id"]);
  $c = $db->exec("UPDATE fragen SET freigegeben= ".intval($_POST["freigabe"])." WHERE id = $id");
  die($c);
}
if (isset($_POST["anmerkung"])) {
  $id = intval($_POST["id"]);
  $c = $db->exec("UPDATE fragen SET anmerkung= ".$db->quote($_POST["anmerkung"])." WHERE id = $id");
  die($c);
}
if (isset($_POST["delete"])) {
  $id = intval($_POST["delete"]);
  $c = $db->exec("UPDATE fragen SET freigegeben=2 WHERE id = $id");
  die($c);
}


include "header.php";
?>
<div class="container">
<div class="row">

  
  <ul class="list-group" id="fragen">
  
  </ul>

</div>
</div>

<script src="https://static.luelistan.net/jquery/jquery-2.1.3.min.js"></script>
<script src="https://static.luelistan.net/bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
<script>
  $(function() {
    function loadQuestions() {
      $.get("api.php?read=alle", function(d) {
        var $f = $("#fragen").html("");
        var lastgroup=-1;
        for(var i in d) {
          if (lastgroup!=d[i].freigegeben) {
            lastgroup=d[i].freigegeben;
            switch(lastgroup) {
              case "0": $f.append("<li class='list-group-item'><b>Nicht freigegeben</b></li>"); break;
              case "1": $f.append("<li class='list-group-item'><b>Freigegeben</b></li>"); break;
              case "3": $f.append("<li class='list-group-item'><b>Beantwortet</b></li>"); break;
            }
          }
          var $item = $("<li class='list-group-item'>").text(d[i].frage).attr('data-id', d[i].id);
          $item.prepend('<span class="label label-primary">'+d[i].upvotes+'</span> ');
          $item.append('<span class="pull-right"> <span class="label freigabe">ok</span> <span class="label label-danger delete">x</span></span><p><input class=anm placeholder=Anmerkung></p>');
          $f.append($item);
          if (d[i].freigegeben=="1") {
            $item.find('.freigabe').addClass('label-primary');
          } else if (d[i].freigegeben=="3") {
            $item.find('.freigabe').addClass('label-success');
          } else {
            $item.find('.freigabe').addClass('label-default');
          }
          $item.find('input').val(d[i].anmerkung);
          $item.attr('freigabe', d[i].freigegeben);
        }
        
        $('input.anm').change(function() {
          var $item = $(this).closest("li");
          $.post("admin.php", { "id": $item.attr("data-id"), "anmerkung": $item.find("input").val() });
        });
        $(".delete").click(function() {
          $.post("admin.php", { "delete": $(this).closest("li").attr("data-id") }, function(x) {
            loadQuestions();
          });
        });
        $(".freigabe").click(function() {
          var $item = $(this).closest("li");
          var newval = $item.attr("freigabe")==1 ? 3 : $item.attr("freigabe")==3 ? 0 : 1;
          $.post("admin.php", { "freigabe": newval , "id": $item.attr("data-id") }, function(x) {
            loadQuestions();
          });
        });
      }, "json");
      
    }
    
    loadQuestions();
    setInterval(loadQuestions, 9000);
  });
  
</script>
</body></html>
