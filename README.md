
Erstelle eine Datei namens .htconfig.php, kopiere folgenden Code rein und passe die Werte entsprechend an:
````
<?php
$DB_HOST="localhost";
$DB_NAME="fragedb";
$DB_USER="frageuser";
$DB_PASS="hunter2";
$password="geheimes_adminpasswort_hier";
````

Lege in der oben eingestellten Datenbank folgende Tabelle an:
````
-- --------------------------------------------------------
-- Exportiere Struktur von Tabelle tools.fragen
CREATE TABLE IF NOT EXISTS `fragen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frage` varchar(400) NOT NULL,
  `upvotes` int(11) NOT NULL,
  `eindat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `freigegeben` tinyint(4) NOT NULL,
  `ipaddr` varchar(70) NOT NULL DEFAULT '0',
  `anmerkung` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
````

