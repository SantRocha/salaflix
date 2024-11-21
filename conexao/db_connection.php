<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "Root123$";
    $dbname = "sgs";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        die("Falha na conexão: " . $e->getMessage());
    }
/*
    $servername = "127.0.0.1";
    $username = "root";
    $password = "Root123$";
    $dbname = "sgs";


    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $conn->set_charset("utf8");*/
?>
