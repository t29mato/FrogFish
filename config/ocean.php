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
    ],
    'FUTO' => [
        'ID' => 3,
        'URL' => 'http://www.izu-ito.jp/futo/info.html',
        'NAME' => '富戸',
        'PATTERNS' => [
            '@<td nowrap>ビーチ</td>([\s\S]*?)</tr>@s',
        ]
    ],
    'IOP' => [
        'ID' => 4,
        'URL' => 'https://iop-dc.com',
        'NAME' => '伊豆海洋公園',
        'PATTERNS' => [
            '@<dt><h4>透視度</h4></dt><dd>([\s\S]*?)</dd>@',
            '@昨日([\s\S]*?)$@',
        ]
    ]
];
