<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('semester_number');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Seed standard semesters 1 to 8
        $now = now();
        $semesters = [];
        for ($i = 1; $i <= 8; $i++) {
            $isGanjil = $i % 2 !== 0;
            $typeStr = $isGanjil ? 'Ganjil' : 'Genap';
            $startYear = 2025 + intval(($i - 1) / 2);
            $endYear = $startYear + 1;
            $semesters[] = [
                'name' => "Semester {$i} ({$typeStr} {$startYear}/{$endYear})",
                'semester_number' => $i,
                'is_active' => $i === 1, // Semester 1 active by default
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        DB::table('semesters')->insert($semesters);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
