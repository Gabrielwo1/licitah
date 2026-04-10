<?
use Kreait\Firebase\Factory;

$factory = (new Factory)->withServiceAccount('/path/to/your-firebase-adminsdk.json');
$messaging = $factory->createMessaging();

$message = [
    'notification' => [
        'title' => 'Título da Notificação',
        'body' => 'Corpo da notificação.',
    ],
    'token' => 'o_token_de_registro_do_dispositivo',
];

$messaging->send($message);

?>