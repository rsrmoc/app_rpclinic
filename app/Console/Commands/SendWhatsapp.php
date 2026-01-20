<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendWhatsapp extends Command {
    protected $signature = 'send-whatsapp {popo?}';

    protected $description = 'Envio de mensagens para o whatsapp';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        echo "Teste".PHP_EOL;

        var_dump($this->argument('popo'));
    }


    
}