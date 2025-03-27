<?php
// Conectar ao banco de dados
$servername = "localhost"; 
$username = "pagoun00_cadata"; 
$password = "Trampo010203@"; 
$dbname = "pagoun00_cadata"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $como_conheceu = $_POST['como_conheceu'];
    $quem_mandou = $_POST['quem_mandou'];
    $contato = $_POST['contato'];

    // Inserir os dados no banco de dados
    $sql = "INSERT INTO clientes (nome, como_conheceu, quem_mandou, contato) 
            VALUES ('$nome', '$como_conheceu', '$quem_mandou', '$contato')";

    if ($conn->query($sql) === TRUE) {
        // Criar a mensagem para o WhatsApp
        $mensagem = "📝 *Novo Cliente Apresentado:*\n\n"
                  . "👤 Nome: *$nome*\n"
                  . "📌 Como Conheceu: *$como_conheceu*\n"
                  . "🔗 Quem Indicou: *$quem_mandou*\n"
                  . "📱 Contato: *$contato*\n\n"
                  . "✅ Dados registrados com sucesso!";

        // Número do grupo ou link do grupo (substitua pelo seu)
        $numeroGrupo = "SEU_NUMERO_DE_GRUPO"; // 🔴 Substituir pelo número ou link do grupo

        // Criar link do WhatsApp
        $linkWhatsApp = "https://api.whatsapp.com/send?phone=" . $numeroGrupo . "&text=" . urlencode($mensagem);

        // Redirecionar para o WhatsApp
        header("Location: $linkWhatsApp");
        exit();
    } else {
        echo "Erro ao salvar os dados: " . $conn->error;
    }
}

// Fechar conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Apresentação</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 40px auto;
            background: url('fundo-container.jpg') no-repeat center center;
            background-size: cover;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
            font-size: 24px;
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
            color: #444;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #4CAF50;
            outline: none;
            background-color: #fff;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .resumo {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }

            button {
                font-size: 16px;
            }
        }
    </style>

    <script>
        function mostrarResumo(event) {
            event.preventDefault();

            var nome = document.getElementById("nome").value;
            var comoConheceu = document.getElementById("como_conheceu").value;
            var quemMandou = document.getElementById("quem_mandou").value;
            var contato = document.getElementById("contato").value;

            var resumo = `
                📝 *Resumo do Cadastro:*\n
                👤 Nome: *${nome}*\n
                📌 Como Conheceu: *${comoConheceu}*\n
                🔗 Quem Indicou: *${quemMandou}*\n
                📱 Contato: *${contato}*\n
                \n✅ Confirme antes de enviar.
            `;

            document.getElementById("resumo_dados").innerText = resumo;
            document.getElementById("resumo_container").style.display = "block";
        }

        function confirmarEnvio() {
            document.getElementById("formulario").submit();
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Formulário de Apresentação</h2>
    <form id="formulario" action="formulario.php" method="POST" onsubmit="mostrarResumo(event);">
        <label for="nome">Seu Nome</label>
        <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>

        <label for="como_conheceu">Como você conseguiu nosso número?</label>
        <input type="text" id="como_conheceu" name="como_conheceu" placeholder="Exemplo: Google, Indicação, etc." required>

        <label for="quem_mandou">Quem te mandou nosso número?</label>
        <input type="text" id="quem_mandou" name="quem_mandou" placeholder="Nome da pessoa que indicou" required>

        <label for="contato">Seu Contato</label>
        <input type="text" id="contato" name="contato" placeholder="Exemplo: +55 11 91234-5678" required>

        <button type="submit">Ver Resumo</button>
    </form>

    <div id="resumo_container" class="resumo hidden">
        <pre id="resumo_dados"></pre>
        <button onclick="confirmarEnvio()">Confirmar e Enviar</button>
    </div>
</div>

</body>
</html>

