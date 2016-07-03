<?php
require "init.php";
show_header("d120.de/fragen ---- Beamer");
?>
<style>
#fragen div { padding: 10px; font-size: 14pt; border-bottom: 1px solid #aaa; min-height: 80px; }
#fragen div .beamer-upvotes { float: left; font-size: 24pt; width: 60px; text-align: right; padding-right: 15px; }
p { font-weight: bold; }
</style>
    <div style="position:fixed;  top: 0; width: 100%; background-color: #222; color: #fff; text-align: center; font-weight: bold; z-index: 10000;">
<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=http%3A//d120.de/fragen&chld=L|2" align=right style="margin:10px">
       <div style="padding: 10px 30px; font-size: 6em;">d120.de/fragen</div>
    </div>

    <div id="fragen" style="margin-top:140px">
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
        for(var i in d) {
          var $item = $("<div>").text(d[i].frage)
          $item.prepend(" <span class='beamer-upvotes'>"+(d[i].freigegeben==3 ? '<span class="glyphicon glyphicon-ok"></span>' : d[i].upvotes) +"</span> ");
          if (d[i].anmerkung) {
            $item.append("<p><small>" +
                         d[i].anmerkung + "</small></p>");
          }
          $f.append($item);
        }
      }, "json");
    }
    loadQuestions();
    setInterval(loadQuestions,7000);
  });
</script>


</body></html>
