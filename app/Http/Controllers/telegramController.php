<?php

namespace App\Http\Controllers;

use App\Models\akses_database;
use App\Models\crud;
use App\Models\telegram_user;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\ElseIf_;
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

            $telegram_user = telegram_user::all()->where('telegram_chat_id', '=', $chatId)->first();
            $this->sendMessageToChat($chatId, "$chatId");

            if ($telegram_user === null) {
                $this->sendMessageToChat($chatId, "Akun ini belum terdaftar pada database");
            } else {
                // $this->sendMessageToChat($chatId, "Akun ini terdaftar di database");
                $this->sendMessageToChat($chatId, $this->response_telegram($telegram_user, $message));
            }
        }

        return response('OK', 200);
    }

    private function response_telegram($telegram_user, $message)
    {
        if ($message == '/0') {
            $telegram_user->menu_id = 1;
            $telegram_user->akses_database_id = 1;
            $telegram_user->crud_id = 1;
            $telegram_user->save();
            // menu_id 1 untuk start
            $text_send = "Hai, ini dari Program Laravel Haris \n";
            $text_send .= "Klik menu dibawah untuk melanjutkan ke sistem : \n";
            $text_send .= "/1 Akses Tabel Data Ke database";
            return $text_send;
        }
        if ($telegram_user->menu_id == 1) {
            if ($message == '/1') {
                $akses_databases = akses_database::all();
                // menu_id 1 untuk start
                $text_send = "Saat ini anda berada di menu akses tabel data ke database \n";
                $text_send .= "Klik tabel berikut yang anda ingin akses : \n";
                foreach ($akses_databases as $akses_database) {
                    if ($akses_database->id == 1) {
                        continue;
                    }
                    $text_send .= "/$akses_database->id akses ke tabel $akses_database->nama_database \n";
                }
                $text_send .= "/0 Kembali ke menu utama \n";
                $telegram_user->menu_id = 2;
                $telegram_user->save();
                return $text_send;
            } else {
                // menu_id 1 untuk start
                $text_send = "Hai, ini dari Program Laravel Haris \n";
                $text_send .= "Klik menu dibawah untuk melanjutkan ke sistem : \n";
                $text_send .= "/1 Akses Tabel Data Ke database";
                return $text_send;
            }
        }
        if ($telegram_user->menu_id == 2) {
            if ($telegram_user->akses_database_id == 1) {
                // akses database id 1 jika belum memilih apa apa di tabel
                if (substr($message, 0, 1) === '/') {
                    // Menghapus karakter "/" dan memeriksa apakah sisanya adalah angka
                    $numberString = substr($message, 1);
                    if (is_numeric($numberString)) {
                        // Mengonversi ke integer
                        $number = intval($numberString);
                        $akses_database = akses_database::find($number);
                        $crud = crud::all();
                        $text_send = "Saat ini anda berada di menu akses tabel $akses_database->nama_database \n";
                        $text_send .= "Klik menu berikut yang anda ingin akses : \n";
                        foreach ($crud as $loop_crud) {
                            if ($loop_crud->id == 1) {
                                continue;
                            }
                            $text_send .= "/$loop_crud->id $loop_crud->tipe_crud data $akses_database->nama_database \n";
                        }
                        $text_send .= "/0 Kembali ke menu utama \n";
                        $telegram_user->menu_id = 2;
                        $telegram_user->akses_database_id = $number;
                        $telegram_user->save();
                        return $text_send;
                    } else {
                        return $this->textWrongChat();
                    }
                } else {
                    return $this->textWrongChat();
                }
            }else{
                if($telegram_user->crud_id == 1){
                    // akses database id 1 jika belum memilih apa apa di tabel
                    if (substr($message, 0, 1) === '/') {
                        // Menghapus karakter "/" dan memeriksa apakah sisanya adalah angka
                        $numberString = substr($message, 1);
                        if (is_numeric($numberString)) {
                            // Mengonversi ke integer
                            $number = intval($numberString);
                            $crud = crud::find($number);
                            $akses_database = akses_database::find($telegram_user->akses_database_id);

                            $text_send = "Saat ini anda memasuki fitur $crud->tipe_crud untuk tabel $akses_database->nama_database \n";
                            
                            $telegram_user->crud_id = $number;
                            $telegram_user->save();
                            return $text_send;
                        } else {
                            return $this->textWrongChat();
                        }
                    } else {
                        return $this->textWrongChat();
                    }
                }
            }
        }
        return "Pilihan Menu Yang Anda Masukkan Tidak Tersedia Di Database \n /0 untuk kembali ke menu utama";
    }
    
    private function textWrongChat()
    {
        $text_send = "Input yang anda masukkan tidak tersedia di database \n";
        $text_send .= "Masukkan kembali input yang sesuai \n";
        $text_send = "/0 Kembali ke menu utama \n";
        return $text_send;
    }

    private function sendMessageToChat($chatId, $text)
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}
