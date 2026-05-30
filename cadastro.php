<?php
//incluindo arquivo de configuração na nossa página
    require_once 'config.php';

//Verificar se recebemos os dados vindos do formulario e se o metodo é post
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//Se o método for post, vai receber os dados
    $nome = trim($_POST['nome']);
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

// validação dos dados
    if(empty($nome) || empty($email) || empty($telefone)){
        $erro = "Por favor, preencha todos os campos.";
       
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Por favor, insira um email válido.";
        
    } elseif (!preg_match("/^[0-9]+$/", $telefone)) {
        $erro = "Por favor, insira um telefone válido (apenas números).";

    } else {
    // Preparar o comando  SQL para inserir os dados
    $stmt = $conexao->prepare("INSERT INTO clientess (nome, email, telefone) VALUES (?, ?, ?)"); 
    $stmt->bind_param("sss", $nome, $email, $telefone); // "sss" indica que os parâmetros são strings

        // executar esse comando SQL e oq acontece depois 
        if($stmt->execute()){
            $sucesso = "Cadastro realizado com sucesso!";
        } else{
            $erro = "Erro ao cadastrar: " . $stmt->error;
        }

        // fechar conexão
        $stmt->close();
    } 
}

?>
