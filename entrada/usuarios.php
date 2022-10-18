<?php
require_once "../conexao/conexao.php";
require_once "../conexao/usuario.php";
$u = new usuarios;

session_start();
if (!isset($_SESSION['id_user'])) {
    header("location: ..");
}
$id_user = $_SESSION['id_user'];

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
    <script src="../js/js.js"></script>
    <title>GLOU</title>
</head>
<body>
    <div id="cabecalho">
    <?php include "atalhos/estado.php"; ?>
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
                <li><a href="./">pagina inicial</a></li>
                <li><a href="perfil/">perfil</a></li>
                <li><a href="ativos.php">ativos<span style="color: yellow;"><?=$num_online?></span></a></li>
                <li><a href="conversa/">conversa <span style="color: yellow;"><?=$_SESSION['sms_nao_lido_todo']?></span></a></li>
                <li><a href="usuarios.php">usuarios</a></li>
                <li><a href="grupo/">grupos</a></li>
            </ul>
    </div>
    <div id="corpo">
        <div id="pedidos">
        <?php
                    $pdd = mysqli_query($link, "SELECT * from `pedido` WHERE id_dest='$id_user' AND de='amizade'");
                    while($pedido = mysqli_fetch_assoc($pdd)){
                        $id_dest = $pedido['id_user'];
                        $sql = mysqli_query($link, "SELECT * from `usuarios` WHERE id_user='$id_dest'");
                        while($row = mysqli_fetch_assoc($sql)){                                  

                         $id = $row['id_user'];
                            $qry = mysqli_query($link, "SELECT * from imagens where id_user='$id'");
                            $nome = mysqli_fetch_assoc($qry);
                            $_SESSION['indereco'] = $nome['indereco'];
                            if (!isset($_SESSION['indereco'])) {
                                $_SESSION['indereco'] ="sem_foto.png";
                            }
                        ?>        
                <table id="usuarios">        
                    <td id="img">
                        <img src="../img_user/perfil/<?=$_SESSION['indereco']?>">
                    </td>
                        
                    <td id="nome">
                        <p><?=$row['nome']?></p>
                        <form method="POST">
                            <input type="number" name="id_desti" style="display: none;" value="<?=$id?>">
                            <button><p id="adicionar">confirmar amigo</p></button>
                        </form>
                        <div>
                        <?php
                                    if (isset($_POST['id_desti'])) {
                                        $id_dest = addslashes($_POST['id_desti']);
                                        $de = "amizade";
                                        if (!empty($id_user) && !empty($id_dest)) {
                        
                                            $u->conectar("glou","localhost","root","");
                                            if ($u->msgErro == "") {
                                                if ($u->comfirmar_amizade($id_user,$id_dest,$de)) {

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
                    </td>
                  
                </table>
                <?php
                        }
                    }
                ?>
        </div>
        <div id="esquerdo">
            <div>
                <h2>usuarios sugeridos</h2>
                <?php
                    $sql = mysqli_query($link, "SELECT * from usuarios");
                    while($row = mysqli_fetch_assoc($sql)){          

                        $veryf = $row['id_user'];

                        $ver = mysqli_query($link, "SELECT id_pdd from pedido WHERE id_user='$veryf' AND id_dest='$id_user'");
                        $very = mysqli_fetch_assoc($ver);

                        $ver_a = mysqli_query($link, "SELECT * from amigo WHERE id_user='$veryf' AND id_dest='$id_user'");
                        $very_a = mysqli_fetch_assoc($ver_a);

                        $ver_p = mysqli_query($link, "SELECT * from pedido WHERE id_user='$id_user' AND id_dest='$veryf'");
                        $very_p = mysqli_fetch_assoc($ver_p);                        

                        if (($row['id_user'] != $id_user) && (!isset($very['id_pdd'])) && (!isset($very_a['id_amigo'])) && (!isset($very_p['id_pdd']))) {
                            $id = $row['id_user'];
                            $qry = mysqli_query($link, "SELECT * from imagens where id_user='$id'");
                            $nome = mysqli_fetch_assoc($qry);
                            $_SESSION['indereco'] = $nome['indereco'];
                            if (!isset($_SESSION['indereco'])) {
                                $_SESSION['indereco'] ="sem_foto.png";
                            }
                        ?>        
                        <table id="usuarios">        

                            <td id="img">
                                <img src="../img_user/perfil/<?=$_SESSION['indereco']?>">
                            </td>
                                
                            <td id="nome">
                                <p><?=$row['nome']?></p>
                                <form method="POST">
                                    <input type="number" name="id_dest" style="display: none;" value="<?=$id?>">
                                    <button><p id="adicionar">adicionar amigo</p></button>
                                </form>
                                <div>
                                    <?php
                                    if (isset($_POST['id_dest'])) {
                                        $id_dest = addslashes($_POST['id_dest']);
                                        $de = "amizade";
                                        if (!empty($id_user) && !empty($id_dest)) {
                        
                                            $u->conectar("glou","localhost","root","");
                                            if ($u->msgErro == "") {
                                                if ($u->pedido_de_amizade($id_user,$id_dest,$de)) {
                                                    header("location: usuarios.php");
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
                            </td>
                        
                        </table>
                    <?php
                        }
                    }
                    ?>
            </div>  
        </div>
    </div>
    <footer id="rodape">
<p>copyring  &copy; 2021 by pedro manuel</p>
<p><a href="http://free.facebook.com">facebook</a> / <a href="http://instagram.com">instagram</a></p>
</footer> 
</body>
</html>