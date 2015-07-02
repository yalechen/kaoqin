<?php
use Illuminate\Database\Seeder;
use App\models\User;

class UserTableSeeder extends Seeder
{

    public function run()
    {
        // 清空表数据
        User::truncate();

        // 填充总管理员用户
        $user = new User();
        $user->name = 'root';
        $user->number = '00000000';
        $user->email = 'root@admin.com';
        $user->mobile = '13799475820';
        $user->password = '123456';
        $user->avatar_path = '/upload_files/init/fa24a612110135f42e8a3b1ec5db9239.png';
        $user->realname = '超级管理员';
        $user->status = User::STATUS_ON;
        $user->save();
        // 添加管理员角色
        $user->roles()->sync([
            1
        ]);

        // 填充总管理员用户
        $user = new User();
        $user->name = 'admin';
        $user->number = '00000001';
        $user->email = 'admin@admin.com';
        $user->mobile = '13799475821';
        $user->password = '123456';
        $user->avatar_path = '/upload_files/init/fa24a612110135f42e8a3b1ec5db9239.png';
        $user->realname = '公司管理员';
        $user->status = User::STATUS_ON;
        $user->parent_user_id = 1;
        $user->save();
        // 添加管理员角色
        $user->roles()->sync([
            2
        ]);

        // 创建20个外勤业务员用户
        $realnames = [
            '郑砚平',
            '符强',
            '颉国起',
            '曹燕',
            '高小玲',
            '王欣',
            '高虹',
            '申刚',
            '张义生',
            '萧洪志',
            '叶标',
            '王照听',
            '侯新宇',
            '陈昆鹏',
            '陆爱君',
            '刘广义',
            '李亚新',
            '段卫军',
            '吴强',
            '茹少玉'
        ];
        $mobiles = [
            '13738786456',
            '13985862539',
            '18685175201',
            '13764912768',
            '15685086688',
            '18985239220',
            '13984720367',
            '13520907278',
            '13518089580',
            '13876548880',
            '13876401112',
            '13976616315',
            '13389899891',
            '13518075379',
            '13518898951',
            '13907593664',
            '13733274134',
            '15128107621',
            '13582238962',
            '13503328394'
        ];
        for ($i = 0; $i < 20; $i ++) {
            $j = $i + 2;
            $user = new User();
            $user->name = "user{$i}";
            $user->number = "0000000{$j}";
            $user->email = "{$mobiles[$i]}@user.com";
            $user->mobile = $mobiles[$i];
            $user->password = '123456';
            $user->avatar_path = '/upload_files/init/fa24a612110135f42e8a3b1ec5db9239.png';
            $user->realname = $realnames[$i];
            $user->parent_user_id = 2;
            $user->status = User::STATUS_ON;
            $user->save();
            // 添加业务员角色
            $user->roles()->sync([
                3
            ]);
        }
    }
}