<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->enum('inform', [
                'The product is fake or not original',
                'Offensive or inappropriate content',
                'Unfair pricing',
                'Invalid or broken links',
                'The product does not match the description',
                'Copyright infringement (e.g., intellectual property rights)',
                'Issues with the quality of the image or book',
                'Duplication or copying from another product',
                'Technical issues with downloading the product',
                'The product contains prohibited content'
            ])->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            //
        });
    }
};
