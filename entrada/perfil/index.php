<?php
require_once "../../conexao/conexao.php";
require_once "../../conexao/usuario.php";
$u = new usuarios;

session_start();
if (!isset($_SESSION['id_user'])) {
    header("location: ..");
}

$_SESSION['estado'] = "perfil";

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
            <li><a href="../">pagina inicial</a></li>
            <li><a href="./">perfil</a></li>
            <li><a href="../ativos.php">ativos<span style="color: yellow;"><?=$num_online?></span></a></li>
            <li><a href="../conversa/">conversa <span style="color: yellow;"><?=$_SESSION['sms_nao_lido_todo']?></span></a></li>
            <li><a href="../usuarios.php">usuarios</a></li>
            <li><a href="../grupo/">grupos</a></li>
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
                <form action="../carregamento/carregar_img.php" method="POST">
                    <input type="submit" value="carregar nova foto">
                </form>
            </div>
            <div id="direita">
                <form>
                    <p id="nomeu"><?=$_SESSION['nomeu']?></p>
                    <ul type="none">
                        <li><button>editar</button></li>
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
                    <select name="visibilidade" id="">
                        <option value="publico">publico</option>
                        <option value="privado">só amigos</option>
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
                $visibilidade = addslashes($_POST['visibilidade']);
                    $video = "none";
                    $id_grupo = 0;
                    $imagem = "none";
                if (!empty($tipo) && !empty($texto)) {

                    $u->conectar("glou","localhost","root","");
                    if ($u->msgErro == "") {
                        if ($u->publicar($id_user,$tipo,$texto,$imagem,$video,$visibilidade,$id_grupo)) {
            
                            header("location: ./");
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
                            <?php
                    $sql = mysqli_query($link, "SELECT * from publicacoes WHERE id_user='$id_user' AND id_grupo='0'");
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
                        <a class="link" href="./?user=<?=$user['code_nome']?>">
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
                                <img style="width: 150px;" id="pbl" src="../../img_user/historia/<?=$row['indereco']?>" alt="">
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
							<form method="POST" action="../estado.php">
									<input style="display: none;" type="number" name="id_pbl" value="<?=$row['id_pbl']?>">
									<input style="display: none;" type="number" name="gosto" value="gosto">
								   <button name="btn" value=""><span id="gosto"><?=$_SESSION['gosto']?></span><img src="../../img/gosto.png" alt=""></button>
								</form> 
							</td>
							<td class="pk">
							<form method="POST" action="../estado.php">
									<input style="display: none;" type="number" name="id_pbl" value="<?=$row['id_pbl']?>">
									<input style="display: none;" type="number" name="adoro" value="adoro">
								   <button name="btn" value=""><span id="adoro"><?=$_SESSION['adoro']?></span><img src="../../img/adoro.png" alt=""></button>
								</form>
							</td>
							<td class="pk">
								<form method="POST" action="../carregamento/publicar/comentar.php">
									<input style="display: none;" type="number" name="id" value="<?=$row['id_pbl']?>">
								   <button name="btn" value=""><span id="comentario"><?=$_SESSION['comentario']?></span><img src="../../img/comentario.png" alt=""></button>
								</form>
							 </td>
                             <td class="pk">
                                <form method="POST" action="../estado.php">
                                    <input type="number" name="editar" value="<?=$row['id_pbl']?>" style="display: none;">
                                    <button>editar</button>
                                </form>
                             </td>
                             <td class="pk">
                                <form method="POST" action="../estado.php">
                                    <input type="number" name="eliminar" value="<?=$row['id_pbl']?>" style="display: none;">
                                    <button>eliminar</button>
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