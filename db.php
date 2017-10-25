<?php
$usr="root";
$pswd="17021942";
$opt = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);
$db = new PDO('mysql:host=localhost;dbname=mafia;charset=UTF8;', $usr, $pswd,$opt);
$db->exec('SET NAMES utf8');