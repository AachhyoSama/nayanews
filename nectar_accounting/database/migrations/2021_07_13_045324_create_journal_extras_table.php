<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_voucher_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->integer('child_account_id')->nullable();
            $table->string('code')->nullable();
            $table->string('remarks')->nullable();
            $table->string('debitAmount')->nullable();
            $table->string('creditAmount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journal_extras');
    }
}
