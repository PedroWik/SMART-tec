<?php
require_once "../../conexao/conexao.php";
require_once "../../conexao/usuario.php";
session_start();

$u = new usuarios;

if (!isset($_SESSION['id_user'])) {
    header("location: ../../../");
}
$id_user = $_SESSION['id_user'];

?>
<html>
<head>
 <title>Upload de imagens</title>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<div class="container">
<h2><strong>Envio de imagens</strong></h2><hr>

<form method="POST" enctype="multipart/form-data">
  <label for="conteudo">Enviar imagem:</label>
  <input type="file" name="pic" accept="image/*" class="form-control">

  <div align="center">
    <button type="submit" class="btn btn-success">Enviar imagem</button>
  </div>
</form>
 
 <hr>
 <?php include "../atalhos/estado.php"; ?>
 <?php
 if(isset($_FILES['pic']))
 {
    $para = "user";
    $ext = strtolower(substr($_FILES['pic']['name'],-4)); //Pegando extensão do arquivo
    $nome_img ="IMG-".$id_user."-".date("Y.m.d-H.i.s") . $ext; //Definindo um novo nome para o arquivo
    $dir = './../../img_user/perfil/'; //Diretório para uploads
    

        $u->conectar('glou','localhost','root','');
        if ($u->carregar_imagem_p($id_user,$nome_img,$para)) {
            
           
            
            echo '<div class="alert alert-success" role="alert" align="center">
                  <img src="./../../img_user/perfil/' . $nome_img . '" class="img img-responsive img-thumbnail" width="200"> 
                  <br>
                  Imagem enviada com sucesso!
                  <br>
                  <a href="">
                  <button class="btn btn-default">voltar a pagina inicial</button>
                  </a></div>';
             move_uploaded_file($_FILES['pic']['tmp_name'], $dir.$nome_img); //Fazer upload do arquivo
        } else {
            echo "não enviado";
        }       
} 
?>

</div>
<body>
</html>