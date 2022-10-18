<?php
require_once "../conexao/conexao.php";
require_once "../conexao/usuario.php";
$u = new usuarios;
session_start();
$id_user = $_SESSION['id_user'];

$estado = $_SESSION['estado'];

if ($estado == "inicio") {
    if (isset($_POST['gosto'])) {
        $id_pbl = addslashes($_POST['id_pbl']);
        $u->conectar("glou", "localhost", "root", "");
        $_SESSION['tipo'] = "gosto";
        if ($u->reagir($id_pbl,$id_user)) {
            # code...
        }
    }
    if (isset($_POST['adoro'])) {
        $id_pbl = addslashes($_POST['id_pbl']);
        $u->conectar("glou", "localhost", "root", "");
        $_SESSION['tipo'] = "adoro"; 
        if ($u->reagir($id_pbl,$id_user)) {
            # code...
        }
    }
    header("location: ./");
}

if ($estado == "comentar") {
    if (isset($_POST['textoc'])) {
        $id_user = $_SESSION['id_user'];
        $id_pbl = addslashes($_POST['id_pbl']);
        $texto = addslashes($_POST['textoc']);
        if (!empty($id_pbl) && !empty($texto)  && !empty($id_user)) {

            $u->conectar("glou","localhost","root","");
            if ($u->comentar($id_pbl,$id_user,$texto)) {
            }   
        }   
    }
    if (isset($_POST['gosto'])) {
        $id_pbl = addslashes($_POST['id_pbl']);
        $u->conectar("glou", "localhost", "root", "");
        $_SESSION['tipo'] = "gosto";
        if ($u->reagir($id_pbl,$id_user)) {
            # code...
        }
    }
    if (isset($_POST['adoro'])) {
        $id_pbl = addslashes($_POST['id_pbl']);
        $u->conectar("glou", "localhost", "root", "");
        $_SESSION['tipo'] = "adoro"; 
        if ($u->reagir($id_pbl,$id_user)) {
            # code...
        }
    }
    header("location: carregamento/publicar/comentar/");
}

if ($estado == "perfil") {
    if (isset($_POST['eliminar'])) {
        $resultado = true;
        $id_pbl = addslashes($_POST['eliminar']);
        $sql = mysqli_query($link, "SELECT * FROM publicacoes WHERE id_pbl='$id_pbl'");
        $row = mysqli_fetch_assoc($sql);
        if ($row['indereco_v'] != "none") {
            $arquivo = "../videos_user/historia/".$row['indereco_v'];
            $resultado = unlink($arquivo);
        }
        $u->conectar("glou","localhost","root","");
        if ($u->apagar_publicacao($id_pbl) && $resultado) {
            
        }
    }
    if (isset($_POST['gosto'])) {
        $id_pbl = addslashes($_POST['id_pbl']);
        $u->conectar("glou", "localhost", "root", "");
        $_SESSION['tipo'] = "gosto";
        if ($u->reagir($id_pbl,$id_user)) {
            # code...
        }
    }
    if (isset($_POST['adoro'])) {
        $id_pbl = addslashes($_POST['id_pbl']);
        $u->conectar("glou", "localhost", "root", "");
        $_SESSION['tipo'] = "adoro"; 
        if ($u->reagir($id_pbl,$id_user)) {
            # code...
        }
    }
    header("location: perfil/");
}

if ($estado == "grupo") {
    if (isset($_POST['aderir'])) {
        $id_grupo = addslashes($_POST['aderir']);
        $de = "grupo";

        $u->conectar("glou","localhost","root","");
        $u->pedido_grupo($id_user,$id_grupo,$de);
        
    }
    if (isset($_POST['id_user'])) {
        $id_grupo = addslashes($_POST['id_grupo']);
        $user = addslashes($_POST['id_user']);
        $de = "grupo";

        $u->conectar("glou","localhost","root","");
        $u->comfirmar_membro($user,$id_grupo);

        header("location: grupo/membros.php");
    }
    if (isset($_POST['gosto'])) {
        $id_pbl = addslashes($_POST['id_pbl']);
        $u->conectar("glou", "localhost", "root", "");
        $_SESSION['tipo'] = "gosto";
        if ($u->reagir($id_pbl,$id_user)) {
            # code...
        }
    }
    if (isset($_POST['adoro'])) {
        $id_pbl = addslashes($_POST['id_pbl']);
        $u->conectar("glou", "localhost", "root", "");
        $_SESSION['tipo'] = "adoro"; 
        if ($u->reagir($id_pbl,$id_user)) {
            # code...
        }
    }
    header("location: grupo/grupo.php");
}
?>