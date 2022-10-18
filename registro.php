<?php
require_once 'conexao/usuario.php';
require_once 'conexao/conexao.php';
$u = new usuarios;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/css.css">
    <link rel="stylesheet" href="css/css_comum/css.css">
    <title>pagina de registro</title>
</head>
<body>
    <div>
    <div id="cabecalho_index">
            <h1>SMART-tec</h1>
            <table>
                <tr>
                    <td><a href="index.php">já tenho uma conta!</a></td>
                </tr>
            </table>
        </div>
        <style>
            section{
                width: 48%;
                float: left;
            }
            aside{
                width: 48%;
                float: right;
                clear:;
            }
        </style>
        <div id="registro" class="corpo">
            <div><h3>Pagina de registro</h3></div>
            <form method="POST">
                <div>
                    E-mail: <br><input type="email" name="email" id="">
                </div>
                <div>
                    <section>
                        apelido: <br><input type="text" name="nome" maxlang="20">
                    </section>
                    <aside>Sobrenome <br><input type="text" name="snome" maxlang="15"></aside>
                </div>
                <div>
                    Data de nascimento: <br><input type="date" name="data_nascimento" id="">
                </div>
                <div>
                    dominio de programação: <br><select name="dominio_de_programacao" id="">
                        <option value="full-stack">Full-Stack</option>
                        <option value="front-end">Front-End</option>
                        <option value="back-end">Back-End</option>
                    </select>
                </div>
                <div>
                    Sexo: <br><select name="sexo" id="">
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="N_E">Ocultar</option>
                    </select>
                </div>
                <div>
                    Senha: <br><input type="password" name="senha" id="">
                </div>
                <div>
                    comfirmar senha: <br><input type="password" name="comfsenha" id="">
                </div>
                code_nome: <br><input type="text" name="code_nome" id="">
                <?php
                ?>
                <div>
                    <p></p>
                    <input id="btn" type="submit" value="cadastrar">
                </div>
            </form>
            <div>
                <?php
                if(isset($_POST['nome'])){   
                    /*$n = 0;
                    $very = "existe";
                    while ($very != "existe") {
                        $code_nome = $code_nome."".$n++;
                        $sql = mysqli_query($link, "SELECT * FROM usuarios");
                        while ($row = mysqli_fetch_assoc($sql)) {
                            if ($code_nome == $row['code_nome']) {
                                $very = "existe";
                            }
                        }
                    } */
                    $code_nome = addslashes($_POST['code_nome']);
                    $nome = addslashes($_POST['nome'])." ".addslashes($_POST['snome']);
                    $email = addslashes($_POST['email']);
                    $sexo = addslashes($_POST['sexo']);
                    $data_nascimento = addslashes($_POST['data_nascimento']);
                    $dominio_de_programacao = addslashes($_POST['dominio_de_programacao']);
                    $senha = addslashes($_POST['senha']);
                    $comfsenha = addslashes($_POST['comfsenha']);
                    
                    if(!empty($nome) && !empty($email) && !empty($dominio_de_programacao) && !empty($senha) && !empty($comfsenha) && !empty($data_nascimento) && !empty($sexo))
                    {
                        $u->conectar("glou","localhost","root","");
                        if($u->msgErro == "")
                        {
                            if($senha == $comfsenha)
                            {
                                if ($u->cadastrar($nome,$email,$senha,$sexo,$data_nascimento,$dominio_de_programacao,$code_nome)) 
                                {
                                    header("location: entrada/");
                                } 
                                else 
                                {
                                    ?>
                                    <div class="msg-erro">
                                    email ja cadastrado
                                    </div>
                                    <?php
                                }				
                            }
                            else
                            {
                                ?>
                                <div class="msg-erro">
                                senhas não correspondem
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
                        <div class="msg-erro">
                            preemcha os campos todos
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