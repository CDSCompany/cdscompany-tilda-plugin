<?php namespace Cds\Tilda\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTildaProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('cds_tilda_tilda_projects', function (Blueprint $table) {
	        Schema::dropIfExists('cds_tilda_tilda_projects');
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->integer('tilda_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cds_tilda_tilda_projects');
    }
}
