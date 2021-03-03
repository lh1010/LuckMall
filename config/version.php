<?php

/**
 * 版本管理
 */

return [
    
    // 后台
    'admin' => [
        'version' => '1.0.0',
        'security' => 'ZGIzN^WMzZG!VkY@2FkMDl@mNWIz!ZDR#lYTczOGFjZDQ5M2Q^',
    ],

    'pc' => [
        'version' => '1.0.0',
        'security' => 'ZGIzN^WMzZG!VkY@2FkMDl@mNWIz!ZDR#lYTczOGFjZDQ5M2Q^',
    ],

    // 微信小程序
    'wxapp' => [
        'version' => '1.1.0',
        'download_url' => '',
        'versions' => [
            '1.0.0' => [
                'security' => 'ZGIzN^WMzZG!VkY@2FkMDl@mNWIz!ZDR#lYTczOGFjZDQ5M2Q^',
                'recommend_update' => 'false',
                'force_update' => 'true',
                'update_message' => '有新版本，请重启更新小程序。'
            ],
            '1.0.1' => [
                'security' => 'ZGIzN^WMzZG!VkY@2FkMDl@mNWIz!ZDR#lYTczOGFjZDQ5M2Q^',
                'recommend_update' => 'true',
                'force_update' => 'false',
                'update_message' => '检测到最新版本，是否重启小程序？'
            ],
            '1.1.0' => [
                'security' => 'ZGIzN^WMzZG!VkY@2FkMDl@mNWIz!ZDR#lYTczOGFjZDQ5M2Q^',
                'recommend_update' => 'true',
                'force_update' => 'false',
                'update_message' => '检测到最新版本，是否重启小程序？'
            ],
        ]
    ]

];
