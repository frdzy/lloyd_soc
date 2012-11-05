<?php
// Change USERNAME and PASSWORD
function lloyd_db_connect() {
  $dbh = new PDO("mysql:host=localhost;dbname=lloyd", "USERNAME", "PASSWORD");
  $dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  return $dbh;
}
?>
