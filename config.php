<?php
    //Conexão com o banco
    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "salao";

    //criando conexão
    $conexao = mysqli_connect($servidor, $usuario, $senha, $banco);

//verificando conexão
    if($conexao ->connect_error){
        die("Conexão falhou: ". $conexao->connect_error);
    }
//echo "Conexão realizada com sucesso!";

?>
