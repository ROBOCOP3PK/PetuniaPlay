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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id()->comment('ID único del post');
            $table->foreignId('blog_category_id')->constrained()->onDelete('cascade')->comment('Categoría del post');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Autor del post');
            $table->string('title')->comment('Título del post');
            $table->string('slug')->unique()->comment('URL amigable única');
            $table->text('excerpt')->nullable()->comment('Extracto/resumen del post');
            $table->longText('content')->comment('Contenido completo del post (HTML)');
            $table->string('featured_image')->nullable()->comment('Imagen destacada del post');
            $table->boolean('is_published')->default(false)->comment('Post publicado o borrador');
            $table->timestamp('published_at')->nullable()->comment('Fecha de publicación');
            $table->string('meta_title')->nullable()->comment('Título para SEO');
            $table->text('meta_description')->nullable()->comment('Descripción para SEO');
            $table->string('meta_keywords')->nullable()->comment('Palabras clave para SEO');
            $table->integer('views_count')->default(0)->comment('Contador de visitas al post');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
