<?php

define('BOT_TOKEN', '1946698638:AAFYoGL1j85v_thS7YwIHJ3Kspzal_qeepI');
define('API_URL', 'https://api.telegram.org/bot' . BOT_TOKEN . '/');

function processMessage($message){  // processa a mensagem recebida
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {

    $text = $message['text']; //texto recebido na mensagem

    if (strpos($text, "/start") === 0) {
      //envia a mensagem ao usuário
      sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Olá, @' . $message['from']['username'] .
        ' !', 'reply_markup' => array(
        'keyboard' => array(array('Geelpa', 'Cristian'), array('Igor', 'Enzo')),
        'one_time_keyboard' => true, 'selective' => true
      )));
    } else if ($text == "Enzo") {
      sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'O cristian finge que não vê TikTok, mas ele canta todas as musiquinhas.'));
    } else {
      sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Desculpe, mas não entendi essa mensagem. :('));
    }
  } else {
    sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Desculpe, mas só compreendo mensagens em texto'));
  }
}

function sendMessage($method, $parameters) {
  $options = array(
    'http' => array(
      'method'  => 'POST',
      'content' => json_encode($parameters),
      'header' =>  "Content-Type: application/json\r\n" .
        "Accept: application/json\r\n"
    )
  );

  $context  = stream_context_create($options);
  file_get_contents(API_URL . $method, false, $context);
}

/*Com o webhook setado, não precisamos mais obter as mensagens através do método getUpdates.Em vez disso, 
* como o este arquivo será chamado automaticamente quando o bot receber uma mensagem, utilizamos "php://input"
* para obter o conteúdo da última mensagem enviada ao bot. 
*/
$update_response = file_get_contents("php://input");

$update = json_decode($update_response, true);

if (isset($update["message"])) {
  processMessage($update["message"]);
}
