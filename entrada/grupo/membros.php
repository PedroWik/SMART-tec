<?php
require_once "../../conexao/conexao.php";
require_once "../../conexao/usuario.php";
$u = new usuarios;

session_start();
if (!isset($_SESSION['id_user'])) {
    header("location: ../..");
}
$_SESSION['estado'] = "grupo";
$id_user = $_SESSION['id_user'];
$id_grupo = $_SESSION['id_grupo'];
$limit = 0;

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
            <li><a href="../">pagina incial</a></li>
            <li><a href="../perfil/">perfil</a></li>
            <li><a href="../conversa/">conversa <span style="color: yellow;"><?=$_SESSION['sms_nao_lido_todo']?></span></a></li>
            <li><a href="../usuarios.php">usuarios</a></li>
            <li><a href="../grupo/">grupos</a></li>
        </ul>    
    </div>
    <?php
    ?>
    <div id="inicio">
        <div id="direita">
            <h3>membros do grupo</h3>
            <?php
            $membros = mysqli_query($link, "SELECT * FROM membros_grupo WHERE id_grupo='$id_grupo'");   
            while ($membro = mysqli_fetch_assoc($membros)) {
                $user = $membro['id_user'];
                $usuario = mysqli_query($link, "SELECT * FROM usuarios WHERE id_user='$user'");
                $user = mysqli_fetch_assoc($usuario);
                ?>
                <div id="membro"><?=$user['nome']?>
                <table>
                    <td>
                        <form method="POST" action="../estado.php">
                            <input style="display: none;" type="number" name="eliminar" value="<?=$user['id_user']?>">
                            <input style="display: none;" type="number" name="id_grupo" value="<?=$membro['id_grupo']?>">
                            <button>eliminar</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="../estado.php">
                            <input style="display: none;" type="number" name="promover" value="<?=$user['id_user']?>">
                            <input style="display: none;" type="number" name="id_grupo" value="<?=$membro['id_grupo']?>">
                            <button>promover</button>
                        </form>
                    </td>
                </table>
                    
                </div>
                <?php
            } 

            ?>
        </div>
        <div id="esquerda">
            <h3>solicitações</h3>
            <?php
            $pedido = mysqli_query($link, "SELECT * FROM pedido WHERE id_grupo = '$id_grupo'");
            while ($pedid = mysqli_fetch_assoc($pedido)) {
                $user = $pedid['id_user'];
                $usuario = mysqli_query($link, "SELECT * FROM usuarios WHERE id_user='$user'");
                $user = mysqli_fetch_assoc($usuario);
                ?>
                <div><?=$user['nome']?>
                    <form method="POST" action="../estado.php">
                        <input style="display: none;" type="number" name="id_user" value="<?=$user['id_user']?>">
                        <input style="display: none;" type="number" name="id_grupo" value="<?=$pedid['id_grupo']?>">
                        <button>aceitar</button>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

</body>
</html>