<?php
require "init.php";
include "header.php";
?>
    <div class="container">
    <div class="row">
    
    
      <div class="form-group">
        <label for="exampleInputEmail1">Stelle hier deine Frage</label>
        <input type="text" class="form-control" id="question" placeholder="Frage">
      </div>
      
      
      <button type="submit" class="btn btn-primary" id="ask">Frage stellen</button>
      <br><br>
      
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
      $.get("api.php?read=fragen&since="+lastchange, function(result) {
        if (result == false) return;
        lastchange = result.timestamp;
        var d = result.data;
        var $f = $("#fragen").html("");
        var lastgroup;
        for(var i in d) {
          if (lastgroup!=d[i].freigegeben) {
            lastgroup=d[i].freigegeben;
            switch(lastgroup) {
              case "0": $f.append("<li class='list-group-item'><b>Nicht freigegeben</b></li>"); break;
              case "1": $f.append("<li class='list-group-item'><b>Freigegeben</b></li>"); break;
              case "3": $f.append("<li class='list-group-item'><b>Beantwortet</b></li>"); break;
            }
          }
          var $item = $("<li class='list-group-item'>").html(d[i].frage).attr('data-id', d[i].id);
          $item.prepend("<a href='#' class=' upvote'><span class='label label-default'>▲</span></a> <span class='label label-primary'>"+d[i].upvotes+"</span> ");
          if (d[i].freigegeben==3 || d[i].anmerkung) {
            $item.append("<p><small>" +
                         (d[i].freigegeben==3 ? "<span class='label label-success'>Beantwortet</span> " : "") +
                         d[i].anmerkung + "</small></p>");
          }
          $f.append($item);
        }
        
        $(".upvote").click(function() {
          $.post("api.php", { "upvote": $(this).closest("li").attr("data-id") }, function(x) {
            loadQuestions();
          });
        });
      }, "json");
      
    }
    
    $("#ask").click(function() {
      $.post("api.php", { question: $("#question").val() }, function(x) {
        $("#question").val("");
      });
    });
    
    loadQuestions();
    
    setInterval(loadQuestions,7000);
  });
  
</script>
</body></html>
