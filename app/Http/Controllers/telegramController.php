<?php

namespace App\Http\Controllers;

use App\Models\akses_database;
use App\Models\crud;
use App\Models\telegram_user;
use App\Services\telegramBot;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use PhpParser\Node\Stmt\ElseIf_;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Str;

class telegramController extends Controller
{
    protected $telegramBot;

    public function __construct(telegramBot $telegramBot)
    {
        $this->telegramBot = $telegramBot;
    }

    public function webhook(Request $request)
    {
        Log::info('Webhook called');
        $update = json_decode(file_get_contents('php://input'), true);
        Log::info('Update received: ' . json_encode($update));
        $this->telegramBot->handleUpdate($update);
        return response('OK', 200);
    }
}
