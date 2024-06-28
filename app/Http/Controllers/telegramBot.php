<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;
use App\Models\telegram_user;
use App\Models\akses_database;
use App\Models\crud;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class telegramBot
{
    protected $telegram;

    public function __construct()
    {
        $this->telegram = new Api(config('services.telegram.bot_token'));
    }

    public function handleUpdate($update)
    {
        if (isset($update['message'])) {
            $message = $update['message']['text'];
            $chatId = $update['message']['chat']['id'];
            Log::info("Message: $message, Chat ID: $chatId");

            $telegram_user = telegram_user::where('telegram_chat_id', $chatId)->first();

            if ($telegram_user === null) {
                $this->sendMessageToChat($chatId, "Akun ini belum terdaftar pada database");
            } else {
                $responseText = $this->processMessage($telegram_user, $message);
                $this->sendMessageToChat($chatId, $responseText);
            }
        }
    }

    private function processMessage($telegram_user, $message)
    {
        switch ($message) {
            case '/0':
                return $this->resetUserState($telegram_user);
            case '/back':
                return $this->goBack($telegram_user);
            default:
                return $this->handleMenu($telegram_user, $message);
        }
    }

    private function resetUserState($telegram_user)
    {
        $telegram_user->menu_id = 1;
        $telegram_user->akses_database_id = 1;
        $telegram_user->crud_id = 1;
        $telegram_user->save();

        $text_send = "Hai, ini dari Program Laravel Haris \n";
        $text_send .= "Klik menu dibawah untuk melanjutkan ke sistem : \n";
        $text_send .= "/1 Akses Tabel Data Ke database";
        return $text_send;
    }

    private function goBack($telegram_user)
    {
        if ($telegram_user->crud_id > 1) {
            $telegram_user->crud_id = 1;
            $telegram_user->status_tele_id = 1;
            $telegram_user->save();
            return $this->handleMenu($telegram_user, '');
        }
        if ($telegram_user->akses_database_id > 1) {
            $telegram_user->akses_database_id = 1;
            $telegram_user->save();
            return $this->handleMenu($telegram_user, '');
        }
        if ($telegram_user->menu_id > 1) {
            $telegram_user->menu_id -= 1;
            $telegram_user->save();
            return $this->handleMenu($telegram_user, '');
        }
        return $this->handleMenu($telegram_user, '');
    }

    private function handleMenu($telegram_user, $message)
    {
        switch ($telegram_user->menu_id) {
            case 1:
                return $this->handleMainMenu($telegram_user, $message);
            case 2:
                return $this->handleDatabaseMenu($telegram_user, $message);
            default:
                return "Pilihan Menu Yang Anda Masukkan Tidak Tersedia Di Database";
        }
    }

    private function handleMainMenu($telegram_user, $message)
    {
        if ($message == '/1') {
            $telegram_user->menu_id = 2;
            $telegram_user->save();
            return $this->handleMenu($telegram_user, '');
        }

        $text_send = "Anda memasuki menu utama \n";
        $text_send .= "Klik menu dibawah untuk melanjutkan ke sistem : \n";
        $text_send .= "/1 Akses Tabel Data Ke database";
        return $text_send;
    }

    private function handleDatabaseMenu($telegram_user, $message)
    {
        if ($telegram_user->akses_database_id == 1) {
            return $this->selectDatabase($telegram_user, $message);
        } else {
            return $this->selectCrudOperation($telegram_user, $message);
        }
    }

    private function selectDatabase($telegram_user, $message)
    {
        if (substr($message, 0, 1) === '/' && is_numeric(substr($message, 1))) {
            $telegram_user->akses_database_id = intval(substr($message, 1));
            $telegram_user->save();
            return $this->handleMenu($telegram_user, '');
        }

        $akses_databases = akses_database::all();
        $text_send = "Saat ini anda berada di menu akses tabel data ke database \n";
        $text_send .= "Klik tabel berikut yang anda ingin akses : \n";
        foreach ($akses_databases as $akses_database) {
            if ($akses_database->id != 1) {
                $text_send .= "/$akses_database->id akses ke tabel $akses_database->nama_database \n";
            }
        }
        return $text_send;
    }

    private function selectCrudOperation($telegram_user, $message)
    {
        if ($telegram_user->crud_id == 1) {
            return $this->displayCrudOptions($telegram_user, $message);
        }

        $crud = crud::find($telegram_user->crud_id);
        $nama_tabel = akses_database::find($telegram_user->akses_database_id);
        $text_send = "Saat ini anda memasuki fitur $crud->tipe_crud untuk tabel $nama_tabel->nama_database \n";

        if ($telegram_user->crud_id == 2) {
            $text_send .= $this->showTable($nama_tabel->nama_database);
            return $text_send;
        }

        if ($telegram_user->crud_id == 3) {
            return $this->handleInsert($telegram_user, $message, $nama_tabel);
        }

        return $text_send;
    }

    private function displayCrudOptions($telegram_user, $message)
    {
        if (substr($message, 0, 1) === '/' && is_numeric(substr($message, 1))) {
            $telegram_user->crud_id = intval(substr($message, 1));
            $telegram_user->save();
            return $this->handleMenu($telegram_user, '');
        }

        $akses_database = akses_database::find($telegram_user->akses_database_id);
        $crud = crud::all();
        $text_send = "Saat ini anda berada di menu akses tabel $akses_database->nama_database \n";
        $text_send .= "Klik menu berikut yang anda ingin akses : \n";
        foreach ($crud as $loop_crud) {
            if ($loop_crud->id != 1) {
                $text_send .= "/$loop_crud->id $loop_crud->tipe_crud data $akses_database->nama_database \n";
            }
        }
        return $text_send;
    }

    private function handleInsert($telegram_user, $message, $nama_tabel)
    {
        if ($telegram_user->status_tele_id == 1) {
            $columns = Schema::getColumnListing($nama_tabel->nama_database);
            $filteredColumns = array_filter($columns, function ($column) {
                return $column !== 'id';
            });

            $text_send = "Untuk masukkan data ke tabel, ketik format seperti berikut : \n \n";
            $text_send .= implode(", ", $filteredColumns);
            $text_send .= "\n";

            $telegram_user->status_tele_id = 2;
            $telegram_user->save();

            return $text_send;
        }

        if ($telegram_user->status_tele_id == 2) {
            $columns = Schema::getColumnListing($nama_tabel->nama_database);
            $filteredColumns = array_filter($columns, function ($column) {
                return $column !== 'id';
            });

            $inputValues = explode(',', $message);

            if (count($inputValues) !== count($filteredColumns)) {
                return "Data yang anda masukkan tidak sesuai, silahkan isi kembali dengan format yang diberikan";
            }

            $data_kirim = array_combine($filteredColumns, $inputValues);

            $model = 'App\\Models\\' . strtolower(Str::studly(Str::singular($nama_tabel->nama_database)));
            if (class_exists($model)) {
                $model::create($data_kirim);
                return "Data berhasil dimasukkan ke tabel\n";
            } else {
                return "Ada kesalahan sistem, dimana model tidak ditemukan $model";
            }

            $telegram_user->status_tele_id = 1;
            $telegram_user->save();
        }

        return "Pilihan Menu Yang Anda Masukkan Tidak Tersedia Di Database";
    }

    private function showTable($table)
    {
        if (!Schema::hasTable($table)) {
            return "Table not found";
        }

        $rows = DB::table($table)->get();
        $rowsArray = $rows->toArray();
        $tableString = '';

        if (count($rowsArray) > 0) {
            $columns = array_keys((array) $rowsArray[0]);
            $tableString .= implode("\t", $columns) . "\n";
        }

        foreach ($rowsArray as $row) {
            $rowArray = (array) $row;
            $tableString .= implode("\t", $rowArray) . "\n";
        }

        return $tableString;
    }

    private function sendMessageToChat($chatId, $text)
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}

