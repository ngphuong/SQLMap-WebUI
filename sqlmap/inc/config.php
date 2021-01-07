<?php
  // API URL to Connect to, default: http://127.0.0.1:80/
  define('API_URL', 'http://127.0.0.1:80/');

  // Path to where the core SQLMAP python files
  define('SQLMAP_BIN_PATH', '/Users/anhphuong/tools/sqlmap/');

  // Path to SQLMAP's Default Output Directory
  define('SQLMAP_OUTPUT_PATH', '/Users/anhphuong/.sqlmap/output/');

  // Define where to write our local scan file
  define('TMP_PATH', '/tmp/sqlmap/');

  // Path to the local Metasploit directory
  //define('MSF_PATH', '/Users/anhphuong/tools/msf/');

  // config database 
  $servername = "localhost";
  $database = "SqlWeb";
  $username = "root";
  $password = "";

  // Create connection
  $conn = mysqli_connect($servername, $username, $password, $database);

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
  echo "Connected successfully";

  // Admin Username & Password
  // *For future admin panel to flush and kill scan tasks....
  define('ADMIN_USER', 'admin');
  define('ADMIN_PASS', 'admin');

?>
