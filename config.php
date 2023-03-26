<?php

//  fetch function to send requestes to the server
    function fetchApi($url){
        $req = curl_init($url);

        $resp = curl_exec($req);

        if($resp == false){
            $error = curl_error($req);
            curl_close($req);
            throw new ErrorException($error);
        }
        else{
            curl_close($req);
            return $resp;
        }
    }


    class Telegram{
        protected $url;

        function __construct($token){
            $this->url = "https://api.telegram.org/bot".$token;
        }

//      set method
        private function setUrl($method){
            return $this->url."/".$method;
        }

//      set webhook
        public function setWebhook($ngrokUrl){
            $data = array(
                "url" => $ngrokUrl
            );

            $url = $this->setUrl("setWebhook?".http_build_query($data));

            fetchApi($url);
        }

//      delete the webhook
        public function deleteWebhook(){
            $url = $this->setUrl("deleteWebhook");

            fetchApi($url);
        }

//      getMe
        public function getMe(){
            $url = $this->setUrl("getMe");

            fetchApi($url);
        }

//      getUpdates (if you want to try)
        public function getUpdates(){
            $url = $this->setUrl("getUpdates");

            fetchApi($url);
        }

//      send message
        public function sendMessage($chatid, $message){
            $data = array(
                "chat_id" => $chatid,
                "text" => $message
            );

            $url = $this->setUrl("sendMessage?".http_build_query($data));

            fetchApi($url);
        }

//      send keyboard
        public function sendKeyboard($chatid, $text, $keyboard){

            $keyboard = json_encode($keyboard);

            $data = array(
                "chat_id" => $chatid,
                "text" => $text,
                "reply_markup" => $keyboard
            );

            $url = $this->setUrl("sendMessage?".http_build_query($data));

            fetchApi($url);
        }

//      send photo
        public function sendPhoto($chatid, $photo){
            $data = array(
                "chat_id" => $chatid,
                "photo" => $photo
            );

            $url = $this->setUrl("sendPhoto?".http_build_query($data));
            fetchApi($url);
        }
    }

//  class for json
    class jsonHandler extends Telegram{
//      take the json of a request and decode it
        function getWebhookJson(){
            $json = file_get_contents('php://input');

            //file_put_contents("data.json", $json."\n", FILE_APPEND);

            return json_decode($json, true);
        }
//      take the chat id when a message arrives
        function getChatId($jsonDecoded){

            if(isset($jsonDecoded["message"]["chat"]["id"])){
                return $jsonDecoded["message"]["chat"]["id"];
            }
            return;
        }
//      take the text of message 
        function getText($jsonDecoded){

            if(isset($jsonDecoded["message"]["text"])){
                return $jsonDecoded["message"]["text"];
            }
            return;
        }
//      take the callback data of the button
        function getCallbackData($jsonDecoded){
            //file_put_contents("data.json", "dataCall:".$jsonDecoded["callback_query"]["data"]."\n", FILE_APPEND);
            if(isset($jsonDecoded["callback_query"]["data"])){
                return $jsonDecoded["callback_query"]["data"];
            }
            return;
        }
//      take the chat id when a button is pressed
        function getCallbackChatid($jsonDecoded){
            //file_put_contents("data.json", "idCall:".$jsonDecoded["callback_query"]["message"]["chat"]["id"]."\n", FILE_APPEND);

            if(isset($jsonDecoded["callback_query"]["message"]["chat"]["id"])){
                return $jsonDecoded["callback_query"]["message"]["chat"]["id"];
            }
            return;
        }
    }
?>