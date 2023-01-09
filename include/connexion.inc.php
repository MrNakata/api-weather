<?php
$host_db = 'mysql:host=localhost;dbname=weather_city';
$login   = 'root'; 
$mdp     = ''; 
$option  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
$pdo     = new PDO($host_db, $login, $mdp, $option);