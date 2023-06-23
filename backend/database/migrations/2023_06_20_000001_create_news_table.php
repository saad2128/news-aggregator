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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->integer('source_id');
            $table->tinyText('raw_author')->nullable();
            $table->text('title')->fulltext();
            $table->text('slug');
            $table->text('url');
            $table->text('url_to_image')->nullable();
            $table->text('description')->nullable()->fulltext();
            $table->text('content')->nullable()->fulltext();
            $table->string('apiSource')->nullable();
            $table->timestamp('published_at');
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
        Schema::dropIfExists('news');
    }
};
