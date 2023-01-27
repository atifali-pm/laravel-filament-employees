<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId("country_id")->constrained()->cascadeOnDelete();
            $table->foreignId("city_id")->constrained()->cascadeOnDelete();
            $table->foreignId("state_id")->constrained()->cascadeOnDelete();
            $table->string('firstname');
            $table->string('lastname');
            $table->string("address");
            $table->string("zip_code");
            $table->date("birth_date");
            $table->date("date_hired");

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
        Schema::dropIfExists('employees');
    }
};
