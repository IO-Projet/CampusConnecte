<?php
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=","","");
    } catch(PDOException $e) {
        echo $e -> getMessage();
    }
?>
