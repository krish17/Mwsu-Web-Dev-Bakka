<?php

  $DB_host = "localhost";
  $DB_user = "web-dev";
  $DB_pass = "mwsumustangsmwsu";
  $DB_name = "web-dev";
  
  $conn = new MySQLi($DB_host,$DB_user,$DB_pass,$DB_name);
    
     if($conn->connect_errno)
     {
         die("ERROR : -> ".$conn->connect_error);
     }

?>