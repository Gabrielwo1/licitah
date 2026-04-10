<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require __DIR__ .'/bibliotecas/vendor/autoload.php';
use Orhanerday\OpenAi\OpenAi;

session_start();


$open_ai = new OpenAi("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");
//echo '<pre>';
//echo $open_ai->listModels();
//echo '</pre>';

$chat = $open_ai->chat([
   'model' => 'gpt-4-0613',
   'messages' => [
       [
           "role" => "system",
           "content" => "Qual o seu nome?"
       ],
       [
           "role" => "user",
           "content" => "Me chamo Lucas Gabriel Coelho e tenho uma equipe de redatores, designers e programadores."
       ],
       [
           "role" => "assistant",
           "content" => "Interessante, em que posso te ajudar Lucas?"
       ],
       [
           "role" => "user",
           "content" => "Quero me candidatar para uma proposta freelancer  para um projeto."
       ],
        [
           "role" => "assistant",
           "content" => "Descreva o título do projeto"
       ],
       [
           "role" => "user",
           "content" => "Desenvolvimento de landingpages criativas de alta conversão para vendas de plataformas"
       ],
         [
           "role" => "assistant",
           "content" => "Agora descreva o corpo do projeto"
       ],
       [
           "role" => "user",
           "content" => "Minha empresa vende algumas plataformas para a internet no setor de cassino, igaming e jogos online. E não temos landingpage nem nada para atrair os clientes. Procuro alguém que possa desenvolver uma página de alta qualidade, efeitos imagens e animações e interações com o usuário. "
       ],
       [
           "role" => "assistant",
           "content" => "Qual o nome do cliente?"
       ],
        [
           "role" => "user",
           "content" => "Hugo S."
       ],
         [
           "role" => "assistant",
           "content" => "O que devo fazer agora?"
       ],
       [
           "role" => "user",
           "content" => "Monte uma proposta, explicando por que sou a melhor opção, com no máximo, 200 palavras e de forma profissional, focado na demanda solicitada do cliente."
       ],
   ],
   'temperature' => 1.0,
   'max_tokens' => 4000,
   'frequency_penalty' => 0,
   'presence_penalty' => 0,
]);


var_dump($chat);
echo "<br>";
echo "<br>";
echo "<br>";
// decode response
$d = json_decode($chat);
// Get Content
echo($d->choices[0]->message->content);