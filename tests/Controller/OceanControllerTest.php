<?php

namespace Tests\Services;

use Tests\TestCase;
use App\Http\Controllers\OceanController;

/**
 * 透明度をintegerにする
 */
class OceanControllerTest extends TestCase
{
    /**
     * @var OceanController
     * @return void
     */

    private $oceanController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->oceanController = new OceanController();
    }

    /**
     * @dataProvider additionProvider透明度
     */
    public function test_透明度をintegerにする($transparency, $expected)
    {
        // PrivateメソッドをテストするためにReflection導入
        // https://qiita.com/penton310/items/6b437061391016179631
        $reflection = new \ReflectionClass($this->oceanController);
        $calculateTransparencyIntFunction = $reflection->getMethod('calculateTransparencyInt');
        $calculateTransparencyIntFunction->setAccessible(true);

        $actual = $calculateTransparencyIntFunction->invoke($this->oceanController, $transparency);
        $this->assertEquals($expected, $actual);
    }

    public function additionProvider透明度()
    {
        return [
            ['-', 0],
            ['3〜8m', '6'],
            ['10〜12m', '11'],
            ['10m', '10'],
        ];
    }
}
