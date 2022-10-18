<?php
require_once "../conexao/conexao.php";
require_once "../conexao/usuario.php";
$u = new usuarios;

session_start();
if (!isset($_SESSION['id_user'])) {
    header("location: ..");
}
$id_user =$_SESSION['id_user'];
if (!isset($_GET['pesquisar'])) {
    $_GET['pesquisar'] = $_SESSION['pesquisar'];
}else{
    $_SESSION['pesquisar'] = $_GET['pesquisar'];
}
$pesquisar = $_SESSION['pesquisar'];
$u->conectar("glou","localhost","root","");         
if ($u->contar_sms_nao_lido_todo($id_user)){
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/user.css">
    <link rel="stylesheet" href="../css/css.css">
    <link rel="stylesheet" href="../<?=$_SESSION['stilo']?>/user.css">
    <link rel="stylesheet" href="../<?=$_SESSION['stilo']?>/css.css">
    <title>Document</title>
</head>
<body>
    <div id="cabecalho">
    <?php include "atalhos/estado.php"; ?>
        <h1>SMART-TEC</h1>
        <form method="GET" action="">
            <table id="pesquisa">
                <tr>
                    <td><h1>GLOU</h1></td>
                    <td><input type="search" name="pesquisar" placeholder="<?=$pesquisar?>"></td>
                    <td><input type="submit" value="pesquisar"></td>
                </tr>
            </table>
        </form>
        <ul type="none">
            <li><a href="index.php">pagina incial</a></li>
            <li><a href="perfil/">perfil</a></li>
            <li><a href="conversa/">conversa <span style="color: yellow;"><?=$_SESSION['sms_nao_lido_todo']?></span></a></li>
            <li><a href="usuarios.php">usuarios</a></li>
            <li><a href="grupo/">grupos</a></li>
        </ul>    
    </div>   
    <?php
    if (empty($pesquisar)) {
        ?>
        <div>
            Digite alguma coisa na caixa de pesquisa
        </div>
        <?php
    } 
    if ($pesquisar != "") {   
        ?> 
        <div>
            <h2 style="text-align: center;">usuarios</h2>
            <?php
            $busca_u = mysqli_query($link, "SELECT * FROM usuarios WHERE nome LIKE '%$pesquisar%' LIMIT 7");
            while($user = mysqli_fetch_assoc($busca_u)){
                $id = $user['id_user'];
                $qry = mysqli_query($link, "SELECT * from imagens where id_user='$id'");
                $nome = mysqli_fetch_assoc($qry);
                
                $_SESSION['indereco'] = $nome['indereco'];
                if (!isset($_SESSION['indereco'])) {
                    $_SESSION['indereco'] ="sem_foto.png";

                }                   
                ?>
                <div id="users">
                    <table id="usuarios">        

                        <td id="img">
                            <img src="../img_user/perfil/<?=$_SESSION['indereco']?>">
                        </td>
                        <td id="nome">
                        <a class="link" href="perfil/perfil_user.php?user=<?=$user['code_nome']?>">
                                <?=$user['nome']?>
                            </a>
                            
                        </td>
                        <td>
                            <?php
                                $ver_a = mysqli_query($link, "SELECT * from `amigo` WHERE id_user='$id' AND id_dest='$id_user'");
                                $very_a = mysqli_fetch_assoc($ver_a);

                                $ver_p = mysqli_query($link, "SELECT * from `pedido` WHERE id_user='$id_user' AND id_dest='$id' AND de='amizade'");
                                $very_p = mysqli_fetch_assoc($ver_p);

                            if (empty($very_a['id_amigo']) && empty($very_p['id_pdd']) && ($id_user != $user['id_user'])) {
                                ?>
                                <form method="POST">
                                    <input type="number" name="id_desti" style="display: none;" value="<?=$id?>">
                                    <button>adicionar amigo</button>
                                </form>
                                <div>
                                    <?php
                                    }
                                    if (isset($_POST['id_desti'])) {
                                        $id_dest = addslashes($_POST['id_desti']);
                                        $de = "amizade";
                                        if (!empty($id_user) && !empty($id_dest)) {
                                            
                                            $u->conectar("glou","localhost","root","");
                                            if ($u->msgErro == "") {
                                                if ($u->pedido_de_amizade($id_user,$id_dest,$de)) {
                                                    header("location: procurar.php");
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
                                <?php
                            
                            ?>
                        </td>
                    </table>
                </div>
                <?php
            }
            ?>       
        </div>
        <div id="grupos">
            <h2 style="text-align: center;">grupos</h2>
            <?php
            $grupos = mysqli_query($link, "SELECT * FROM grupos WHERE nome LIKE '%$pesquisar%' LIMIT 5");
            while ($grupo = mysqli_fetch_assoc($grupos)) {
                ?>
                <div><p><?=$grupo['nome']?></p></div>
                <?php
            }
            ?>
        </div>
        <div id="esquerdo">
            <h2 style="text-align: center;">Publicações</h2>
            <div>
                <?php
                $sql = mysqli_query($link, "SELECT * from publicacoes WHERE texto LIKE '%$pesquisar%' LIMIT 5");
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
                                $_SESSION['indereco'] = $nome['indereco'];
                                if (!isset($_SESSION['indereco'])) {
                                    $_SESSION['indereco'] ="sem_foto.png";
                                }                   
                                ?>

                            </div>
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
                                    width: max-content;
                                    font-size: 13pt;
                                    color: blue;
                                }
                            </style>
                            <table id="usuarios">        

                                <td id="img">
                                    <img src="../img_user/perfil/<?=$_SESSION['indereco']?>">
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
                                if ($row['indereco'] == "none") {
                                        # code...
                                } else {
                                    ?>
                                    <style>
                                        #pbl{
                                            width: 150px;
                                        }
                                    </style>
                                    <img id="pbl" src="../img_user/historia/<?=$row['indereco']?>" alt="">
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
                ?>
            </div>
            <?php
        }
        ?>
    </body>
    <footer id="rodape">
<p>copyring  &copy; 2021 by pedro manuel</p>
<p><a href="http://free.facebook.com">facebook</a> / <a href="http://instagram.com">instagram</a></p>
</footer> 
    </html>