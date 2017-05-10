<?php
$subscription = json_decode(file_get_contents('php://input'), true);
include_once "../config.php";


$endpoint = (string)$subscription['endpoint'];
$token = (string) $subscription['token'];
$key = (string) $subscription['key'];

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'POST':
  // create a new subscription entry in your database (endpoint is unique)
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "INSERT INTO $tblname (endpoint, token,public_key)
  VALUES ('$endpoint', '$token','$key')";

  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
  break;
  case 'PUT':
  // update the key and token of subscription corresponding to the endpoint
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "UPDATE $tblname SET token='$token', public_key='$key' WHERE endpoint='$endpoint'";

  if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
  } else {
    echo "Error updating record: " . $conn->error;
  }

  $conn->close();

  break;
  case 'DELETE':
  // delete the subscription corresponding to the endpoint
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $sql = "DELETE FROM $tblname WHERE endpoint='$endpoint'";

  if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
  } else {
    echo "Error deleting record: " . $conn->error;
  }

  $conn->close();
  break;
  default:
  echo "Error: method not handled";
  return;
}
