<?php
$entrada = time();
//pega o ip
$ip = $_SERVER['REMOTE_ADDR'];

//Se o usuario ficar 3 minuto inativo deleta
$exe_delete = mysqli_query($link, "DELETE FROM sessao WHERE ('$entrada' - ult_click) / 60 >= 5");

$exe_busca = mysqli_query($link,"SELECT * FROM sessao WHERE id_user = '$id_user'");
$num_busca = mysqli_num_rows($exe_busca);

// incluir
if ($num_busca == 0){
   $sql_inclu = "INSERT INTO sessao(entrada, id_user, ult_click, ip) VALUES
               ('$entrada', '$id_user', '$entrada', '$ip')";
   $exe_inclu = mysqli_query($link,"$sql_inclu");
}
else {
//Altera
   $exe_up = mysqli_query($link, "UPDATE sessao SET ult_click = '$entrada' WHERE sessao = '$id_user'");
}
//verifica quantos usuarios estão online
$num_online = 0;
$exe_online = mysqli_query($link, "SELECT * FROM sessao");
while ($num_online_total = mysqli_fetch_assoc($exe_online)) {
   $sessao = $num_online_total['id_user'];
   $amigos = mysqli_query($link, "SELECT * from `amigo` WHERE id_user = '$id_user' AND id_dest='$sessao'");
   $amigo = mysqli_fetch_assoc($amigos);
   if (isset($amigo['id_amigo'])) {
      $num_online++;
   }
}
?>