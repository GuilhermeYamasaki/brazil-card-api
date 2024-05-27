<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento criado/pendente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            color: #555;
            line-height: 1.6;
        }

        strong {
            color: #007bff;
        }

        a {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
        }

        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Olá, {{ $data['senderName'] }}</h1>

        <p>Estamos felizes em informar que o pagamento de <strong>R$ {{ number_format($data['amount'], 2, ',', '.') }}</strong> para {{ $data['recipientName'] }}
            foi criado com sucesso e está pendente de pagamento.
        </p>
        <p>Você pode realizar o pagamento clicando no link abaixo:</p>

        <p><a href="{{ $data['invoiceUrl'] }}">Pagar Agora</a></p>

        <p>Se você tiver qualquer dúvida, sinta-se à vontade para entrar em contato conosco.</p>

        <p>Obrigado por usar o nosso serviço.</p>
    </div>
</body>
</html>
