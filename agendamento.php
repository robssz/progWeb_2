<?php
//incluindo arquivo de configuração na nossa página
    require_once 'config.php';

//Verificar se recebemos os dados vindos do formulario e se o metodo é post
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//Se o método for post, vai receber os dados
    $nome = trim($_POST['nome']);
    // buscar o id do cliente com base no nome fornecido
    $sqlCliente = "SELECT id FROM clientess WHERE nome = ?";
    $stmtCliente = $conexao->prepare($sqlCliente);
    $stmtCliente->bind_param("s", $nome);
    $stmtCliente->execute();
    $resultadoCliente = $stmtCliente->get_result();
    $dadosCliente = $resultadoCliente->fetch_assoc();

    if (!$dadosCliente) {

    $erro = "Cliente não encontrado. É preciso cadastrar o cliente antes de agendar um serviço.";
    } else{
        $cliente_id = $dadosCliente['id'];
    }
    

    $servico_id = $_POST['servico'];
    $data_agendamento = $_POST['data_agendamento'];
    $horario_id = $_POST['hora'];
    $observacoes = trim($_POST['observacoes']);

// validação dos dados
    if(empty($nome) || empty($servico_id) || empty($data_agendamento) || empty($horario_id)){
        echo "Por favor, preencha todos os campos.";
        exit;
    } 
    elseif ($data_agendamento < date('Y-m-d')) {// validar se a data não é anterior ao dia atual

        echo "Não é possível agendar datas passadas.";
        exit;

    }

    if (!isset($erro)) {
    // verificar se já existe agendamento
    // para o mesmo serviço
    // no mesmo horário
    // na mesma data

    $sqlVerificacao = "
        SELECT id
        FROM agendamentos
        WHERE data_agendamento = ?
        AND horario_id = ?
        AND servico_id = ?
    ";

    $stmtVerificacao = $conexao->prepare($sqlVerificacao);

    $stmtVerificacao->bind_param(
        "sii",
        $data_agendamento,
        $horario_id,
        $servico_id

    );

    $stmtVerificacao->execute();
    $resultado = $stmtVerificacao->get_result();

    if ($resultado->num_rows > 0) {

        echo "Esse serviço já possui agendamento nesse horário.";
        //exit;

    }
    else {

    // Preparar o comando  SQL para inserir os dados
   $stmt = $conexao->prepare("INSERT INTO agendamentos(cliente_id, servico_id, data_agendamento, horario_id, observacoes) VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "iisis",
        $cliente_id,
        $servico_id,
        $data_agendamento,
        $horario_id,
        $observacoes
    );

        // executar esse comando SQL e oq acontece depois 
        if($stmt->execute()){
            echo "Agendamento realizado com sucesso!";
        } else{
            echo "Erro ao agendar: " . $stmt->error;
        }

        // fechar conexão
        $stmt->close();
    }

    }
    
}

?>
