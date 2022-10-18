
					<table class="int">
						<?php
						if (empty($pos)) {
							$pos = "./";
						}
						$u->conectar("glou","localhost","root","");         
						$u->contar_gosto($id_pbl);
						
						$u->conectar("glou","localhost","root","");         
						$u->contar_adoro($id_pbl);

						$u->conectar("glou","localhost","root","");         
						$u->contar_comentario($id_pbl);
						?>
						<tr>
							<td class="pk">
							<form method="POST" action="<?=$pos?>estado.php">
									<input style="display: none;" type="number" name="id_pbl" value="<?=$row['id_pbl']?>">
									<input style="display: none;" type="number" name="gosto" value="gosto">
								   <button name="btn" value=""><span id="gosto"><?=$_SESSION['gosto']?></span><img src="<?=$pos?>../img/gosto.png" alt=""></button>
								</form> 
							</td>
							<td class="pk">
							<form method="POST" action="<?=$pos?>estado.php">
									<input style="display: none;" type="number" name="id_pbl" value="<?=$row['id_pbl']?>">
									<input style="display: none;" type="number" name="adoro" value="adoro">
								   <button name="btn" value=""><span id="adoro"><?=$_SESSION['adoro']?></span><img src="<?=$pos?>../img/adoro.png" alt=""></button>
								</form>
							</td>
							<td class="pk">
								<a class="link2" href="<?=$pos?>carregamento/publicar/comentar/?pbl=<?=$row['id_pbl']?>">
								   <span id="comentario"><?=$_SESSION['comentario']?></span><img src="<?=$pos?>../img/comentario.png" alt="">
								</a>
							 </td>
							<td >:<?=$row['data']?></td>
						</tr>
					</table>