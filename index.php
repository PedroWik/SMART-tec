<?php
require_once 'conexao/usuario.php';
$u = new usuarios;
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/css.css">
    <link rel="stylesheet" href="css/css_comum/css.css">
    <title>Document</title>
</head>
<body>
    <div>
        <div id="cabecalho_index">
            <h1>SMART-tec</h1>
            <table>
                <tr>
                    <td><a href="registro.php">não tenho uma conta!</a></td>
                </tr>
            </table>
        </div>
        <div id="entrar" class="corpo">
            <div><h3>pagina de login</h3></div>
            <form method="POST">
                <div>
                    E-mail: <br><input type="email" name="email">
                </div>
                <div>
                    Senha: <br><input type="password" name="senha" id="">
                </div>
                <p></p>
                <div>
                    <input id="btn" type="submit" value="entrar">
                </div>
            </form>
            <div>
                <?php
                /*formatação do metodo logar*/
                if(isset($_POST['senha']))
                {
                    $email = addslashes($_POST['email']);
                    $senha = addslashes($_POST['senha']);
                    
                    if(!empty($email) && !empty($senha))
                    {
                        $u->conectar("glou","localhost","root","");
                        if ($u->msgErro == "") 
                        {
                        if ($u->logar($email,$senha)) 
                        {
                        
                            header("location: entrada/");
                
                        } 
                        else 
                        {
                        ?>
                        <div class="msg-erro">
                            email e/ou senha estão incorretos
                        </div>
                        <?php
                        }
                    } 
                    else 
                    {
                    echo "erro: ".$u->msgErro;
                    }
                    }
                    else 
                    {
                        ?>
                        <div class="text-center">
                        <div class="msg-erro">
                        preencha todos os campos
                        </div>
                    </div>
                    <?php
                    }		
                }       	
                ?>
            </div>
        </div>
    </div>
</body>
</html>