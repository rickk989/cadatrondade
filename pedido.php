<?php
// Conectar ao banco de dados
$servername = "localhost"; 
$username = "pagoun00_cadata"; 
$password = "Trampo010203@"; 
$dbname = "pagoun00_cadata"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido de Venda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: url('fundo-container.jpg') no-repeat center center;
            background-size: cover;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        select, input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .hidden {
            display: none;
        }

        button {
            margin-top: 15px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .resumo {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
        }

        #confirmar {
            background-color: #f39c12;
        }

        #confirmar:hover {
            background-color: #e67e22;
        }
    </style>

    <script>
        function toggleFields() {
            var tipoCompra = document.getElementById("tipo_compra").value;
            document.getElementById("placas_group").style.display = (tipoCompra === "atacado") ? "block" : "none";
            document.getElementById("quantidade_group").style.display = (tipoCompra === "varejo") ? "block" : "none";

            var metodoEntrega = document.getElementById("metodo_entrega").value;
            document.getElementById("endereco_group").style.display = (metodoEntrega === "entrega") ? "block" : "none";
            document.getElementById("referencia_group").style.display = (metodoEntrega === "entrega") ? "block" : "none";

            var pagamento = document.getElementById("pagamento").value;
            document.getElementById("troco_group").style.display = (pagamento === "dinheiro") ? "block" : "none";
        }

        function calcularResumo() {
            var produto = document.getElementById("produto").value;
            var tipoCompra = document.getElementById("tipo_compra").value;
            var quantidade = tipoCompra === "varejo" ? parseInt(document.getElementById("quantidade").value) || 0 : parseInt(document.getElementById("placas").value) * 100;
            var metodoEntrega = document.getElementById("metodo_entrega").value;
            var pagamento = document.getElementById("pagamento").value;
            var troco = (pagamento === "dinheiro") ? document.getElementById("troco").value : "Não precisa";

            var precos = {
                "Melt": 100,
                "Nug": 100,
                "Dry": 50,
                "Flor Colombiana": 50
            };

            var precoTotal = quantidade * precos[produto];

            var resumo = `
                🛒 *Resumo do Pedido:*\n
                📌 Produto: *${produto}*\n
                📦 Tipo: *${tipoCompra}*\n
                ⚖️ Quantidade: *${quantidade}g*\n
                💰 Total: *R$ ${precoTotal.toFixed(2)}*\n
                🚚 Método: *${metodoEntrega}*\n
            `;

            if (metodoEntrega === "entrega") {
                var endereco = document.getElementById("endereco").value;
                var referencia = document.getElementById("referencia").value;
                resumo += `📍 Endereço: *${endereco}*\n📌 Referência: *${referencia}*\n`;
            }

            resumo += `💳 Pagamento: *${pagamento}*\n`;
            if (pagamento === "dinheiro") resumo += `💵 Troco: *${troco}*\n`;

            resumo += "\n✅ Confirme antes de enviar.";

            document.getElementById("resumo_pedido").innerText = resumo;
            document.getElementById("resumo_container").style.display = "block";
        }

        function confirmarEnvio() {
            var mensagem = document.getElementById("resumo_pedido").innerText;
            var numeroGrupo = "18981500400"; // Substitua pelo número do grupo ou do destinatário
            var linkWhatsApp = "https://api.whatsapp.com/send?phone=" + numeroGrupo + "&text=" + encodeURIComponent(mensagem);
            window.location.href = linkWhatsApp;
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Pedido de Venda</h2>
    <form oninput="calcularResumo();">
        <label>Produto:</label>
        <select id="produto" required>
            <option value="Melt">Melt - R$ 100,00/g</option>
            <option value="Nug">Nug - R$ 100,00/g</option>
            <option value="Dry">Dry - R$ 50,00/g</option>
            <option value="Flor Colombiana">Flor Colombiana - Apenas acima de 50g</option>
        </select>

        <label>Varejo ou Atacado:</label>
        <select id="tipo_compra" onchange="toggleFields()" required>
            <option value="varejo">Varejo</option>
            <option value="atacado">Atacado</option>
        </select>

        <div id="quantidade_group">
            <label>Quantidade (g):</label>
            <input type="number" id="quantidade" min="1">
        </div>

        <div id="placas_group" class="hidden">
            <label>Quantidade de Placas (100g cada):</label>
            <input type="number" id="placas" min="1">
        </div>

        <label>Método de Entrega:</label>
        <select id="metodo_entrega" onchange="toggleFields()" required>
            <option value="retirada">Retirada</option>
            <option value="entrega">Entrega</option>
        </select>

        <div id="endereco_group" class="hidden">
            <label>Endereço:</label>
            <input type="text" id="endereco">
        </div>

        <div id="referencia_group" class="hidden">
            <label>Ponto de Referência:</label>
            <input type="text" id="referencia">
        </div>

        <label>Forma de Pagamento:</label>
        <select id="pagamento" onchange="toggleFields()" required>
            <option value="dinheiro">Dinheiro</option>
            <option value="cartao">Cartão</option>
        </select>

        <div id="troco_group" class="hidden">
            <label>Troco (Se necessário):</label>
            <input type="number" id="troco" min="1">
        </div>
    </form>

    <!-- Resumo do Pedido -->
    <div id="resumo_container" class="resumo">
        <pre id="resumo_pedido"></pre>
        <button id="confirmar" onclick="confirmarEnvio()">Confirmar e Enviar</button>
    </div>
</div>

</body>
</html>

