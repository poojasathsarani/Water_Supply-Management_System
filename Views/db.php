<?php

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "water_supply_management_system";
$conn = "";

$conn = mysqli_connect($db_server,
                       $db_user,
                       $db_pass,
                       $db_name

                            );

if($conn){
    echo'';
}
else{
    echo"you are not connected";
}


?>