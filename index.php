<?php
require "init.php";
show_header("Fragen");
?>
    <div class="container">
    <div class="row">
    
    <form id="ask">
      <div class="form-group">
        <label for="exampleInputEmail1">Stelle hier deine Frage</label>
        <input type="text" class="form-control" id="question" placeholder="Frage">
      </div>
      
      
      <button type="submit" class="btn btn-primary">Frage stellen</button>
    </form>
      <br><br>
<p class=text-muted>Hinweis: Die Fragen werden vor der Anzeige moderiert.</p>
<p class=text-muted>Hinweis 2: Die untenstehenden Antworten wurden nach <i>Bestem Wissen Und Gewissen™</i> teils mitgeschrieben, teils von Helfern auf der VV selbst gegeben - dabei kann es natürlich immer zu Missverständnissen, Fehlern und Irrtümern kommen. Für wirklich belastbare und verbindliche Aussagen lieber im Studienbüro oder bei der Fachstudienberatung nachfragen.</p>
      
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
        if (result.run) eval(result.run);
        if (result.data == false) return;
        lastchange = result.timestamp;
        var d = result.data;
        var $f = $("#fragen").html("");
        var lastgroup;
        for(var i in d) {
          if (lastgroup!=d[i].freigegeben) {
            lastgroup=d[i].freigegeben;
            switch(lastgroup) {
              case "1": $f.append("<li class='list-group-item'><h4><span class='glyphicon glyphicon-question-sign'></span> Neue Fragen</h4></li>"); break;
              case "3": $f.append("<li class='list-group-item'><h4><span class='glyphicon glyphicon-ok'></span> Beantwortete Fragen</h4></li>"); break;
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
    
    $("#ask").submit(function() {
      if ($("#question").val() === '') return;
      $.post("api.php", { question: $("#question").val() }, function(x) {
        $("#question").val("");
      });
    });
    
    loadQuestions();
    
    setInterval(loadQuestions,7000);
  });
  
</script>


</body></html>
