<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // table to manage likes in te system

        DB::statement('CREATE TABLE `likes` (
              `user_id` int(11) NOT NULL,
              `post_id` int(11) NOT NULL,
              `rating_action` varchar(30) NOT NULL,
               CONSTRAINT UC_likes UNIQUE (user_id, post_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
