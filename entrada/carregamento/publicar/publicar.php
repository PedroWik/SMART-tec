<?php
require_once "../../../conexao/usuario.php";
require_once "../../../conexao/conexao.php";
$u = new usuarios;

session_start();
$estado = $_SESSION['estado_p'];
$id_user = $_SESSION['id_user'];

?>
<?php include "../../atalhos/estado.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    if ($estado == "publicar") { 
    ?>
    <div id="pbl">
        <div >
            <form method="POST" enctype="multipart/form-data">
                <select name="tipo" id="">
                    <option value="normal">De rotina</option>
                    <option value="pergunta">pergunta</option>     
                </select>
                <select name="visibilidade" id="">
                    <option value="publico">publica</option>
                    <option value="privado">privada</option>     
                </select>
                <div>
                    <textarea name="texto" id="" cols="30" rows="10"></textarea>
                    <input type="file" name="pic" accept="image/*" class="form-control">
                    <input type="submit" value="publicar">
                </div>
            </form>
        </div>
        <div>
            <?php
                            #fazendo uma publicação no site~
            if (isset($_POST['texto'])) {
                
                $tipo = addslashes($_POST['tipo']);
                $texto = addslashes($_POST['texto']);
                $visibilidade = addslashes($_POST['visibilidade']);
                $id_grupo = 0;
                $video = "none";

                $ext = strtolower(substr($_FILES['pic']['name'],-4)); //Pegando extensão do arquivo
                $nome_img = date("Y.m.d-H.i.s") . $ext; //Definindo um novo nome para o arquivo
                $dir = '../../../img_user/historia/'; //Diretório para uploads

                if (empty($ext)) {
                    $imagem = "none";
                }else {
                    $imagem = $nome_img;
                }
                if (!empty($tipo) && !empty($texto) && !empty($imagem)) {

                    $u->conectar("glou","localhost","root","");
                    if ($u->msgErro == "") {
                        if ($u->publicar($id_user,$tipo,$texto,$imagem,$video,$visibilidade,$id_grupo)) {
                            move_uploaded_file($_FILES['pic']['tmp_name'], $dir.$nome_img); //Fazer upload do arquivo
                            header("location: ../../");
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
    </div>
    <?php
    } if ($estado == "editar") {
        # code...
    }
    ?>
</body>
</html>