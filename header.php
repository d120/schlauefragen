<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Fragen</title>

    <link rel="stylesheet" href="https://static.luelistan.net/bootstrap-3.3.2-dist/css/bootstrap.css">
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <nav class="navbar navbar-default navbar-static-top orange-stripe">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#"><span class="hidden-xs">D120.de/</span>fragen</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar">
          <ul class="nav navbar-nav">
            <li class="<?= strstr($_SERVER["SCRIPT_FILENAME"],"index.php")?"active":""?>"><a href='.'>Unbeantwortete Fragen</a></li>
            <?php if(isset($_SESSION["login"])) { ?>
            <li class="<?= strstr($_SERVER["SCRIPT_FILENAME"],"admin.php")?"active":""?>"><a href='admin'>Admin</a></li>
            <li><a href='admin.php?logout=1'>Logout</a></li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </nav>
