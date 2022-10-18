<?php
require_once "../conexao/conexao.php";
require_once "../conexao/usuario.php";
$u = new usuarios;

session_start();
if (!isset($_SESSION['id_user'])) {
    header("location: ..");
}
$id_user = $_SESSION['id_user'];
if (!isset($_SESSION['p_v'])) {
    $_SESSION['p_v'] = "amigos";
}

$u->conectar("glou","localhost","root","");         
if ($u->contar_sms_nao_lido_todo($id_user)){
}
?>
<html lang="pt">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/user.css">
    <link rel="stylesheet" href="../css/css.css">
    <link rel="stylesheet" href="../css/video.css">
    <link rel="stylesheet" href="../<?=$_SESSION['stilo']?>/video.css">
    <link rel="stylesheet" href="../<?=$_SESSION['stilo']?>/css.css">
    <script src="../js/js.js"></script>
    <title>GLOU</title>
</head>
<body>
    <div id="cabecalho">
    <?php include "atalhos/estado.php"; ?>
    <div id="cab">
            <table>
                <td><h1>SMART-TEC</h1></td>
                <td></td>
            </table>
        </div>
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
            <li><a href="./">pagina inicial</a></li>
            <li><a href="perfil/">perfil</a></li>
            <li><a href="conversa/">conversa <span style="color: yellow;"><?=$_SESSION['sms_nao_lido_todo']?></span></a></li>
            <li><a href="usuarios.php">usuarios</a></li>
            <li><a href="grupo/">grupos</a></li>
            <li><a href="video.php">videos</a></li>
        </ul>
    </div>
    <div id="pbl">
        <div >
            
            <form id="post_video" method="POST" enctype="multipart/form-data">
                <table>
                    <tr>
                       <select name="visibilidade" id="">
                            <option value="publico">publico</option>
                            <option value="privado">Só amigos</option>
                        </select>
                        <input class="file" type="file" name="pic" accept="video/*" class="form-control"> 
                    </tr>
                    <tr id="caixa">
                        <td id="texto">
                            <textarea name="texto" ></textarea>
                        </td>
                        <td id="btn">
                            <input type="submit" value="carregar">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div>
            <?php
            if (isset($_POST['texto'])) {
                $id_user = $_SESSION['id_user'];
                $tipo = "video";
                $texto = addslashes($_POST['texto']);
                $id_grupo = 0;
                $imagem = "none"; 
                $visibilidade = addslashes($_POST['visibilidade']);

                $ext = strtolower(substr($_FILES['pic']['name'],-4)); //Pegando extensão do arquivo
                $nome_video = date("Y.m.d-H.i.s") . $ext; //Definindo um novo nome para o arquivo
                $dir = '../videos_user/historia/'; //Diretório para uploads

                if (empty($ext)) {
                    $video = "none";
                }else {
                    $video = $nome_video;
                }
                if (!empty($tipo) && !empty($texto) && !empty($video)) {

                    $u->conectar("glou","localhost","root","");
                    if ($u->msgErro == "") {
                        if ($u->publicar($id_user,$tipo,$texto,$imagem,$video,$visibilidade,$id_grupo)) {
                            move_uploaded_file($_FILES['pic']['tmp_name'], $dir.$nome_video); //Fazer upload do arquivo
                            header("location: video.php");
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
        </div>
    </div>
    <div id="troca">
        <?php
        if ($_SESSION['p_v'] == "publico") {
            ?>
            <form method="post">
                <input type="text" name="troca" value="amigos" style="display: none;">
                <div>
                    <p><span>videos publicos  </span>|<button>  Videos de amigos</button></p>
                </div>
            </form>
            <?php
        }
        if ($_SESSION['p_v'] == "amigos") {
            ?>
            <form method="post">
                <input type="text" name="troca" value="publico" style="display: none;">
                <div>
                    <p><span>Videos de amigos  </span>|<button>  videos publicos</button></p>
                </div>
            </form>
            <?php
        }
        if (isset($_POST['troca'])) {
            $_SESSION['p_v'] = addslashes($_POST['troca']);
            header("location: video.php");
        }
        ?>
    </div>
    <?php
        if ($_SESSION['p_v'] == "publico") {
            ?>
            <div id="corpo">
                <?php
                $sql = mysqli_query($link, "SELECT * FROM publicacoes WHERE visibilidade ='publico' ORDER BY id_pbl DESC");
                while ($row =mysqli_fetch_assoc($sql)) {
                    $id = $row['id_user'];
                    $usuario = mysqli_query($link, "SELECT * FROM usuarios WHERE id_user ='$id'");
                    $user = mysqli_fetch_assoc($usuario);
                    if ($row['indereco_v'] != "none") {
                        ?>
                            <div id="corpo_video">
                                <div id="legenda"><?=$user['nome']?><br><?=$row['texto']?></div>
                                <div>
                                    <video id="video" controls="controls">
                                        <source src="../videos_user/historia/<?=$row['indereco_v']?>" type="video/mp4"/>
                                        <p>desculpe mais não foi possivel carregar o video</p>
                                    </video>
                                </div>
                            </div>
                        <?php
                    }         
                }
                ?>
            </div>
            <?php
        }
        if ($_SESSION['p_v'] == "amigos") {
            ?>
            <div id="corpo">
                <?php
                $sql = mysqli_query($link, "SELECT * FROM publicacoes ORDER BY id_pbl DESC");
                while ($row =mysqli_fetch_assoc($sql)) {
                    $id = $row['id_user'];
                    $usuario = mysqli_query($link, "SELECT * FROM usuarios WHERE id_user ='$id'");
                    $user = mysqli_fetch_assoc($usuario);

                    $amigo = mysqli_query($link, "SELECT * FROM amigo WHERE id_user ='$id_user' AND id_dest='$id'");
                    $amig = mysqli_fetch_assoc($amigo);
                    if (($row['indereco_v'] != "none") && (isset($amig['id_amigo']) || $id_user == $id)) {
                        ?>
                            <div id="corpo_video">
                                <div id="legenda"><?=$user['nome']?><br><?=$row['texto']?></div>
                                <div>
                                    <video id="video" controls="controls">
                                        <source src="../videos_user/historia/<?=$row['indereco_v']?>" type="video/mp4"/>
                                        <p>desculpe mais não foi possivel carregar o video</p>
                                    </video>
                                </div>
                            </div>
                        <?php
                    }         
                }
                ?>
            </div>
            <?php
        }
        ?>
    
</body>
</html>