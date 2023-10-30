<?php
$username = filter_input(INPUT_POST, 'username');
$username = html_entity_decode($username, ENT_COMPAT, "UTF-8");
$password = filter_input(INPUT_POST, 'password');
$password = html_entity_decode($password, ENT_COMPAT, "UTF-8");

if (!empty($username)){
if (!empty($password)){
$host = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "bithacks";
// Create connection
$conn = new mysqli ($host, $dbusername, $dbpassword, $dbname);


if (mysqli_connect_error()){
die('Connect Error ('. mysqli_connect_errno() .') '
. mysqli_connect_error());
}
else{
$sql = "INSERT INTO studentinfo (title, content)
values ('$username','$password')";
if ($conn->query($sql)){
echo "New record is inserted sucessfully";
}
else{
echo "Error: ". $sql ."
". $conn->error;
}
}
}
else{
echo "Password should not be empty";
die();
}
}
else{
echo "Username should not be empty";
die();
}
$conn->close();

?>