<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuickBooksTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quickbooks_tokens', function (Blueprint $table) {
            $table->string('id');
            $table->integer('tenant_id');
            $table->unsignedBigInteger('realm_id')
                  ->nullable();
            $table->longtext('auth_code')
                  ->nullable();
            $table->longtext('access_token')
                  ->nullable();
            $table->datetime('access_token_expires_at')
                  ->nullable();
            $table->string('refresh_token')
                  ->nullable();
            $table->datetime('refresh_token_expires_at')
                  ->nullable();
            $table->string('qbo_company_id')
                  ->nullable();
            $table->string('qbo_company_name')
                  ->nullable();
            $table->string('qbo_company_address')
                  ->nullable();
            $table->string('qbo_company_email')
                  ->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('quickbooks_tokens');
    }
}
