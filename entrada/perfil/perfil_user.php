<?php
require_once "../../conexao/conexao.php";
require_once "../../conexao/usuario.php";
$u = new usuarios;

session_start();
if (!isset($_SESSION['id_user'])) {
    header("location: ../..");
}
$id_user = $_SESSION['id_user'];
$u->conectar("glou","localhost","root","");         
if ($u->contar_sms_nao_lido_todo($id_user)){
}
include "../atalhos/estado.php";
$user = $_GET['user'];
$sql = mysqli_query($link, "SELECT * FROM usuarios WHERE code_nome='$user'");
$row = mysqli_fetch_assoc($sql);
$id_user = $row['id_user'];
if ($id_user == $_SESSION['id_user']) {
    header("location: ./");
}
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/perfil.css">
    <link rel="stylesheet" href="../../css/css.css">
    <link rel="stylesheet" href="../../<?=$_SESSION['stilo']?>/perfil.css">
    <link rel="stylesheet" href="../../<?=$_SESSION['stilo']?>/css.css">
    <title>Document</title>
</head>
<body>
                    <style>
                        #usuarios{
                            width: 100%;
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
                            font-size: 13pt;
                            color: blue;
                        }
                    </style>
    <div id="cabecalho">
    <h1>SMART-TEC</h1>
        <form method="GET" action="../procurar.php">
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
            <li><a href="./">perfil</a></li>
            <li><a href="../conversa/">conversa <span style="color: yellow;"><?=$_SESSION['sms_nao_lido_todo']?></span></a></li>
             <li><a href="../usuarios.php">usuarios</a></li>
        </ul>    
    </div>
    <div>
        <div id="info">
            <div id="esquerda">
                <div>
                    <?php
                    $img = mysqli_query($link, "SELECT * from imagens WHERE id_user='$id_user'");
                    $nome_img = mysqli_fetch_assoc($img);
                    if (!isset($nome_img['indereco'])) {
                        $nome_img['indereco'] = "sem_foto.png";
                    }
                    ?>
                    <img src="../../img_user/perfil/<?=$nome_img['indereco']?>" alt="">
                </div>
            </div>
            <div id="direita">
                <form>
                    <ul type="none">
                        <li><button>usuarios chegados</button></li>
                    </ul>    
                </form>
            </div>
        </div>    
        <div>
            <div id="area_publicar">
                <form method="POST">
                    <select name="tipo" id="">
                        <option value="normal">de rotina</option>
                        <option value="pergunta">pergunta</option>
                    </select>
                    <div>
                        <textarea name="texto" id="" cols="50" rows="6"></textarea><br>
                        <input type="submit" value="publicar">
                    </div>
                    
                </form>
            </div>
            <?php

            ?>
                            <?php
                    $sql = mysqli_query($link, "SELECT * from publicacoes WHERE id_user='$id_user'");
                    while($row = mysqli_fetch_assoc($sql)){          
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
                                    $_SESSION['nome'] = $nome['nome'];
                                }
                            }
                            $img = mysqli_query($link, "SELECT * from imagens where id_user='$id'");
                            $nome = mysqli_fetch_assoc($img);
                            $_SESSION['indereco'] = $nome['indereco'];
                            if (!isset($_SESSION['indereco'])) {
                                $_SESSION['indereco'] ="sem_foto.png";
                            }   
                        ?>
                    </div>
                    <table id="usuarios">        

                        <td id="img">
                            <img src="../../img_user/perfil/<?=$_SESSION['indereco']?>">
                        </td>
                            
                        <td id="nome"><p style="float: left; font-size: 12pt;"><?=$_SESSION['nome']?></p></td>

                    </table>                       
                        <div>
                            <p><?=$row['texto']?></p>
                            <?php
                                if ($row['indereco'] == "none") {
                                        # code...
                                } else {
                                    ?>
                                    <style>
                                        #pbl{
                                            width: 150px;
                                        }
                                    </style>
                                    <img id="pbl" src="../../img_user/historia/<?=$row['indereco']?>" alt="">
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
                                            <source src="../../videos_user/historia/<?=$row['indereco_v']?>" type="video/mp4"/>
                                            <p>desculpe mais não foi possivel carregar o video</p>
                                        </video>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>               
                    <?php  
                        $pos = "../";        
                        $id_pbl =$row['id_pbl'];
                        include "../atalhos/reacao.php";
                    ?>   
                </div>
                <?php
                    }
                ?>

        </div>
    </div>
    <div id="historias">
        <?php
        $sql
        ?>
    </div>
    <footer id="rodape">
<p>copyring  &copy; 2021 by pedro manuel</p>
<p><a href="http://free.facebook.com">facebook</a> / <a href="http://instagram.com">instagram</a></p>
</footer> 
</body>
</html>