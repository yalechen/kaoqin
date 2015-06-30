<?php
return [
    'status' => [
        \App\Models\Overtime::STATUS_PASS => '审核通过',
        \App\Models\Overtime::STATUS_REJECT => '审核驳回',
        \App\Models\Overtime::STATUS_WAIT => '待审核中'
    ]
];
