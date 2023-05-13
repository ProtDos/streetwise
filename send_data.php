<?php

if (empty($_POST["name"])) {
    die("Name is required");
}

$mysqli = require __DIR__ . "/database_scores.php";

$sql = "INSERT INTO scores (name, score, time)
        VALUES (?, ?, ?)";
        
$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss",
                  $_POST["name"],
                  $_POST["score"],
                  $_POST["time"]);
                  
if ($stmt->execute()) {

    header("Location: play.php");
    exit;
    
} else {
    
    if ($mysqli->errno === 1062) {
        die("wtf");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}








