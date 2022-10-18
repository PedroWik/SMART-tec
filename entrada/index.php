<?php
require_once "../conexao/conexao.php";
require_once "../conexao/usuario.php";
$u = new usuarios;

session_start();
if (!isset($_SESSION['id_user'])) {
    header("location: ..");
}
$_SESSION['estado'] = "inicio";

$id_user = $_SESSION['id_user'];
$limit = 0;

$u->conectar("glou","localhost","root","");         
if ($u->contar_sms_nao_lido_todo($id_user)){}

if (isset($_GET['tema'])) {
    switch ($_GET['stilo']) {
    case "1":
        $_SESSION['stilo'] = 'css/css_comum';
        break;
    case "2":
        $_SESSION['stilo'] = 'css/css_claro';
        break;
    case "3":
        $_SESSION['stilo'] = 'css/css_dark';
        break;
    case "4":
        $_SESSION['stilo'] = 'css/css_azul';
        break;
    }   
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/css.css">
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
                <td>
                <form class="stilo" method="GET">
				<input type="hidden" name="tema" value="" />
				<select name="stilo" onchange="submit();">
					<option value="1" <?php if($_SESSION['stilo']=='css/css_comum'){ echo "selected"; } ?>>Tema comum</option>
					<option value="2" <?php if($_SESSION['stilo']=='css/css_claro'){ echo "selected"; } ?>>Tema claro</option>
                    <option value="3" <?php if($_SESSION['stilo']=='css/css_dark'){ echo "selected"; } ?>>Tema escuro</option>
                    <option value="4" <?php if($_SESSION['stilo']=='css/css_azul'){ echo "selected"; } ?>>Tema azul</option>
				</select>
			</form>
                </td>
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
            <li><a href="index.php">pagina incial</a></li>
            <li><a href="perfil/">perfil</a></li>
            <li><a href="ativos.php">ativos<span style="color: yellow;"><?=$num_online?></span></a></li>
            <li><a href="conversa/">conversa <span style="color: yellow;"><?=$_SESSION['sms_nao_lido_todo']?></span></a></li>
            <li><a href="usuarios.php">usuarios</a></li>
            <li><a href="grupo/">grupos</a></li>
            <li><a href="video.php">videos</a></li>
            <li><a href="../academia/">academia</a></li>
        </ul>    
    </div>
    <div id="corpo">
        <div id="direito">
        <div id="area_publicar">
                <form method="POST">
                    <select name="tipo" id="">
                        <option value="normal">de rotina</option>
                        <option value="pergunta">pergunta</option>
                    </select>
                    <select name="visibilidade" id="">
                        <option value="publico">publico</option>
                        <option value="privado">só amigos</option>
                    </select>
                    <?php
                    $_SESSION['estado_p'] = "publicar";
                    ?>
                    <li><a href="carregamento/publicar/publicar.php">adicionar foto</a></li>
                    <div>
                        <textarea placeholder="em que estas pensando?" name="texto" id="" cols="50" rows="6"></textarea><br>
                        <input type="submit" value="publicar">
                    </div>
                    
                </form>
                <?php
            if (isset($_POST['texto'])) {
                $id_user = $_SESSION['id_user'];
                $tipo = addslashes($_POST['tipo']);
                $texto = addslashes($_POST['texto']);
                $visibilidade = addslashes($_POST['visibilidade']);
                $video = "none";
                $id_grupo = "0";
                    $imagem = "none";
                if (!empty($tipo) && !empty($texto) && !empty($imagem)) {
                    $u->conectar("glou","localhost","root","");
                    if ($u->msgErro == "") {
                        if ($u->publicar($id_user,$tipo,$texto,$imagem,$video,$visibilidade,$id_grupo)) {
            
                            header("location: index.php");
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
            <div id="demo"></div>
        </div>
        <div id="esquerdo">
            <div>
                <?php
                    $sql = mysqli_query($link, "SELECT * from publicacoes ORDER BY id_pbl DESC");
                    while($row = mysqli_fetch_assoc($sql)){
                        $id = $row['id_user'];       
                        $id_pbl =$row['id_pbl'];

                        $pbl = mysqli_query($link,"SELECT * FROM pbl_visto WHERE id_pbl='$id_pbl' AND id_user='$id_user'");
                        $pbl_visto = mysqli_fetch_assoc($pbl);
                        $amigo['id_amigo'] = "existe";
                        if ($id != $id_user) {
                            $amg = mysqli_query($link, "SELECT id_amigo FROM amigo WHERE id_user='$id_user' AND id_dest='$id'");
                            $amigo = mysqli_fetch_assoc($amg);
                            if (isset($amigo['id_amigo'])) {
                                $amigo['id_amigo'] = "existe";
                            }
                        }
                        if ($row['id_grupo'] != 0) {
                            $id_grupo = $row['id_grupo'];
                            $amigo['id_amigo'] = "";

                            $very_g = mysqli_query($link, "SELECT * FROM grupos WHERE id_user='$id_user' AND id_grupo='$id_grupo'");
                            $very_gr = mysqli_fetch_assoc($very_g);
                            
                            if (isset($very_gr['id_grupo'])) {
                                $amigo['id_amigo'] = "existe";
                            }
                            $very_m = mysqli_query($link, "SELECT * FROM membros_grupo WHERE id_user='$id_user' AND id_grupo='$id_grupo'");
                            $very_me = mysqli_fetch_assoc($very_m);
                                 
                            if (isset($very_me['id_membro'])) {
                                $amigo['id_amigo'] = "existe";
                            }
                        }
                        
                    if (($amigo['id_amigo'] == "existe") && !isset($pbl_visto['id_pbl_visto']) && $limit < 5) {
                        $limit++;

                        if (empty($limit)) {#congelado para manutenção do site
                            $visto = ("INSERT INTO pbl_visto (id_user, id_pbl) VALUES ('$id_user', '$id_pbl')");
                            mysqli_query($conexao, $visto) or die ("erro ao processar dados");
    
                        }
                ?>
                <div id="corpo_pbl">
                    <div>
                       <?php

                        ?>
                    </div>
                    <div style="margin-bottom: 10px;">
                    <div>
                        <?php
                         $id = $row['id_user'];
                            $qry = mysqli_query($link, "SELECT * from usuarios where id_user='$id'");
                            if($qry){
                                if ($nome = mysqli_fetch_assoc($qry)){
                                    $user['nome'] = $nome['nome'];
                                    $user['code_nome'] = $nome['code_nome'];
                                }
                            }
                        ?>
                    </div>
                    <div>
                        <?php
                            $qry = mysqli_query($link, "SELECT * from imagens where id_user='$id'");
                            $nome = mysqli_fetch_assoc($qry);
                            $user['indereco'] = $nome['indereco'];
                            if (!isset($user['indereco'])) {
                                $user['indereco'] ="sem_foto.png";
                            }                   
                        ?>

                    </div>
                    <style>
                        #usuarios{
                            width: max-content
                        }
                        #img{
                            width: 36px;
                        }
                        #img img{
                            height: 45px;
                            border-radius: 50%;
                            width: 35px;
                        }
                        #nome{
                            height: max-content;
                            width: max-content;
                            font-size: 13pt;
                            color: blue;
                        }
                    </style>
                    <table id="usuarios">        

                        <td id="img">
                            <img src="../img_user/perfil/<?=$user['indereco']?>">
                        </td>
                            
                        <td id="nome">
                            <a class="link" href="perfil/perfil_user.php?user=<?=$user['code_nome']?>">
                                <?=$user['nome']?>
                            </a>
                            
                        </td>
                        <?php
                        $id_pbl = $row['id_pbl'];
                        $ver_gr = mysqli_query($link, "SELECT * from publicacoes where id_pbl='$id_pbl'");
                        $ver_g = mysqli_fetch_assoc($ver_gr);
                        if ($ver_g['id_grupo'] != 0) {
                            $id_grupo = $ver_g['id_grupo']; 
                            $nome_gr = mysqli_query($link, "SELECT * from grupos where id_grupo='$id_grupo'");
                            $nome_g = mysqli_fetch_assoc($nome_gr);
                            ?>
                            <td id="nome">
                            ><a class="link" href="grupo/grupo.php?user=<?=$nome_g['id_grupo']?>">
                                <?=$nome_g['nome']?>
                            </a>
                                
                            </td>
                            <?php
                        }
                        ?>
                    </table>
                        <div>
                            <p><?=$row['texto']?></p>
                            <?php
                            if ($row['indereco'] != "none") {
                             
                                ?>
                                <style>
                                    #pbl{
                                        width: 150px;
                                    }
                                </style>
                                <img id="pbl" src="../img_user/historia/<?=$row['indereco']?>" alt="">
                                <?php
                            }
                            if ($row['indereco_v'] != "none") {
                             
                                ?>
                                <style>
                                    #pbl{
                                        width: 150px;
                                    }
                                    video{
                                        width: 80%;
                                        height: 200px;
                                        background: black; 
                                        margin: auto;
                                    }
                                </style>
                                    <video id="video" controls="controls">
                                        <source src="../videos_user/historia/<?=$row['indereco_v']?>" type="video/mp4"/>
                                        <p>desculpe mais não foi possivel carregar o video</p>
                                    </video>
                                <?php
                            }
                            
                            ?>
                        </div>
                    </div>   
                    <?php          
                        $id_pbl =$row['id_pbl'];
                        include "atalhos/reacao.php";
                    ?>  
                </div>
                <?php
                    }
                }
                ?>
            </div>
            <div>
             <?php
               
                        
            ?>
            </div>    
        </div>
    </div>
    <footer id="rodape">
<p>copyring  &copy; 2021 by pedro manuel</p>
<p><a href="http://free.facebook.com">facebook</a> / <a href="http://instagram.com">instagram</a></p>
<p><li><a href="..">terminar sessão</a></li></p>
<p><li><a href="config/"><img src="../img/config.png" alt=""> definições</a></li></p>
</footer> 
</body>
</html>