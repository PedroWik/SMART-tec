<?php
require_once '../conexao/usuario.php';
require_once '../conexao/conexao.php';
$u = new usuarios;

session_start();
if (!isset($_SESSION['id_user'])) 
{
  header("location: ../../"); 
}
 $id_user = $_SESSION['id_user'];
?>
<html>
<head>
<title>academia</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/academia/stilo.css"/>
<meta charset="UTF-8"/>

</head>
<body id="corpo">
    <h1>academia</h1>

        <nav id="cab">
            <ul type="none" id="selec">
              <?php
              $sql = mysqli_query($link, "SELECT * FROM academia WHERE id_user='$id_user'");
              $row = mysqli_fetch_assoc($sql);
              if (!isset($row['id_cdm'])) {
                ?>
                <li><a href="criador/">criar academia</li></a>
                <?php
              } else {
                ?>
                <li><a href="criador/painel.php?id_cdm=<?=$row['id_cdm']?>"><?=$row['nome']?></li></a>
                <?php
              }
              ?>
              
              <li id="acad"><a href="../entrada">voltar a SMART-tec</li></a>
            </ul>
        </nav>  
  <form method="">
    <input type="serch" id="psq" placeholder="Conteudos gratis"> 
    <input type="submit" name="btn" value="pesquisar" id="btnpsq">
  </form >

<footer id="rodape">
<p>copyring  &copy; 2021 by pedro manuel</p>
<p><a href="http://free.facebook.com">facebook</a> / <a href="http://instagram.com">instagram</a></p>
</footer> 
</body>
</html>