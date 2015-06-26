<?php
return [
    'gender' => [
        \App\Models\User::GENDER_MALE => '男',
        \App\Models\User::GENDER_FEMALE => '女'
    ],
    'status' => [
        \App\Models\User::STATUS_ON => '在职',
        \App\Models\User::STATUS_OFF => '离职'
    ]
];