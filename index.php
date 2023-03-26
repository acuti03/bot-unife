<?php

    require("token.php");
    require("config.php");


    try{
        $bot = new Telegram($token);
        $jsonHandler = new JsonHandler($token);
        $ngrokUrl = "Enter your webhook URL here";

//      set webhook
        $bot->setWebhook($ngrokUrl);
        $webhookJson = $jsonHandler->getWebhookJson();
        //$bot->deleteWebhook();

//      get chatid and text
        $chatid = $jsonHandler->getChatId($webhookJson);
        $userText = strtolower($jsonHandler->getText($webhookJson));

//      get callback data and chat id from the telegram keyboard
        $callbackData = $jsonHandler->getCallbackData($webhookJson);
        $callbackChatid = $jsonHandler->getCallbackChatid($webhookJson);

//      switch for commands
        switch($userText){
            case '/start':{
                $bot->sendMessage($chatid, "Ciao, ti do il benvenuto, per vedere le funzionalità di questo bot digita:\n/help");
                break;
            }

            case '/help':{
                $bot->sendMessage($chatid, "Questo bot serve per visualizzare le lezioni del corso di informatica di unife. Per vedere i giorni digita:\n/giorni\nPer vedere i link digita:\n/link");
                break;
            }

            case '/giorni':{
                
                $keyboard = [
                    "inline_keyboard" => [
                        [
                            [
                                "text" => "Lunedì 💩",
                                "callback_data" => "lun"
                            ],
                            [
                                "text" => "Martedì 😵‍💫",
                                "callback_data" => "mar"
                            ],
                        ],
                        [
                            [
                                "text" => "Mercoledì 😮‍💨",
                                "callback_data" => "mer"
                            ],
                            [
                                "text" => "Giovedì 🤤",
                                "callback_data" => "gio"
                            ],
                        ],
    
                    ]
                ];

                $bot->sendKeyboard($chatid, "Scegli il giorno da cui vuoi vedere le materie", $keyboard);

                break;
            }

            case '/link':{
                $keyboard = [
                    "inline_keyboard" => [
                        [
                            [
                                "text" => "Architettura degli elaboratori",
                                "callback_data" => "linkArch"
                            ]
                        ],
                        [
                            [
                                "text" => "Matematica discreta",
                                "callback_data" => "linkMate"
                            ]
                        ],
                        [
                            [
                                "text" => "Probabilità e statistica",
                                "callback_data" => "linkProb"
                            ]
                        ],
                        [
                            [
                                "text" => "Fisica",
                                "callback_data" => "linkFisica"
                            ]
                        ]
                    ]
                ];

                $bot->sendKeyboard($chatid, "Scegli la materia da cui vuoi ottere la meet", $keyboard);

                break;
            }

            case 'gatti':{
                $photo = array(
                    1 => "https://preview.redd.it/6k6tdaezsbg61.png?width=640&crop=smart&auto=webp&s=2a5727ff6a9064d7a18b86e3afa3d901b2364425",
                    2 => "https://placekitten.com/700/700",
                    3 => "https://placekitten.com/400/300",
                    4 => "https://placekitten.com/500/600"
                );

                $randomInteger = rand(1,4);
                $bot->sendPhoto($chatid, $photo[$randomInteger]);

                break;
            }

            default:{
                $bot->sendMessage($chatid, "Non riconosco il comando \n(prova a scrivere: gatti)");

                break;
            }
        }

//      switch for callback
        switch($callbackData){
            case 'lun':{
                $bot->sendMessage($callbackChatid, "Al Lunedi hai:\n\n-matematica discreta⭐️\n-probabilità e statistica🤮\n-architettura degli elaboratori🤨\n-tutorato: probabilità e statistica💀");
                break;
            }

            case 'mar':{
                $bot->sendMessage($callbackChatid, "Al Martedi hai:\n\n-probabilità e statistica🤮\n-fisica💀\n-tutorato: matematica discreta🤨");
                break;
            }

            case 'mer':{
                $bot->sendMessage($callbackChatid, "Al Mercoledi hai:\n\n-matematica discreta⭐️\n-architettura degli elaboratori🤨\n-tutorato: fisica🤮");
                break;
            }

            case 'gio':{
                $bot->sendMessage($callbackChatid, "Al Giovedi hai:\n\n-matematica discreta⭐️\n-fisica💀\n-architettura degli elaboratori🤨");
                break;
            }

            case 'linkArch':{
                $bot->sendMessage($callbackChatid, "Link architettura:\nhttps://meet.google.com/jae-uvky-vck");
                break;
            }
            
            case 'linkMate':{
                $bot->sendMessage($callbackChatid, "Link matematica discreta:\nhttps://meet.google.com/ezr-gbxh-nzx");
                $bot->sendMessage($callbackChatid, "Tutorato:\nhttps://meet.google.com/kaj-uipr-tvy");
                break;
            }

            case 'linkProb':{
                $bot->sendMessage($callbackChatid, "Link probabilità:\nhttps://meet.google.com/gfa-yshv-ufz");
                break;
            }

            case 'linkFisica':{
                $bot->sendMessage($callbackChatid, "Link fisica:\nhttps://meet.google.com/rng-fnih-fvp");
                break;
            }
        }



    }catch(ErrorException $e){
        echo $e->getMessage();
    }
?>