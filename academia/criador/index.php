<?php
require_once '../../conexao/usuario.php';
require_once '../../conexao/conexao.php';
$u = new usuarios;

session_start();
if (!isset($_SESSION['id_user'])) 
{
  header("location: ../../../"); 
}
 $id_user = $_SESSION['id_user'];
?>
<html>
<head>
<title>academia</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../css/academia/stilo.css"/>
<link rel="stylesheet" href="../../css/academia/criador.css">

<meta charset="UTF-8"/>

</head>
<body id="corpo">
    <h1>academia</h1>
    <nav id="cab">
        <ul type="none" id="selec">
       <li id="acad"><a href="../">pagina inicial</li></a>
        </ul>
    </nav> 
    <div id="info">
        <p>
            insere as informações requeidas e prossegui para criação
            da sua academia digital.
        </p>
    </div>
<section>
    <form action="" method="POST">
        <div>        
            Nome da academia
            <br><input type="text" name="nome" maxlangh="30">
        </div>
        <div>
            academia de?
            <br><input type="text" name="area" maxlangh="30">
        </div>
        <div>
            localização
            <br><input type="text" name="localizacao" placeholder="se exister um academia fisica">
        </div>
        <div>
            disponibilidade
            <br><select name="disponibilidade" id="">
                <option value="sempre">sempre disponivel</option>
                <option value="fim_semana">fins de semana</option>
                <option value="dias_semana">dias de semana</option>
                <option value="variada">variada</option>
            </select>
        </div>
        <div><button>criar</button></div>
    </form>
    <?php
    if (isset($_POST['nome'])) {
        $area = addslashes($_POST['area']);
        $nome = addslashes($_POST['nome']);
        $disponibilidade = addslashes($_POST['disponibilidade']);
        if ($_POST['localizacao'] == "") {
            $localizacao = "nenhuma";
        }else {
            $localizacao = addslashes($_POST['localizacao']);
        }
        if (!empty($area) && !empty($nome) && !empty($disponibilidade) && !empty($localizacao)) {
            $u->conectar("glou","localhost","root","");
            if ($u->msgErro == "") {
                if ($u->criar_academia($id_user,$nome,$area,$disponibilidade,$localizacao)) {    
                    header("location: painel.php");
                } else {
                    echo "Já existe uma academia com esse nome";
                }
                
            } else {
                echo "erro: $u->msgErro";
            }
            
        } else {
            echo "preencha todos os campos";
        }
        
    }
    ?>
</section>
<aside>
  <div>
    <fieldset><legend>OBS:</legend>
        <p>-se não houver uma academia fisica para associar a essa 
            não precisa preencher a aba localizacão, porque do contrario 
            o site entendera que existe uma academia fisica.
        </p>
    </fieldset>
  </div>
</aside>
  <footer id="rodape">
<p>copyring  &copy; 2021 by pedro manuel</p>
<p><a href="http://free.facebook.com">facebook</a> / <a href="http://instagram.com">instagram</a></p>
</footer> 
</body>
</html>