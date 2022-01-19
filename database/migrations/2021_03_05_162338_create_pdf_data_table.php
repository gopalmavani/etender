<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdfDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdf_data', function (Blueprint $table) {
            $table->id();
            $table->string('tender_no',50)->nullable();
            $table->text('tender_title')->nullable();
            $table->string('publishing_time',50)->nullable();
            $table->string('closing_time',50)->nullable();
            $table->string('bid_end_time',255)->nullable();
            $table->string('bid_opening_time',255)->nullable();
            $table->string('ministry_state_name',255)->nullable();
            $table->string('organization_name',255)->nullable();
            $table->string('estimated_bid_value',255)->nullable();
            $table->string('pdftype',50)->nullable();
            $table->string('filename',255)->nullable();
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
        Schema::dropIfExists('pdf_data');
    }
}
