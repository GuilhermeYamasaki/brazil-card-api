<!DOCTYPE html>
<html>
<head>
    <title>Pagamento estornado</title>
</head>
<body>
    <h1>Olá, {{ $data['senderName'] }}</h1>

    <p>Estamos enviando este e-mail para informar que o pagamento de <strong>R$ {{ number_format($data['amount'], 2, ',', '.') }}</strong> para {{ $data['recipientName'] }}
        foi estornado com sucesso.
    </p>
    <p>Você pode visualizar o recibo da transação clicando no link abaixo:</p>

    <p><a href="{{ $data['transactionReceiptUrl'] }}">Ver Recibo</a></p>

    <p>Se você tiver qualquer dúvida, sinta-se à vontade para entrar em contato conosco.</p>

    <p>Obrigado por usar o nosso serviço.</p>
</body>
</html>
