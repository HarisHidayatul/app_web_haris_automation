<?php

namespace App\Http\Controllers;

use App\Models\telegram_user;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        Log::info('Webhook called');

        $update = json_decode(file_get_contents('php://input'), true);
        Log::info('Update received: ' . json_encode($update));

        if (isset($update['message'])) {
            $message = $update['message']['text'];
            $chatId = $update['message']['chat']['id'];
            Log::info("Message: $message, Chat ID: $chatId");

            $telegram_user = telegram_user::where('telegram_chat_id','=',$chatId)->first();
            if($telegram_user == null){
                $this->sendMessageToChat($chatId, "Akun ini belum terdaftar pada database");
            }else{
                $this->sendMessageToChat($chatId, "Akun ini terdaftar di database");
            }
            // if ($message == '/1') {
            //     $this->sendMessageToChat($chatId, "You said 1: $message $chatId");
            // } else {
            //     // session()->put('status_menu' . $chatId, $status);

            //     $this->sendMessageToChat($chatId, "You said: $message");
            // }
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
