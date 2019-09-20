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

    /**
     * @dataProvider additionProvider岩
     */
    public function test_岩($htmlPath, $patterns, $expected)
    {
        $html = file_get_contents(__DIR__  . $htmlPath);
        $actual = $this->oceanService->matchPatterns(Config($patterns), $html);
        $this->assertEquals($expected, $actual);
    }

    public function additionProvider岩()
    {
        return [
            ['/SampleHtml/Iwa/2019-09-13.html', 'ocean.IWA.PATTERNS', '5m'],
            ['/SampleHtml/Iwa/2019-09-20.html', 'ocean.IWA.PATTERNS', '8～10m'],
            ['/SampleHtml/Kawana/2019-09-20.html', 'ocean.KAWANA.PATTERNS', '8～10m']
        ];
    }
}
