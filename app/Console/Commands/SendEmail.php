<?php

namespace App\Console\Commands;

use App\Mail\NotificationCronMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviando notificação via email para clientes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::select('SELECT * FROM users');
        for($i=0; $i <= count($users); $i++){
            Mail::to($users[$i]->email)->send(new NotificationCronMail());
        };

        Log::info("Executando job de envio de emails");
    }
}
