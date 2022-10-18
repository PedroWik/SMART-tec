<?php
global $pdo;
$codigo = "";
$usuario = "root";
$nome = "glou";
$local = "localhost";


$conexao = new mysqli($local,$usuario,$codigo,$nome);
$pdo = new PDO("mysql:dbname=".$nome.";host=".$local,$usuario,$codigo);
$link = mysqli_connect($local,$usuario,$codigo,$nome); 
?>
