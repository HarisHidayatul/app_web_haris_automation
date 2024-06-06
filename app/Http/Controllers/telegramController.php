<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;

class telegramController extends Controller
{
    //
    protected $telegram;
    public function __construct()
    {
        $this->telegram = new Api(config('services.telegram-bot-api.token'));
    }
    public function webhook(Request $request)
    {
        // $update = $this->telegram->getWebhookUpdates();
        // $message = $update->getMessage();
        // $chatId = $message->getChat()->getId();

        $update = Telegram::getWebhookUpdates();

        $message = $update->getMessage();
        $chatId = $message->getChat()->getId();
        $text = $message->getText();

        // Logika untuk menangani pesan
        $responseText = "You said: " . $text;
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $responseText
        ]);

        return response()->json(['status' => 'success']);
    }

    public function sendMessage(Request $request)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = $request->input('chat_id');
        $message = $request->input('message');

        $client = new Client();
        $response = $client->post("https://api.telegram.org/bot{$token}/sendMessage", [
            'json' => [
                'chat_id' => $chatId,
                'text' => $message,
            ],
        ]);

        return response()->json([
            'status' => 'success',
            'response' => json_decode($response->getBody()->getContents(), true),
        ]);
    }
}
