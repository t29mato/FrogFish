<?php
return [
    'Iwa' => [
        'ID' => 1,
        'URL' => 'https://www.iwadive.com/',
        'name' => '岩',
        'patterns' => [
            '!透視度([\s\S]*?)水温!',
        ],
        'characterCode' => 'UTF-8',
        'CSS' => [
            'top' => '18%',
            'left' => '60%',
        ]
    ],
    'Futo' => [
        'ID' => 3,
        'URL' => 'http://www.izu-ito.jp/futo/info.html',
        'name' => '富戸',
        'patterns' => [
            '!<td nowrap>ビーチ</td>([\s\S]*?)</td></tr>!',
            '!<td>：</td>([\s\S]*?)</td></tr>!',
            '!<td nowrap>([\s\S]*?)</td></tr>!',
        ],
        'characterCode' => 'sjis',
        'CSS' => [
            'top' => '53%',
            'left' => '55%',
        ]
    ],
    // 'IOP' => [
    //     'ID' => 4,
    //     'URL' => 'https://iop-dc.com',
    //     'name' => '伊豆海洋公園',
    //     'nickname' => 'IOP',
    //     'patterns' => [
    //         '!<dt><h4>透視度</h4></dt><dd>([\s\S]*?)</dd>!',
    //         '!　([\s\S]*?)</dd>!',
    //     ],
    //     'CSS' => [
    //         'top' => '59%',
    //         'left' => '54%',
    //     ]
    // ],
    // 'IzuOshima' => [
    //     'ID' => 5,
    //     'URL' => 'https://izuohshima-diving.com/divelog/',
    //     'name' => '伊豆大島',
    //     'nickname' => '大島',
    //     'patterns' => [
    //         '!<div class="contenttext">([\s\S]*?)ｍ!s',
    //         '!透明度([\s\S]*?)$!s',
    //     ],
    //     'CSS' => [
    //         'top' => '71%',
    //         'left' => '74%',
    //     ]
    // ],
    // 'Osezaki' => [
    //     'ID' => 6,
    //     'URL' => 'http://www.cocomo-ds.net/p4-1.html',
    //     'name' => '大瀬崎',
    //     'patterns' => [
    //         '!<b>透視度</b>([\s\S]*?)<td><b>気温</b></td>!s',
    //         '!<td>([\s\S]*?)</td>!',
    //     ],
    //     'CSS' => [
    //         'top' => '35%',
    //         'left' => '10%',
    //     ]
    // ],
    // 'Kumomi' => [
    //     'ID' => 7,
    //     'URL' => 'http://kumomi-hamayu.com/sealogs/',
    //     'name' => '雲見',
    //     'patterns' => [
    //         '!<th class="rightTh">透視度:</th>([\s\S]*?)</td>!s',
    //         '!<td>([\s\S]*?)</td>!',
    //     ],
    //     'CSS' => [
    //         'top' => '72%',
    //         'left' => '1%',
    //     ]
    // ],
    // 'Mikomoto' => [
    //     'ID' => 8,
    //     'URL' => 'http://www.mikomoto.com/logs/',
    //     'name' => '神子元島',
    //     'patterns' => [
    //         '!<th>透視度<br>([\s\S]*?)</tr>!s',
    //         '!<td>([\s\S]*?)</td>!',
    //     ],
    //     'CSS' => [
    //         'top' => '93%',
    //         'left' => '33%',
    //     ]
    // ]
];
