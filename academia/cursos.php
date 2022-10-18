<?php
require_once '../conexao/usuario.php';
$u = new usuarios;
?>

<?php
  session_start();
  if (!isset($_SESSION['id_user'])) 
  {
    header("location: ../"); 
  }
?>
<html>
<head>
<title>academia</title>
<link rel="stylesheet" href="stilo.css"/>
<link rel="stylesheet" href="../../bootstrap.css"/>

<meta charset="UTF-8"/>

</head>
<body id="corpo">
    <h1>academia</h1>
    <form method="">
  <input type="serch" id="psq" placeholder="Conteudos gratis"> 
  <input type="submit" value="pesquisar" id="btnpsq">
    </form>
        <nav id="cab">
            <ul type="none" id="selec">
        <li><a href=".php">cursos gratis</li></a>
        <li><a href=".php">cursos pagos</li></a>
        <li id="acad"><a href="./">pagina inicial</li></a>
            </ul>
        </nav>  
<section>
  <div id="comentario">        
    <form method="POST">
      <textarea name="pbl" id="pblc" cols="45" rows="5" placeholder="deixe aqui o seu comentario"></textarea>
      <input id="btn" type="submit" value="comentar">
    </form>
  </div>
</section>
<aside>

</aside>
  <footer id="rodape">
<p>copyring  &copy; 2021 by pedro manuel</p>
<p><a href="http://free.facebook.com">facebook</a> / <a href="http://instagram.com">instagram</a></p>
</footer> 
</body>
</html>