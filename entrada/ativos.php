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
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/css.css">
    <link rel="stylesheet" href="../css/chat.css">
    <link rel="stylesheet" href="../<?=$_SESSION['stilo']?>/css.css">
    <link rel="stylesheet" href="../<?=$_SESSION['stilo']?>/chat.css">
    <script src="../../js/js.js"></script>
    <title>chat</title>
</head>
<body>
    <div>
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
    </div>
    <?php
    #$ver_sms = mysqli_query($link, "SELECT * FROM bate_papo ORDER BY data DESC"); 
    #while ($ver_sm = mysqli_fetch_assoc($ver_sms)) {
    #    $id_dest = $ver_sm['id_user_dest'];


        $sessao = mysqli_query($link, "SELECT * from sessao");
        while ($sess = mysqli_fetch_assoc($sessao)) {
            $id = $sess['id_user'];

            $usuarios = mysqli_query($link, "SELECT * from `usuarios` WHERE id_user='$id'");
            $usuario = mysqli_fetch_assoc($usuarios);

            $amigos = mysqli_query($link, "SELECT * from `amigo` WHERE id_user = '$id_user' AND id_dest='$id'");
            $amigo = mysqli_fetch_assoc($amigos);
            if (isset($amigo['id_amigo'])) {
                ?>
                <a class="link3" href="conv_next.php?user=<?=$usuario['code_nome']?>">
                    <div class="link3-1">
                    <?=$usuario['nome']?>
                    </div>
                </a><br>
                <?php
            }
            
        } 
    #}
    ?>
    <footer id="rodape">
<p>copyring  &copy; 2021 by pedro manuel</p>
<p><a href="http://free.facebook.com">facebook</a> / <a href="http://instagram.com">instagram</a></p>
</footer> 
</body>
</html>