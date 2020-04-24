<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Covid19 Bot</title>
    <script src="https://d.line-scdn.net/liff/edge/2.1/sdk.js"></script>
</head>

<body>
    <?php
    require __DIR__ . '/../vendor/autoload.php';

    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\Factory\AppFactory;

    use \LINE\LINEBot;
    use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
    use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
    use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
    use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
    use \LINE\LINEBot\SignatureValidator as SignatureValidator;

    $pass_signature = true;

    // set LINE channel_access_token and channel_secret
    $channel_access_token = " ";
    $channel_secret = " ";

    // inisiasi objek bot
    $httpClient = new CurlHTTPClient($channel_access_token);
    $bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);

    $app = AppFactory::create();
    $app->setBasePath("/public");

    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Hello World!");
        return $response;
    });

    // buat route untuk webhook
    $app->post('/webhook', function (Request $request, Response $response) use ($channel_secret, $bot, $httpClient, $pass_signature) {
        // get request body and line signature header
        $body = $request->getBody();
        $signature = $request->getHeaderLine('HTTP_X_LINE_SIGNATURE');

        // log body and signature
        file_put_contents('php://stderr', 'Body: ' . $body);

        if ($pass_signature === false) {
            // is LINE_SIGNATURE exists in request header?
            if (empty($signature)) {
                return $response->withStatus(400, 'Signature not set');
            }

            // is this request comes from LINE?
            if (!SignatureValidator::validateSignature($body, $channel_secret, $signature)) {
                return $response->withStatus(400, 'Invalid signature');
            }
        }

        //kode aplikasi nanti disini
        $data = json_decode($body, true);
        if (is_array($data['events'])) {
            foreach ($data['events'] as $event) {
                if ($event['type'] == 'message') {
                    //reply message
                    if ($event['message']['type'] == 'text') {
                        if (strtolower($event['message']['text']) == 'user id') {

                            $result = $bot->replyText($event['replyToken'], $event['source']['userId']);
                        } elseif (strtolower($event['message']['text']) == 'menu') {

                            $flexTemplate = file_get_contents("../flex_message.json"); // template flex message
                            $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
                                'replyToken' => $event['replyToken'],
                                'messages'   => [
                                    [
                                        'type'     => 'flex',
                                        'altText'  => 'Menu',
                                        'contents' => json_decode($flexTemplate)
                                    ]
                                ],
                            ]);
                        } elseif (strtolower($event['message']['text']) == 'covid-19 global') {
                            $flexTemplate = file_get_contents("../flex_message_covidGlobe.json"); // template flex message
                            $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
                                'replyToken' => $event['replyToken'],
                                'messages'   => [
                                    [
                                        'type'     => 'flex',
                                        'altText'  => 'Covid-19 Global',
                                        'contents' => json_decode($flexTemplate)
                                    ]
                                ],
                            ]);
                        } elseif (strtolower($event['message']['text']) == 'covid-19 indonesia') {
                            $flexTemplate = file_get_contents("../flex_message_covidInd.json"); // template flex message
                            $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
                                'replyToken' => $event['replyToken'],
                                'messages'   => [
                                    [
                                        'type'     => 'flex',
                                        'altText'  => 'Covid-19 Indonesia',
                                        'contents' => json_decode($flexTemplate)
                                    ]
                                ],
                            ]);
                        } elseif (strtolower($event['message']['text']) == 'covid-19?') {
                            $flexTemplate = file_get_contents("../flex_message_covid_apa.json"); // template flex message
                            $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
                                'replyToken' => $event['replyToken'],
                                'messages'   => [
                                    [
                                        'type'     => 'flex',
                                        'altText'  => 'Penjelasan Covid-19',
                                        'contents' => json_decode($flexTemplate)
                                    ]
                                ],
                            ]);
                        } elseif (strtolower($event['message']['text']) == 'gejala') {
                            $flexTemplate = file_get_contents("../flex_message_covid_gejala.json"); // template flex message
                            $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
                                'replyToken' => $event['replyToken'],
                                'messages'   => [
                                    [
                                        'type'     => 'flex',
                                        'altText'  => 'Gejala Covid-19',
                                        'contents' => json_decode($flexTemplate)
                                    ]
                                ],
                            ]);
                        } elseif (strtolower($event['message']['text']) == 'melindungi diri dari covid-19') {
                            $flexTemplate = file_get_contents("../flex_message_covid_melindungi.json"); // template flex message
                            $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
                                'replyToken' => $event['replyToken'],
                                'messages'   => [
                                    [
                                        'type'     => 'flex',
                                        'altText'  => 'Cara melindungi diri dari Covid-19',
                                        'contents' => json_decode($flexTemplate)
                                    ]
                                ],
                            ]);
                        } elseif (strtolower($event['message']['text']) == 'call center covid-19') {
                            $flexTemplate = file_get_contents("../flex_message_covid_call.json"); // template flex message
                            $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
                                'replyToken' => $event['replyToken'],
                                'messages'   => [
                                    [
                                        'type'     => 'flex',
                                        'altText'  => 'Call Center Covid-19',
                                        'contents' => json_decode($flexTemplate)
                                    ]
                                ],
                            ]);
                        } elseif (strtolower($event['message']['text']) == 'selesai') {
                            $flexTemplate = file_get_contents("../flex_message_covid_selesai.json"); // template flex message
                            $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
                                'replyToken' => $event['replyToken'],
                                'messages'   => [
                                    [
                                        'type'     => 'flex',
                                        'altText'  => 'Closing Message',
                                        'contents' => json_decode($flexTemplate)
                                    ]
                                ],
                            ]);
                        }   //else {
                        // send same message as reply to user
                        //    $result = $bot->replyText($event['replyToken'], $event['message']['text']);
                        //}
                        else {
                            $message = 'Silakan kirim pesan "MENU" untuk menuju ke menu Covid-19. Atau kirim pesan "Selesai" untuk menyelesaikan perbincangan kita hari ini.';
                            $textMessageBuilder = new TextMessageBuilder($message);
                            $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
                        }


                        // or we can use replyMessage() instead to send reply message
                        // $textMessageBuilder = new TextMessageBuilder($event['message']['text']);
                        // $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);


                        $response->getBody()->write(json_encode($result->getJSONDecodedBody()));
                        return $response
                            ->withHeader('Content-Type', 'application/json')
                            ->withStatus($result->getHTTPStatus());
                    }
                }
            }
        }
        return $response->withStatus(400, 'No event sent!');
    });

    $app->get('/content/{messageId}', function ($req, $response, $args) use ($bot) {
        // get message content

        $messageId = $args['messageId'];
        $result = $bot->getMessageContent($messageId);

        // set response
        $response->getBody()->write($result->getRawBody());

        return $response
            ->withHeader('Content-Type', $result->getHeader('Content-Type'))
            ->withStatus($result->getHTTPStatus());
    });

    $app->get('/pushmessage', function ($req, $response) use ($bot) {
        // send push message to user
        $userId = 'Udccfb84539f8baa99653e5f3f0f26cd8';
        $textMessageBuilder = new TextMessageBuilder('Halo, ini pesan push');
        $result = $bot->pushMessage($userId, $textMessageBuilder);

        $response->getBody()->write("Pesan push berhasil dikirim!");
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($result->getHTTPStatus());
    });

    $app->get('/multicast', function ($req, $response) use ($bot) {
        // list of users
        $userList = [
            'Udccfb84539f8baa99653e5f3f0f26cd8',
            'Isi dengan user ID teman1',
            'Isi dengan user ID teman2',
            'dst'
        ];

        // send multicast message to user
        $textMessageBuilder = new TextMessageBuilder('Halo, ini pesan multicast');
        $result = $bot->multicast($userList, $textMessageBuilder);


        $response->getBody()->write("Pesan multicast berhasil dikirim!");
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($result->getHTTPStatus());
    });

    $app->get('/profile/{userId}', function ($req, $response, $args) use ($bot) {
        // get user profile
        $userId = $args['userId'];
        $result = $bot->getProfile($userId);

        $response->getBody()->write(json_encode($result->getJSONDecodedBody()));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($result->getHTTPStatus());
    });

    $app->run();
    ?>

</body>

</html>