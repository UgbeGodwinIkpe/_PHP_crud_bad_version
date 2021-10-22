<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$title = $_POST['title'];
$description = $_POST['description'];
$price = $_POST['price'];
$date = Date('Y-m-d H:i:s');

$statement = $pdo->prepare("INSERT INTO products (title, descript, images, price, create_date)
             VALUES(:title, :descript, :images, :price, :create_date)
          ");
$statement->bindValue(':title', $title);
$statement->bindValue(':descript', $description);
$statement->bindValue(':images', '');
$statement->bindValue(':price', $price);
$statement->bindValue(':create_date', $date);
$statement->execute();
