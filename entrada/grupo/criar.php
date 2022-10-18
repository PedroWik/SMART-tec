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
            <li><a href="../conversa/">conversa <span style="color: yellow;"><?=$_SESSION['sms_nao_lido_todo']?></span></a></li>
            <li><a href="../usuarios.php">usuarios</a></li>
            <li><a href="../grupo/">grupos</a></li>
        </ul>    
    </div>
        <form action="" method="POST">
            <div id="nome">
                <p>nome do grupo</p>
                <input type="text" name="nome" id="">
            </div>
            <div id="descricao"><textarea placeholder="breve descrição do grupo" name="descricao" id="" cols="30" rows="7"></textarea></div>
            <div id="outros">
                <p>Tipo: <select name="tipo" id="">
                    <option value="interece social">interce social</option>
                    <option value="arte e desenvolvimento">arte</option>
                    <option value="cultura">cultura</option>
                    <option value="academia">academia</option>
                </select></p>
                <p>Visibilidade <select name="visibilidade" id="">
                    <option value="publico">publico</option>
                    <option value="privado">privado</option>
                </select></p>
            </div>
            <div id="btn_criar"><input type="submit" value="criar"></div>
            <?php
            if (isset($_POST['nome'])) {
                $tipo = addslashes($_POST['tipo']);
                $nome = addslashes($_POST['nome']);
                $descricao = addslashes($_POST['descricao']);
                $visibilidade = addslashes($_POST['visibilidade']);

                if (!empty($tipo) && !empty($nome) && !empty($descricao) && !empty($visibilidade)) {

                    $u->conectar("glou","localhost","root","");
                    if ($u->msgErro == "") {
                        if ($u->novo_grupo($id_user,$nome,$descricao,$visibilidade,$tipo)) {
                            
                            $meu_grupo = mysqli_query($link, "SELECT * FROM grupos WHERE id_user ='$id_user' ORDER BY id_grupo DESC");  
                            $meu = mysqli_fetch_assoc($meu_grupo);
                            $_SESSION['id_grupo'] = $meu['id_grupo'];
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
        </form>
    </div>
</body>
</html>