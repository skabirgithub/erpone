<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartOfAccountSubTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'chart_of_account_sub_types', function (Blueprint $table){
            $table->id();
            $table->string('name')->default();
            $table->integer('type')->default(0);
            $table->string('status')->default('active');
            $table->string('visibility')->default('public');
            $table->integer('created_by')->default(2);
            $table->timestamps();
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chart_of_account_sub_types');
    }
}
