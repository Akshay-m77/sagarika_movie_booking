
<?php
 include("../apps/connect/db.php");

 $apiKey = 'my_secure_api_key';
$authHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
//  if ($authHeader !== $apiKey) {
//      header('HTTP/1.1 401 Unauthorized');
//      echo json_encode(array("message" => "Unauthorized access."));
//      exit();
//  }
?>
