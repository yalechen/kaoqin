<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Attn;

class CreateAttnTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 考勤统计表
        Schema::create('attn', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 员工ID
            $table->unsignedInteger('user_id');

            // 月份
            $table->string('ymonth');

            // 哪天
            $table->tinyInteger('day');

            // 是否已上班签到:0-否；1-是
            $table->tinyInteger('is_sign_on');

            // 是否已下班签到:0-否；1-是
            $table->tinyInteger('is_sign_off');

            // 是否迟到:0-否；1-是
            $table->tinyInteger('is_late');

            // 是否休假:0-否；1-是
            $table->tinyInteger('is_vacation');

            // 是否加班:0-否；1-是
            $table->tinyInteger('is_overtime');

            // 总体状态，以上全部为1，则是OK
            $table->enum('status', [
                Attn::STATUS_OK,
                Attn::STATUS_NG
            ])->default(Attn::STATUS_NG);

            // 里程数
            $table->tinyInteger('mileage');

            // 巡店数
            $table->tinyInteger('visited_custs');

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
        Schema::drop('attn');
    }
}
