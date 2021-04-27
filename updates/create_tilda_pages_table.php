<?php namespace Cds\Tilda\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTildaPagesTable extends Migration
{
    public function up()
    {
        Schema::create('cds_tilda_tilda_pages', function (Blueprint $table) {
	        Schema::dropIfExists('cds_tilda_tilda_pages');
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->text('js');
            $table->text('css');
            $table->text('images');
            $table->text('html');
            $table->string('filename');
            $table->timestamp('published_tilda_at');
            $table->integer('project_id');
            $table->integer('tilda_id');
            $table->boolean('is_active');
            $table->integer('type');
            $table->string('slug');
            $table->string('seo_title');
            $table->string('seo_description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cds_tilda_tilda_pages');
    }
}
