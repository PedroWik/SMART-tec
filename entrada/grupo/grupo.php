<?php
require_once "../../conexao/conexao.php";
require_once "../../conexao/usuario.php";
$u = new usuarios;

session_start();
if (!isset($_SESSION['id_user'])) {
    header("location: ..");
}
$_SESSION['estado'] = "grupo";
$id_user = $_SESSION['id_user'];
if (isset($_GET['user'])) {
    $_SESSION['id_grupo'] = addslashes($_GET['user']);
}

$id_grupo = $_SESSION['id_grupo'];
$limit = 0;

$u->conectar("glou","localhost","root","");         
if ($u->contar_sms_nao_lido_todo($id_user)){
}
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/css.css">
    <link rel="stylesheet" href="../../css/grupo.css">
    <link rel="stylesheet" href="../../<?=$_SESSION['stilo']?>/css.css">
    <link rel="stylesheet" href="../../<?=$_SESSION['stilo']?>/grupo.css">     
    <title>grupos</title>
</head>
<body>
    <div id="cabecalho">
    <?php include "../atalhos/estado.php"; ?>
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
            <li><a href="../">pagina incial</a></li>
            <li><a href="../perfil/">perfil</a></li>
            <li><a href="../conversa/">conversa <span style="color: yellow;"><?=$_SESSION['sms_nao_lido_todo']?></span></a></li>
            <li><a href="../usuarios.php">usuarios</a></li>
            <li><a href="../grupo/">grupos</a></li>
        </ul>    
    </div>
    <?php
        $sql = mysqli_query($link, "SELECT * FROM grupos WHERE id_grupo='$id_grupo'");
        $row = mysqli_fetch_assoc($sql);

        $pedido = mysqli_query($link, "SELECT * FROM pedido WHERE id_grupo='$id_grupo' AND id_user='$id_user'");
        $pedid = mysqli_fetch_assoc($pedido);

        $imagem = mysqli_query($link, "SELECT * FROM imagens WHERE id_grupo='$id_grupo' AND para='grupo'");
        $img = mysqli_fetch_assoc($imagem);

        $ver_m = mysqli_query($link, "SELECT * FROM membros_grupo WHERE id_grupo='$id_grupo' AND id_user='$id_user'");
        $m = mysqli_fetch_assoc($ver_m);

        if (!isset($img['indereco'])) {
            $img['indereco'] = "grupo_sem_foto.png";
        }
        ?>    
        <div>
            <div id="cabecalho_grupo">
                <div id="img"><img src="../../img_user/perfil/<?=$img['indereco']?>" alt=""></div>
                <div id="nomeg"><?=$row['nome']?></div>
                <div><?=$row['visibilidade']?></div>
                <div>
                    <?php
                    if ($row['id_user'] == $id_user) {
                        ?>
                        <li><a href="membros.php">ver solicitaçõe de aderencia </a></li>
                        <?php
                    }?>
                </div>
                <div>
                    <?php
                    if (isset($m['id_membro']) || ($row['id_user'] == $id_user)) {
                    }else{
                        if (!isset($pedid['id_pdd'])) {
                        ?>
                            <form method="POST" action="../estado.php">
                                <input type="number" name="aderir" value="<?=$row['id_grupo']?>" style="display:none;">
                                <input type="submit" value="aderir grupo">
                            </form>
                        <?php
                        }else {
                            ?>
                            <form method="POST" action="../estado.php">
                                <input type="number" name="aderir" value="<?=$row['id_grupo']?>" style="display:none;">
                                <input type="submit" value="cancelar pedido">
                            </form>
                        <?php
                        } 
                    }
                    ?>
                    
                </div>
            </div>
            <?php
            if (($row['visibilidade'] == "publico") || ($row['id_user'] == $id_user || isset($m['id_membro']))) {
                if (isset($m['id_membro']) || ($row['id_user'] == $id_user)) {
                    ?>
                <div id="area_publicar">
                <form method="POST">
                    <select name="tipo" id="">
                        <option value="normal">de rotina</option>
                        <option value="pergunta">pergunta</option>
                    </select>
                    <div>
                        <textarea placeholder="em que estas pensando?" name="texto" id="" cols="50" rows="6"></textarea><br>
                        <input type="submit" value="publicar">
                    </div>
                    
                </form>
                <?php
                            #fazendo uma publicação no site~
            if (isset($_POST['texto'])) {
                $id_user = $_SESSION['id_user'];
                $tipo = addslashes($_POST['tipo']);
                $texto = addslashes($_POST['texto']);

                    $imagem = "none";
                    $video = "none";
                    $visibilidade = "reservado";
                if (!empty($tipo) && !empty($texto) && !empty($imagem)) {

                    $u->conectar("glou","localhost","root","");
                    if ($u->msgErro == "") {
                        if ($u->publicar($id_user,$tipo,$texto,$imagem,$video,$visibilidade,$id_grupo)) {
            
                            header("location: grupo.php");
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
                } else {
                    ?>
                    <div>podes ver as publicaçõe de um grupo publico, mas não fazelas sem seres membro </div>
                    <?php
                }           
            ?>
                <?php
                    $id_grupo = $_SESSION['id_grupo'];
                    $sql = mysqli_query($link, "SELECT * from publicacoes WHERE id_grupo='$id_grupo'");
                    while($row = mysqli_fetch_assoc($sql)){     
                        $id_pbl = $row['id_pbl'];     
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
                            
                        <td id="nome">
                        <a class="link" href="../perfil/perfil_user.php?user=<?=$user['code_nome']?>">
                                <?=$user['nome']?>
                            </a>
                        </td>

                    </table>                       
                        <div>
                            <p><?=$row['texto']?></p>
                        </div>
                        <?php
                        if ($row['indereco'] != "none") {
                                 ?>
                                    <img style="width: 150px;" id="pbl" src="../img_user/historia/<?=$row['indereco']?>" alt="">
                                <?php
                                }
                        ?>
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
        <?php
    }else {
    ?>
    <div>seja membro para poder postar ou ver publicacões em um grupo privado</div>
    <?php
}?>

</body>
</html>