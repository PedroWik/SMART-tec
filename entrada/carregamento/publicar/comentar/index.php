<?php
require_once "../../../../conexao/conexao.php";
require_once "../../../../conexao/usuario.php";
session_start();

$u = new usuarios;
 
    $_SESSION['id_pbl'] = addslashes($_GET['pbl']);
    $id_pbl = $_SESSION['id_pbl'];

$_SESSION['estado'] = "comentar";

if (!isset($_SESSION['id_user'])) {
    header("location: ../../../");
}
if (!isset($_SESSION['id_pbl'])) {
    header("location: ../../");
}else{
    $id_pbl = $_SESSION['id_pbl'];
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
    <script src="../../../../js/js.js"></script>
    <link rel="stylesheet" href="../../../../css/publicacoes.css">
    <link rel="stylesheet" href="../../../../css/css.css">
    <link rel="stylesheet" href="../../../../<?=$_SESSION['stilo']?>/publicacoes.css">
    <link rel="stylesheet" href="../../../../<?=$_SESSION['stilo']?>/css.css">
    <title>Comentario</title>
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
    <?php include "../../../atalhos/estado.php"; ?>
    <h1>SMART-TEC</h1>
        <form method="GET" action="../../../procurar.php">
            <table id="pesquisa">
                <tr>
                    <td><h1>GLOU</h1></td>
                    <td><input type="search" name="pesquisar" id=""></td>
                    <td><input type="submit" value="pesquisar"></td>
                </tr>
            </table>
        </form>
        <ul type="none">
                <li><a href="../../.././">pagina inicial</a></li>
                <li><a href="../../../perfil/">perfil</a></li>
                <li><a href="../../../conversa/">conversa <span style="color: yellow;"><?=$_SESSION['sms_nao_lido_todo']?></span></a></li>
                <li><a href="../../../usuarios.php">usuarios</a></li>
            </ul>
    </div>
    <div id="cmt">
        <div>
            
        </div>
        <div id="comentarios">
            <div>
            <?php
                    $sql = mysqli_query($link, "SELECT * from publicacoes WHERE id_pbl='$id_pbl'");
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
                            $qry = mysqli_query($link, "SELECT * from imagens where id_user='$id'");
                            $nome = mysqli_fetch_assoc($qry);
                            $_SESSION['indereco'] = $nome['indereco'];
                            if (!isset($_SESSION['indereco'])) {
                                $_SESSION['indereco'] ="sem_foto.png";
                            }                   
                        ?>
                    </div>
                    <table id="usuarios">        

                        <td id="img">
                            <img src="../../../../img_user/perfil/<?=$_SESSION['indereco']?>">
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
                                        width: 60%;
                                    }
                                </style>
                                <img id="pbl" src="../../../../img_user/historia/<?=$row['indereco']?>" alt="">
                                <?php
                            }
                            
                            ?>
                        </div>
                    </div>               
                    <table class="int">
                        <?php
                        $id_pbl = $row['id_pbl'];
                        $u->conectar("glou","localhost","root","");         
						$u->contar_gosto($id_pbl);
						
						$u->conectar("glou","localhost","root","");         
						$u->contar_adoro($id_pbl);

						$u->conectar("glou","localhost","root","");         
						$u->contar_comentario($id_pbl);
						?>
                        <tr>
                        <td class="pk">
							<form method="POST" action="../../../estado.php">
									<input style="display: none;" type="number" name="id_pbl" value="<?=$row['id_pbl']?>">
									<input style="display: none;" type="number" name="gosto" value="gosto">
								   <button name="btn" value=""><span id="gosto"><?=$_SESSION['gosto']?></span><img src="../../../../img/gosto.png" alt=""></button>
								</form> 
							</td>
							<td class="pk">
							<form method="POST" action="../../../estado.php">
									<input style="display: none;" type="number" name="id_pbl" value="<?=$row['id_pbl']?>">
									<input style="display: none;" type="number" name="adoro" value="adoro">
								   <button name="btn" value=""><span id="adoro"><?=$_SESSION['adoro']?></span><img src="../../../../img/adoro.png" alt=""></button>
								</form>
							</td>
							<td class="pk">
								<form method="POST">
									<input style="display: none;" type="number" name="id" value="<?=$row['id_pbl']?>">
								   <button name="btn" value=""><span id="comentario"><?=$_SESSION['comentario']?></span><img src="../../../../img/comentario.png" alt=""></button>
								</form>
							 </td>
                            <td >:<?=$row['data']?></td>
                        </tr>
                    </table>  
                </div>
                <?php
                    }
                ?>
            </div>
            <?php
            $sql = mysqli_query($link, "SELECT * from comentario WHERE id_pbl ='$id_pbl'");
            while($row = mysqli_fetch_assoc($sql)){
                ?>
                <div id="corpo_comentario">
                    <?php
                        $id = $row['id_user'];
                        $user = mysqli_query($link, "SELECT * from usuarios where id_user='$id'");
                        $nome = mysqli_fetch_assoc($user);

                        $foto = mysqli_query($link, "SELECT * from imagens where id_user='$id'");
                        $ffoto = mysqli_fetch_assoc($foto);
                        if (!isset($ffoto['indereco'])) {
                            $ffoto['indereco'] ="sem_foto.png";
                        }                   
                    ?>
                    <table id="usuarios">        

                        <td id="img">
                            <img src="../../../../img_user/perfil/<?=$ffoto['indereco']?>">
                        </td>
                            
                        <td id="nome"><p style="float: left; font-size: 12pt;"><?=$nome['nome']?></p></td>

                    </table>
                    <div class="texto"><?=$row['texto']?></div>
                    <table class="int">
                        <?php
                        $id_cmt = $row['id_cmt'];
                        $u->conectar("glou","localhost","root","");         
						$u->contar_gosto_comentario($id_cmt);

						$u->conectar("glou","localhost","root","");         
						$u->contar_resposta($id_cmt);
						?>
                        <tr>
                        <td class="pk">
							<form method="POST">
									<input style="display: none;" type="number" name="id_pbl" value="<?=$row['id_pbl']?>">
									<input style="display: none;" type="number" name="gosto" value="gosto">
								   <button name="btn" value=""><span id="gosto"><?=$_SESSION['gosto_c']?></span><img src="../../../../img/gosto.png" alt=""></button>
								</form> 
							</td>
							<td class="pk">
								<form method="POST">
									<input style="display: none;" type="number" name="id" value="<?=$row['id_pbl']?>">
								   <button name="btn" value=""><span id="comentario"><?=$_SESSION['resposta']?></span>responder</button>
								</form>
							 </td>
                            <td >:<?=$row['data']?></td>
                        </tr>
                    </table>
                </div>
                <?php
            }
            ?>
            
            
        </div>
        <div id="comentar">
            <form method="POST" action="../../../estado.php">
                <div>
                    <input type="number" value="<?=$_SESSION['id_pbl']?>" name="id_pbl" style="display: none;">
                    <textarea name="textoc" id="" cols="50" rows="5"></textarea>
                    <input type="submit" value="comentar">
                </div>
            </form>
        </div>
        <div>
        </div>
    </div>
</body>
</html>