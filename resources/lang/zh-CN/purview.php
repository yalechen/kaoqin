<?php
return [
    'type' => [
        \App\Models\Purview::TYPE_MENU => '菜单Menu',
        \App\Models\Purview::TYPE_ACTION => '功能Action'
    ],
    'status' => [
        \App\Models\Purview::STATUS_ON => '开启',
        \App\Models\Purview::STATUS_OFF => '关闭'
    ],
    'useon' => [
        \App\Models\Purview::USEON_PC => 'PC',
        \App\Models\Purview::USEON_APP => 'APP'
    ]
];
