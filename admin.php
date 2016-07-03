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
  touch("lastchange");
  die($c);
}
if (isset($_POST["anmerkung"])) {
  $id = intval($_POST["id"]);
  $c = $db->exec("UPDATE fragen SET anmerkung= ".$db->quote($_POST["anmerkung"])." WHERE id = $id");
  touch("lastchange");
  die($c);
}
if (isset($_POST["delete"])) {
  $id = intval($_POST["delete"]);
  $c = $db->exec("UPDATE fragen SET freigegeben=2 WHERE id = $id");
  touch("lastchange");
  die($c);
}


show_header("Admin - Fragen");
?>
<div class="container">
<div class="row">
<input type="checkbox" id="autoreload" checked> Auto reload

<button id="reload" class="btn btn-default">Reload</button><br>
  
  <ul class="list-group" id="fragen">
  
  </ul>

</div>
</div>

<script src="https://static.luelistan.net/jquery/jquery-2.1.3.min.js"></script>
<script src="https://static.luelistan.net/bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
<script>
  $(function() {
    var lastchange = 0;
    function loadQuestions() {
      $.get("api.php?read=alle&since="+lastchange, function(result) {
        if ($("#autoreload")[0].checked) setTimeout(loadQuestions,3000);
        if (result == false) return;
        lastchange = result.timestamp;
        var d = result.data;
        var $f = $("#fragen").html("");
        var lastgroup=-1;
        for(var i in d) {
          if (lastgroup!=d[i].freigegeben) {
            lastgroup=d[i].freigegeben;
            switch(lastgroup) {
              case "0": $f.append("<li class='list-group-item'><h4><span class='glyphicon glyphicon-eye-close'></span> Nicht freigegeben</h4></li>"); break;
              case "1": $f.append("<li class='list-group-item'><h4><span class='glyphicon glyphicon-eye-open'></span> Freigegeben</h4></li>"); break;
              case "3": $f.append("<li class='list-group-item'><h4><span class='glyphicon glyphicon-ok'></span> Beantwortet</h4></li>"); break;
            }
          }
          var $item = $("<li class='list-group-item'>").text(d[i].frage).attr('data-id', d[i].id);
          $item.prepend('<b><span class="label upvotes">'+d[i].upvotes+'</span></b>\
             <span class="pull-right"> <button class="btn btn-default btn-xs freigabe"></button> \
             <button class="btn btn-xs btn-danger delete"><span class="glyphicon glyphicon-trash"></span></button></span>');
          $item.append('<p class=anm>Anmerkung: <span></span></p>');
          $item.find('.anm span').text(d[i].anmerkung);
          $f.append($item);
          if (d[i].freigegeben=="1") {
            $item.find('.freigabe').html('beantwortet'); $item.find('.upvotes').addClass('label-primary');
          } else if (d[i].freigegeben=="3") {
            $item.find('.freigabe').html('verstecken'); $item.find('.upvotes').addClass('label-success');
          } else {
            $item.find('.freigabe').html('freigeben'); $item.find('.upvotes').addClass('label-primary');
          }
          $item.attr('freigabe', d[i].freigegeben);
        }
        
        $('.anm').click(function() {
          var $item = $(this).closest("li");
          var anm = $item.find(".anm span").text();
          anm = prompt("Anmerkung", anm);
          if (!anm) return;
          $.post("admin.php", { "id": $item.attr("data-id"), "anmerkung": anm });
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
    $("#reload,#autoreload").click(loadQuestions);
  });
  
</script>
</body></html>
