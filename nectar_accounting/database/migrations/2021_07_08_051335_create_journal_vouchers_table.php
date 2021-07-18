<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('journal_voucher_no')->nullable();
            $table->date('entry_date_english')->nullable();
            $table->date('entry_date_nepali')->nullable();
            $table->foreignId('fiscal_year_id')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->integer('payable_to')->nullable();
            $table->double('debitTotal')->nullable();
            $table->double('creditTotal')->nullable();
            $table->longText('narration');
            $table->boolean('status')->default('0');
            $table->boolean('is_cancelled');
            $table->integer('entry_by')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('cancelled_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('edited_by')->nullable();
            $table->integer('editcount')->default('0');
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
        Schema::dropIfExists('journal_vouchers');
    }
}
