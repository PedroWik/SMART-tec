<?php
require_once '../../conexao/usuario.php';
require_once '../../conexao/conexao.php';
$u = new usuarios;

session_start();
if (isset($_GET['id_cdm'])) {
  $id_acdm = $_GET['id_cdm'];
  $_SESSION['id_cdm'] = $id_acdm;
}

if (!isset($_SESSION['id_cdm'])) {
  header("location: ../"); 
}
if (!isset($_SESSION['id_user'])) {
  header("location: ../../"); 
}
$id_user = $_SESSION['id_user'];
$id_cdm = $_SESSION['id_cdm'];

$sql = mysqli_query($link, "SELECT * FROM academia WHERE id_cdm = '$id_cdm'");
$row = mysqli_fetch_assoc($sql);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/academia/painel.css">
    <title>Painel</title>
</head>
<body>
    
    <nav>
      <img src="" alt="">
      <ul type='none'>
        <li><a href="painel.php">menu principal</a></li>
        <li><a href="painel.php">conteudos</a></li>
        <li><a href="painel.php">analise de popularidade</a></li>
        <li><a href="painel.php">visitantes</a></li>
      </ul>
    </nav>
    <div id="main">
      <div align='center'><?=$row['nome']?></div>
      <div id="info">
        <section>
          <p>visitantes <span></span></p>
          <p>participantes <span></span></p>
          <p>participantes fies</p>
          <p>colaboradores <span></span></p>
          <p>conta remoneravel <span></span></p>
        </section>
        <aside>
          <fieldset><legend>Conteudos <span></span></legend>
            <p>curtidas <span></span></p>
            <p>partilhas <span></span></p>
          </fieldset>
        </aside>
      </div>
      <div id="comentarios"></div>
      <footer></footer>
    </div>
</body>
</html>