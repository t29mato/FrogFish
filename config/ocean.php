<?php
return [
    'IWA' => [
        'ID' => 1,
        'URL' => 'http://iwadive.divingnavi.info/',
        'NAME' => '岩',
        'PATTERNS' => [
            '@岩の今日の海況([\s\S]*?)</table>@',
            '@透視度([\s\S]*?)<br />@',
        ],
        'characterCode' => 'sjis',
        'CSS' => [
            'top' => '18%',
            'left' => '60%',
        ]
    ],
    'KAWANA' => [
        'ID' => 2,
        'URL' => 'http://www.izu-ito.jp/kawana/info.html',
        'NAME' => '川奈',
        'PATTERNS' => [
            '@ビーチ ： ([\s\S]*?)br@',
        ],
        'characterCode' => 'sjis',
        'CSS' => [
            'top' => '45%',
            'left' => '56%',
        ]
    ],
    'FUTO' => [
        'ID' => 3,
        'URL' => 'http://www.izu-ito.jp/futo/info.html',
        'NAME' => '富戸',
        'PATTERNS' => [
            '@<td nowrap>ビーチ</td>([\s\S]*?)</td></tr>@',
            '@<td nowrap>[0-9]([\s\S]*?)</td></tr>@',
            '@<td nowrap>([\s\S]*?)</td></tr>@',
        ],
        'characterCode' => 'sjis',
        'CSS' => [
            'top' => '53%',
            'left' => '55%',
        ]
    ],
    'IOP' => [
        'ID' => 4,
        'URL' => 'https://iop-dc.com',
        'NAME' => '伊豆海洋公園',
        'PATTERNS' => [
            '@<dt><h4>透視度</h4></dt><dd>([\s\S]*?)</dd>@',
            '@　([\s\S]*?)</dd>@',
        ],
        'CSS' => [
            'top' => '59%',
            'left' => '54%',
        ]
    ],
    'IZUOSHIMA' => [
        'ID' => 5,
        'URL' => 'https://izuohshima-diving.com/divelog/',
        'NAME' => '伊豆大島',
        'PATTERNS' => [
            '@<div class="contenttext">([\s\S]*?)ｍ@s',
            '@透明度([\s\S]*?)$@s',
        ],
        'CSS' => [
            'top' => '71%',
            'left' => '81%',
        ]
    ],
    'OSEZAKI' => [
        'ID' => 6,
        'URL' => 'http://www.cocomo-ds.net/p4-1.html',
        'NAME' => '大瀬崎',
        'PATTERNS' => [
            '@<b>透視度</b>([\s\S]*?)<td><b>気温</b></td>@s',
            '@<td>([\s\S]*?)</td>@',
        ],
        'CSS' => [
            'top' => '71%',
            'left' => '81%',
        ]
    ]
];
