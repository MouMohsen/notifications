<?php
require __DIR__ . '/../vendor/autoload.php';
use Minishlink\WebPush\WebPush;
include_once "../../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
  $myObj->title = $_POST["title"];
  $myObj->message = $_POST["message"];

  $payload = json_encode($myObj);

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT endpoint, token, public_key FROM $tblname";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $auth = array(
      'VAPID' => array(
        'subject' => 'https://enty.tv/',
        'publicKey' => $publicKey,
        'privateKey' => $privateKey,
      ),
    );
    $webPush = new WebPush($auth);
    while($subscription = $result->fetch_assoc()) {
      $res = $webPush->sendNotification(
        $subscription["endpoint"],
        $payload,
        $subscription["public_key"],
        $subscription["token"]
      );
    }
    $webPush->flush();
  } else {
    echo "No Endpoints found";
  }

  $conn->close();
  //Redirect
  header('Location: ' . $_SERVER['HTTP_REFERER']);

}
