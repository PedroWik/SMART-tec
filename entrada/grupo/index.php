<?php
require_once "../../conexao/conexao.php";
require_once "../../conexao/usuario.php";
$u = new usuarios;

session_start();
if (!isset($_SESSION['id_user'])) {
    header("location: ..");
}
if (empty($_SESSION['valor_estado'])) {
    $_SESSION['valor_estado'] = "";
}
$_SESSION['estado'] = "grupo";
$estado_p = $_SESSION['valor_estado'];
$id_user = $_SESSION['id_user'];
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
            <li><a href="../ativos.php">ativos<span style="color: yellow;"><?=$num_online?></span></a></li>
            <li><a href="../conversa/">conversa <span style="color: yellow;"><?=$_SESSION['sms_nao_lido_todo']?></span></a></li>
            <li><a href="../usuarios.php">usuarios</a></li>
            <li><a href="../grupo/">grupos</a></li>
        </ul>    
    </div>
    <?php
        ?>
        <div id="inicio">
            <li><a href="criar.php">criar novo grupo</a></li>
            <div id="direita">
                <div>
                    <h3>meus grupos</h3>
                    <div>
                        <?php
                        $meu_grupo = mysqli_query($link, "SELECT * FROM grupos WHERE id_user ='$id_user'");  
                        while ($meu = mysqli_fetch_assoc($meu_grupo)) {
                            ?>
                            
                            <a class="link3" href="grupo.php?user=<?=$meu['id_grupo']?>">
                                <div class="link3-1">    
                                <?=$meu['nome']?>
                                </div>
                            </a><br>
                            
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div>
                    <h3>grupos que faço parte</h3>
                    <div>
                    <?php
                        $grupo = mysqli_query($link, "SELECT * FROM grupos");  
                        while ($grup = mysqli_fetch_assoc($grupo)) {
                            $id_grupo = $grup['id_grupo'];
                            $parte = mysqli_query($link, "SELECT * FROM membros_grupo WHERE id_user ='$id_user' AND id_grupo='$id_grupo'");
                            $part = mysqli_fetch_assoc($parte);
                            
                            if (isset($part['id_membro'])) {
                                ?>
                                
                                <a class="link3" href="grupo.php?user=<?=$part['id_grupo']?>">
                                    <div class="link3-1">
                                    <?=$grup['nome']?>
                                    </div>
                                </a><br>
                                
                                <?php
                            }
                        }
                    ?>
                    </div>
                </div>
            </div>
            <div id="esquerda">
                <h3>grupos sugeridos</h3>
                <div>
                <?php
                    $sug_grupo = mysqli_query($link, "SELECT * FROM grupos");  
                    while ($sug = mysqli_fetch_assoc($sug_grupo)) {
                        $id_grupo = $sug['id_grupo'];
                        $parte = mysqli_query($link, "SELECT * FROM membros_grupo WHERE id_user ='$id_user' AND id_grupo='$id_grupo'");
                        $part = mysqli_fetch_assoc($parte);
                        
                        if (!isset($part['id_membro']) && ($id_user != $sug['id_user'])) {
                            ?>
                            
                            <a class="link3" href="grupo.php?user=<?=$sug['id_grupo']?>">
                                <div class="link3-1">    
                                <?=$sug['nome']?>
                                </div>
                            </a><br>
                            
                            <?php
                        }
                    }
                ?>    
                </div>
            </div>
        </div>
</body>
</html>