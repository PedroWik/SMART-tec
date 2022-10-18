<?php
require_once "../../conexao/conexao.php";
require_once "../../conexao/usuario.php";
$u = new usuarios;

session_start();
$id_user = $_SESSION['id_user'];

if (isset($_GET['user'])) {
    $user = addslashes($_GET['user']);
    $sql = mysqli_query($link, "SELECT * FROM usuarios WHERE code_nome='$user'");
    $row = mysqli_fetch_assoc($sql);
    $_SESSION['id_destino'] = $row['id_user'];

    $u->conectar("glou","localhost","root","");         
    if ($u->marcar_sms_visto($user,$id_user)){
    }
    
}
$u->conectar("glou","localhost","root","");         
if ($u->contar_sms_nao_lido_todo($id_user)){
}

$id_destino = $_SESSION['id_destino'];

if (!isset($_SESSION['id_user'])) {
    header("location: ../../..");
}
$u->marcar_sms_visto($id_destino, $id_user);
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/css.css">
    <link rel="stylesheet" href="../../css/chat.css">
    <link rel="stylesheet" href="../../<?=$_SESSION['stilo']?>/css.css">
    <link rel="stylesheet" href="../../<?=$_SESSION['stilo']?>/chat.css">
    <title>Document</title>
</head>
<body>
<div>
        <div id="cabecalho">
        <?php include "../atalhos/estado.php"; ?>
        <h1>SMART-TEC</h1>
        <form method="GET" action="procurar.php">
            <table id="pesquisa">
                <tr>
                    <td><h1>GLOU</h1></td>
                    <td><input type="search" name="pesquisar" id=""></td>
                    <td><input type="submit" value="pesquisar"></td>
                </tr>
            </table>
        </form>
            <ul type="none">
                <li><a href="../">pagina inicial</a></li>
                <li><a href="../perfil/">perfil</a></li>
                <li><a href="./">conversa <span style="color: yellow;"><?=$_SESSION['sms_nao_lido_todo']?></span></a></li>
                <li><a href="../usuarios.php">usuarios</a></li>
                <li><a href="../grupo/">grupos</a></li>
            </ul>
        </div>
    </div>
    <style>
            #img_sms{
                width: 100px;
            }
        </style>
            <div id="user_dest">
                <?php
                $perfil = mysqli_query($link, "SELECT * FROM usuarios WHERE id_user='$id_destino'");
                $perfill = mysqli_fetch_assoc($perfil);
                
                $foto = mysqli_query($link, "SELECT * FROM imagens WHERE id_user='$id_destino'");
                $fotoo = mysqli_fetch_assoc($foto);
                if (empty($fotoo['indereco'])) {
                    $fotoo['indereco'] ="sem_foto.png";
                }
                ?>
                <div id="img"><img src="../../img_user/perfil/<?=$fotoo['indereco']?>" alt=""></div>
                <p><?=$perfill['nome']?></p>
            </div>        
  <div id="conversa">
        <?php
        $conversa = mysqli_query($link, "SELECT * from `bate_papo`");
        while ($conv = mysqli_fetch_assoc($conversa)){
            if ($conv['id_user'] == $id_user) {
                $estado = "direita";
            } else {
                $estado = "esquerda";
            }
            if ((($conv['id_user'] == $id_user) && ($conv['id_user_dest'] == $id_destino)) || (($conv['id_user'] == $id_destino) && ($conv['id_user_dest'] == $id_user))) {
                ?>
                <div id="<?=$estado?>" class="sms">
                    <p><?=$conv['texto']?></p>
                    <?php
                    if ($conv['indereco'] != "none") {
                        ?>
                        <img id="img_sms" src="../../img_user/sms/<?=$conv['indereco']?>" alt="">
                        <?php
                    }
                    ?>
                    <p id="data"><?=$conv['data']?></p>
                </div>
                <?php
            }
            
        }
            ?>
    </div>
   <div id="caixa_de_texto">
    <form method="POST" enctype="multipart/form-data">
        <div>
        <style>
            #img_sms{
                width: 80%;
                margin-left: 9%;
            }
        </style>
            <textarea name="texto" id="" cols="35" rows="5"></textarea>
            <br><input type="submit" value="enviar">
            <br><input type="file" name="pic" accept="image/*">
        </div>
        <?php
            #fazendo uma publicação no site~
            if (isset($_POST['texto'])) {
                $id_user = $_SESSION['id_user'];
                $texto = addslashes($_POST['texto']);

                $ext = strtolower(substr($_FILES['pic']['name'],-4)); //Pegando extensão do arquivo
                $nome_img = date("Y.m.d-H.i.s") . $ext; //Definindo um novo nome para o arquivo
                $dir = '../img_user/sms/'; //Diretório para uploads

                if (empty($ext)) {
                    $imagem = "none";
                }else {
                    $imagem = $nome_img;
                }
                if (!empty($texto) || !empty($imagem)) {

                    $u->conectar("glou","localhost","root","");
                    if ($u->msgErro == "") {
                        if ($u->enviar_sms($id_user,$id_destino,$texto,$imagem)) {
                            move_uploaded_file($_FILES['pic']['tmp_name'], $dir.$nome_img); //Fazer upload do arquivo
                        } else {
                            echo "erro";
                        }
                        
                    } else {
                        echo "erro: $u->msgErro";
                    }
                    
                } else {
                    echo "preencha todos os campos";
                }
                
            }
            ?>
    </form>
   </div> 
   <footer id="rodape">
<p>copyring  &copy; 2021 by pedro manuel</p>
<p><a href="http://free.facebook.com">facebook</a> / <a href="http://instagram.com">instagram</a></p>
</footer>   
</body>
</html>