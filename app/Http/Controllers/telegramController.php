<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;

class telegramController extends Controller
{
    //
    // protected $telegram;
    // public function __construct()
    // {
    //     $this->telegram = new Api(config('services.telegram-bot-api.token'));
    // }
    // public function webhook(Request $request)
    // {
    //     // $update = $this->telegram->getWebhookUpdates();
    //     // $message = $update->getMessage();
    //     // $chatId = $message->getChat()->getId();

    //     $update = Telegram::getWebhookUpdates();

    //     $message = $update->getMessage();
    //     $chatId = $message->getChat()->getId();
    //     $text = $message->getText();

    //     // Logika untuk menangani pesan
    //     $responseText = "You said: " . $text;
    //     Telegram::sendMessage([
    //         'chat_id' => $chatId,
    //         'text' => $responseText
    //     ]);

    //     return response()->json(['status' => 'success']);
    // }

    // public function sendMessage(Request $request)
    // {
    //     $token = env('TELEGRAM_BOT_TOKEN');
    //     $chatId = $request->input('chat_id');
    //     $message = $request->input('message');

    //     $client = new Client();
    //     $response = $client->post("https://api.telegram.org/bot{$token}/sendMessage", [
    //         'json' => [
    //             'chat_id' => $chatId,
    //             'text' => $message,
    //         ],
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'response' => json_decode($response->getBody()->getContents(), true),
    //     ]);
    // }

    protected $telegram;

    public function __construct()
    {
        $this->telegram = new Api(config('services.telegram.bot_token'));
    }

    public function webhook(Request $request)
    {
        $update = json_decode(file_get_contents('php://input'), true);

        if (isset($update['message'])) {
            $message = $update['message']['text'];
            $chatId = $update['message']['chat']['id'];

            // Mendapatkan status interaksi pengguna dari sesi
            $status = session()->get('status_menu' . $chatId, '');

            if ($message == '/1') {
                $this->sendMessageToChat($chatId, "You said 1: $message $status");
            } else {
                // Update status dengan menambahkan 'A'
                $status .= 'A';
                session()->put('status_menu' . $chatId, $status);

                $this->sendMessageToChat($chatId, "You said: $message");
            }
        }

        return response('OK', 200);
    }

    private function sendMessageToChat($chatId, $text)
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}
