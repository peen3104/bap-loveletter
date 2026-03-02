<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path'); // lokasi file upload
            $table->enum('status', [
                'menunggu_kasubag',
                'menunggu_kabag1',
                'disetujui',
                'ditolak'
            ])->default('menunggu_kasubag');
            $table->text('notes')->nullable(); // catatan dari kasubag/kabag
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('letters');
    }
};