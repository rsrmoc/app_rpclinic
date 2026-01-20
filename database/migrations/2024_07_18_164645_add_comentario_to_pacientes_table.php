<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddComentarioToPacientesTable extends Migration
{
    public function up()
    {
        Schema::table('paciente', function (Blueprint $table) {
            $table->longText('comentario')->nullable()->after('cpf_responsavel'); // Substitua 'ultimo_campo_existente' pelo nome do último campo na tabela
        });
    }

    public function down()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn('comentario');
        });
    }
}

