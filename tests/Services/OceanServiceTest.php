<?php

namespace Tests\Services;

use Tests\TestCase;
use app\Services\OceanService;

class 透明度を取得する extends TestCase
{
    /**
     *
     * @var OceanService
     * @return void
     */

    private $oceanService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->oceanService = new OceanService();
    }

    public function test_2019年9月13日の岩の透明度は5m()
    {
        $src = '/SampleHtml/Iwa/2019-09-13.html';
        $htmlIwa = file_get_contents(__DIR__  . $src);
        $expected = '5m';
        $actual = $this->oceanService->matchPatterns(Config('ocean.IWA.PATTERNS'), $htmlIwa);
        $this->assertEquals($expected, $actual);
    }
}
