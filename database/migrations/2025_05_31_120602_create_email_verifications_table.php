<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('email_verifications', function (Blueprint $table) {
        $table->id();
        $table->string('email')->unique(); // نخزن الإيميل
        $table->string('code'); // نخزن الكود المرسل
        $table->timestamp('expires_at'); // وقت انتهاء الكود
        $table->timestamps(); // وقت الإنشاء والتحديث
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_verifications');
    }
};
