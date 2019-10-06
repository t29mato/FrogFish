<?php
return [
    'IWA' => [
        'ID' => 1,
        'URL' => 'http://iwadive.divingnavi.info/',
        'NAME' => '岩',
        'PATTERNS' => [
            '@岩の今日の海況([\s\S]*?)</table>@',
            '@透視度([\s\S]*?)<br />@',
        ]
    ],
    'KAWANA' => [
        'ID' => 2,
        'URL' => 'http://www.izu-ito.jp/kawana/info.html',
        'NAME' => '川奈',
        'PATTERNS' => [
            '@ビーチ ： ([\s\S]*?)br@',
        ]
    ]
];
