<?php
require_once "db_connect.php";

//REGISTER 
// Initialize curl session
$curl = curl_init();

$postData = array();
$playername = "";
if (isset($_POST["register"])) {
    if (!empty($_POST["playername"])){
        $playername = strtoupper($_POST["playername"]);
        // Data to be sent via POST for registration
        $postData = array(
            "symbol" => $playername,
            "faction" => "COSMIC",
        );
    }

    //Proof if player already exist here

    // Set curl options for a POST request
    curl_setopt($curl, CURLOPT_URL, "https://api.spacetraders.io/v2/register");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));

    //execute the curl session
    $response = curl_exec($curl);
    
    //convert response to PHP array
    $responseData = json_decode($response, true); 

    $token = "";
    // Check if the token is received
    if (isset($responseData['data'])) {
        // Save this token for future requests
        $token = $responseData['data']['token'];
    } else {
        // Handle error; token not received
        echo "Error: Token not received from API.";
        header("Location: /solarys/register.php");
    }

    //SET THE TOKEN
    // Set the token in the Authorization header
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
    ];

    // Initialize curl session
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.spacetraders.io/v2/my/agent');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    // Execute curl session
    $response = curl_exec($curl);

    // Close curl session
    curl_close($curl);

    //WRITE INTO DATABASE
    //check if player already exist
    $sql = "SELECT * FROM `players` WHERE `player_name` = '$playername'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 0) {
        //if not exist write it into the database
        $sql = "INSERT INTO `players`(`player_name`, `player_token`) VALUES ('$playername','$token')";
        $result = mysqli_query($conn, $sql);
        if($conn->query($sql) === true) {
            // here you set var for the session so you can reach the datas in the session
            $_SESSION["player"] = $token; 
        } 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 
</head>
<body>

<h1>Sign Up</h1>
    <div class="container">
        <div class="form">
            <form method="POST" enctype="multipart/form-data">
                <label>
                    <h3>Username</h3>
                    <input type="name" name="playername" class="form-control">
                </label>
                <div class="buttonForm">
                    <input type="submit" value="Register" name="register" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
</body>
</html>


