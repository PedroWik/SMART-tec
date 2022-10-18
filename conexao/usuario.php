<?php
class usuarios
{
	private $pdo;
	public $msgMessage ="";
	public $msgErro;
	public $id_destino;

	public function conectar($nome, $host, $usuario, $senha)
	{
		try
		{
			global $pdo;
			$pdo = new PDO("mysql:dbname=".$nome.";host=".$host,$usuario,$senha);
		} catch (PDOException $e){
			$msgErro = $e->getMessage();
		}
	}

	public function cadastrar($nome,$email,$senha,$sexo,$data_nascimento,$dominio_de_programacao,$code_nome)
	{
		global $pdo;

		$sql = $pdo->prepare("SELECT id_user FROM usuarios WHERE email = :e");
		$sql->bindValue(":e",$email);
		$sql->execute();
		if($sql->rowCount() > 0)
		{
			return false;
		}
		else
		{
			$sql = $pdo->prepare("INSERT INTO usuarios (nome,email,code_nome,senha,sexo,data_nascimento,dominio_de_programacao, data_cadastro) 
			VALUES (:n, :e, :cn, :s, :sx, :d, :dp, NOW())");
			$sql->bindValue(":n",$nome);
			$sql->bindValue(":e",$email);
			$sql->bindValue(":cn",$code_nome);
			$sql->bindValue(":s",md5($senha));
			$sql->bindValue(":sx",$sexo);
			$sql->bindValue(":d",$data_nascimento);
			$sql->bindValue(":dp",$dominio_de_programacao);
			$sql->execute();
				
			$sql = $pdo->prepare("SELECT * FROM `usuarios` WHERE email=:e AND senha=:se");
			$sql->bindValue(":e",$email);
			$sql->bindValue(":se",md5($senha));
			$sql->execute();

			$dado = $sql->fetch();
			session_start();
			$_SESSION['id_user'] = $dado['id_user'];
			$_SESSION['nomeu'] = $dado['nome'];
			$_SESSION['stilo'] = "ccss/css_comum";

			return true;
		}
	}

	public function logar($email,$senha)
	{
		global $pdo;
		$sql = $pdo->prepare("SELECT * FROM `usuarios` WHERE email=:e AND senha=:se");
		$sql->bindValue(":e",$email);
		$sql->bindValue(":se",md5($senha));
		$sql->execute();
		if($sql->rowCount() > 0)
		{
			$dado = $sql->fetch();
			session_start();
			$_SESSION['id_user'] = $dado['id_user'];
			$_SESSION['nomeu'] = $dado['nome'];
			$_SESSION['stilo'] = "css/css_comum";

			return true;
		}
		else
		{
			return false;
		}
	}

	public function publicar($id_user,$tipo,$texto,$imagem,$video,$visibilidade,$id_grupo){
		global $pdo;
		$sql = $pdo->prepare("INSERT INTO publicacoes (id_user,tipo,texto,indereco,indereco_v,visibilidade,id_grupo,data)
		VALUES (:id_user, :t, :tx, :i, :v, :vi, :g, NOW())");
		$sql->bindValue(":id_user",$id_user);
		$sql->bindValue(":t",$tipo);
		$sql->bindValue(":tx",$texto);
		$sql->bindValue(":i",$imagem);
		$sql->bindValue(":v",$video);
		$sql->bindValue(":vi",$visibilidade);
		$sql->bindValue(":g",$id_grupo);
		$sql->execute();


		return true;
	} 
	public function apagar_publicacao($id_pbl)
	{
		global $pdo;
		$sql = $pdo->prepare("DELETE FROM `publicacoes` WHERE id_pbl = :p");
		$sql->bindValue(":p",$id_pbl);
		$sql->execute();
		$sql = $pdo->prepare("DELETE FROM `comentario` WHERE id_pbl = :p");
		$sql->bindValue(":p",$id_pbl);
		$sql->execute();
		$sql = $pdo->prepare("DELETE FROM `reacao` WHERE id_pbl = :p");
		$sql->bindValue(":p",$id_pbl);
		$sql->execute();

		return true;
	}
	public function comentar($id_pbl,$id_user,$texto){
		global $pdo;
		$sql = $pdo->prepare("INSERT INTO comentario (id_pbl,id_user,texto,data)
		VALUES (:pbl, :user, :tx, NOW())");
		$sql->bindValue(":pbl",$id_pbl);
		$sql->bindValue(":user",$id_user);
		$sql->bindValue(":tx",$texto);
		$sql->execute();

		header("location: comentar.php");

		return true;
	}
	public function carregar_imagem_p($id_user,$nome_img,$para)
	{
		global $pdo;
		$sql = $pdo->prepare("INSERT INTO imagens (indereco,id_user,para,data)
		VALUES (:n, :user, :p, NOW())");
		$sql->bindValue(":n",$nome_img);
		$sql->bindValue(":user",$id_user);
		$sql->bindValue(":p",$para);
		$sql->execute();

		header("location: ../../entrada/perfil/");

		return true;
	}
	public function pedido_de_amizade($id_user,$id_dest,$de)
	{
		global $pdo;

		$sql = $pdo->prepare("SELECT * FROM pedido WHERE id_dest = :d AND id_user = :u");
		$sql->bindValue(":d",$id_dest);
		$sql->bindValue(":u",$id_user);
		$sql->execute();
		if($sql->rowCount() > 0)
		{
			return true;
		}else{
			$sql = $pdo->prepare("INSERT INTO pedido (id_user,id_dest,de)
			VALUES (:u, :d, :e)");
			$sql->bindValue(":u",$id_user);
			$sql->bindValue(":d",$id_dest);
			$sql->bindValue(":e",$de);
			$sql->execute();
			return true;
		}
	}
	public function comfirmar_amizade($id_user,$id_dest,$de)
	{
		global $pdo;
		$sql = $pdo->prepare("DELETE FROM `pedido` WHERE id_user = :d AND id_dest = :u");
		$sql->bindValue(":u",$id_user);
		$sql->bindValue(":d",$id_dest);
		$sql->execute();

		$sql = $pdo->prepare("INSERT INTO amigo (id_user,id_dest,data)
		VALUES (:u, :d, NOW())");
		$sql->bindValue(":u",$id_user);
		$sql->bindValue(":d",$id_dest);
		$sql->execute();

		$sql = $pdo->prepare("INSERT INTO amigo (id_user,id_dest,data)
		VALUES (:d, :u, NOW())");
		$sql->bindValue(":u",$id_user);
		$sql->bindValue(":d",$id_dest);
		$sql->execute();
		return true;
	}
	public function pedido_grupo($id_user,$id_grupo,$de)
	{
		global $pdo;

		$sql = $pdo->prepare("SELECT * FROM pedido WHERE id_grupo = :d AND id_user = :u");
		$sql->bindValue(":d",$id_grupo);
		$sql->bindValue(":u",$id_user);
		$sql->execute();
		if($sql->rowCount() > 0)
		{
			$sql = $pdo->prepare("DELETE FROM `pedido` WHERE id_user = :u AND id_grupo = :d");
			$sql->bindValue(":u",$id_user);
			$sql->bindValue(":d",$id_grupo);
			$sql->execute();
			return true;
		}else{
			$sql = $pdo->prepare("INSERT INTO pedido (id_user,id_grupo,de)
			VALUES (:u, :d, :e)");
			$sql->bindValue(":u",$id_user);
			$sql->bindValue(":d",$id_grupo);
			$sql->bindValue(":e",$de);
			$sql->execute();
			return true;
		}
	}
	public function comfirmar_membro($user,$id_grupo)
	{
		global $pdo;

	    $sql = $pdo->prepare("DELETE FROM `pedido` WHERE id_user = :u AND id_grupo = :d");
		$sql->bindValue(":u",$user);
		$sql->bindValue(":d",$id_grupo);
		$sql->execute();

		$sql = $pdo->prepare("INSERT INTO membros_grupo (id_user,id_grupo)
		VALUES (:u, :d)");
		$sql->bindValue(":u",$user);
		$sql->bindValue(":d",$id_grupo);
		$sql->execute();

		return true;

	}
	public function enviar_sms($id_user,$id_destino,$texto,$imagem)
	{
		global $pdo;
		$sql = $pdo->prepare("INSERT INTO bate_papo (id_user,id_user_dest,texto,data,indereco)
		VALUES (:u, :d, :t, NOW(), :i)");
		$sql->bindValue(":u",$id_user);
		$sql->bindValue(":d",$id_destino);
		$sql->bindValue(":t",$texto);
		$sql->bindValue(":i",$imagem);
		$sql->execute();

		header("location: conv_next.php");

		return true;
	}
	public function contar_sms_nao_lido($id,$id_user)
	{
		global $pdo;
		$sql = $pdo->prepare("SELECT * FROM bate_papo WHERE id_user= :d AND id_user_dest= :u AND visto=''");
		$sql->bindValue(":u",$id_user);
		$sql->bindValue(":d",$id);
		$sql->execute();
		$_SESSION['sms_nao_lido'] = $sql->rowCount();
		return true;
	}
	public function marcar_sms_visto($id_destino,$id_user)
	{
		global $pdo;
		$sql = $pdo->prepare("UPDATE `bate_papo` SET `visto` = 'sim' WHERE id_user= :d AND id_user_dest= :u AND visto=''");
		$sql->bindValue(":u",$id_user);
		$sql->bindValue(":d",$id_destino);
		$sql->execute();
		return true;
	}
	public function contar_sms_nao_lido_todo($id_user)
	{
		global $pdo;
		$sql = $pdo->prepare("SELECT * FROM bate_papo WHERE id_user_dest= :u AND visto=''");
		$sql->bindValue(":u",$id_user);
		$sql->execute();
		$_SESSION['sms_nao_lido_todo'] = $sql->rowCount();
		return true;
	}
	public function novo_grupo($id_user,$nome,$descricao,$visibilidade,$tipo)
	{
		global $pdo;

		$sql = $pdo->prepare("SELECT id_grupo FROM grupos WHERE id_user = :u AND nome = :n");
		$sql->bindValue(":u",$id_user);
		$sql->bindValue(":n",$nome);
		$sql->execute();
		if($sql->rowCount() > 0)
		{
			return false;
		}else{
			$sql = $pdo->prepare("INSERT INTO grupos (id_user,nome,descricao,visibilidade,tipo,data)
			VALUES (:u, :n, :d, :v, :t, NOW())");
			$sql->bindValue(":u",$id_user);
			$sql->bindValue(":n",$nome);
			$sql->bindValue(":d",$descricao);
			$sql->bindValue(":v",$visibilidade);
			$sql->bindValue(":t",$tipo);
			$sql->execute();
			header("location: usuarios.php");
			return true;
		}		
	}
	public function reagir($id_pbl,$id_user)
	{
		global $pdo;
		$tipo = $_SESSION['tipo'];

		if ($tipo == "gosto") {
			$sql = $pdo->prepare("SELECT * FROM reacao WHERE id_pbl = :p AND id_user = :u");
			$sql->bindValue(":u",$id_user);
			$sql->bindValue(":p",$id_pbl);
			$sql->execute();
			if($sql->rowCount() > 0)
			{
				$sql = $pdo->prepare("DELETE FROM `reacao` WHERE id_user = :u AND id_pbl = :p");
				$sql->bindValue(":u",$id_user);
				$sql->bindValue(":p",$id_pbl);
				$sql->execute();
				return true;
			}else{
				$sql = $pdo->prepare("INSERT INTO reacao (id_user,id_pbl,tipo)
				VALUES (:u, :n, :d)");
				$sql->bindValue(":u",$id_user);
				$sql->bindValue(":n",$id_pbl);
				$sql->bindValue(":d","gosto");
				$sql->execute();
				return true;
			}
		}
		if ($tipo == "adoro") {
			$sql = $pdo->prepare("SELECT * FROM reacao WHERE id_pbl = :p AND id_user = :u");
			$sql->bindValue(":u",$id_user);
			$sql->bindValue(":p",$id_pbl);
			$sql->execute();
			if($sql->rowCount() > 0)
			{
				$sql = $pdo->prepare("DELETE FROM `reacao` WHERE id_user = :u AND id_pbl = :p");
				$sql->bindValue(":u",$id_user);
				$sql->bindValue(":p",$id_pbl);
				$sql->execute();
				return true;
			}else{
				$sql = $pdo->prepare("INSERT INTO reacao (id_user,id_pbl,tipo)
				VALUES (:u, :n, :d)");
				$sql->bindValue(":u",$id_user);
				$sql->bindValue(":n",$id_pbl);
				$sql->bindValue(":d","adoro");
				$sql->execute();
				return true;
			}
		}
	}
	public function contar_gosto($id_pbl)
	{
		global $pdo;
		$sql = $pdo->prepare("SELECT * FROM reacao WHERE id_pbl = :p AND tipo = 'gosto'");
		$sql->bindValue(":p",$id_pbl);
		$sql->execute();
		$_SESSION['gosto'] = $sql->rowCount();
	}
	public function contar_adoro($id_pbl)
	{
		global $pdo;
		$sql = $pdo->prepare("SELECT * FROM reacao WHERE id_pbl = :p AND tipo = 'adoro'");
		$sql->bindValue(":p",$id_pbl);
		$sql->execute();
		$_SESSION['adoro'] = $sql->rowCount();
	}
	public function contar_comentario($id_pbl)
	{
		global $pdo;
		$sql = $pdo->prepare("SELECT * FROM comentario WHERE id_pbl = :p");
		$sql->bindValue(":p",$id_pbl);
		$sql->execute();
		$_SESSION['comentario'] = $sql->rowCount();
	}
	public function contar_gosto_comentario($id_cmt)
	{
		global $pdo;
		$sql = $pdo->prepare("SELECT * FROM reacao WHERE id_cmt = :p AND tipo = 'gosto'");
		$sql->bindValue(":p",$id_cmt);
		$sql->execute();
		$_SESSION['gosto_c'] = $sql->rowCount();
	}
	public function contar_resposta($id_cmt)
	{
		global $pdo;
		$sql = $pdo->prepare("SELECT * FROM comentario WHERE id_cmt = :p");
		$sql->bindValue(":p",$id_cmt);
		$sql->execute();
		$_SESSION['resposta'] = $sql->rowCount();
	}



	#de agora em diante a formtação feita sera para a academia


	public function criar_academia($id_user,$nome,$area,$disponibilidade,$localizacao)
	{
		global $pdo;

		$sql = $pdo->prepare("SELECT id_cdm FROM academia WHERE nome = :n");
		$sql->bindValue(":n",$nome);
		$sql->execute();
		
		if($sql->rowCount() > 0)
		{
			return false;
		}
		else
		{
			$sql = $pdo->prepare("INSERT INTO academia (id_user,nome,area,disponibilidade,localizacao,data) 
			VALUES (:iu, :n, :a, :d, :l, NOW())");
			$sql->bindValue(":n",$nome);
			$sql->bindValue(":iu",$id_user);
			$sql->bindValue(":a",$area);
			$sql->bindValue(":d",$disponibilidade);
			$sql->bindValue(":l",$localizacao);
			$sql->execute();
				
			$sql = $pdo->prepare("SELECT * FROM `academia` WHERE nome=:n");
			$sql->bindValue(":n",$nome);
			$sql->execute();

			$dado = $sql->fetch();
			session_start();
			$_SESSION['id_cdm'] = $dado['id_cdm'];
			return true;
		}
	}
}
?>