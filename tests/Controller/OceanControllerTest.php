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

    /**
     * @dataProvider additionProvider透明度レベル
     */
    public function test_透明度をCSS用に透明度レベルを出す($transparency, $expected)
    {
        // PrivateメソッドをテストするためにReflection導入
        // https://qiita.com/penton310/items/6b437061391016179631
        $reflection = new \ReflectionClass($this->oceanController);
        $calculateTransparencyLevelFunction = $reflection->getMethod('calculateTransparencyLevel');
        $calculateTransparencyLevelFunction->setAccessible(true);

        $actual = $calculateTransparencyLevelFunction->invoke($this->oceanController, $transparency);
        $this->assertEquals($expected, $actual);
    }

    public function additionProvider透明度レベル()
    {
        return [
            [-5, 0],
            [0, 0],
            [10, 0.5],
            [15, 0.75],
            [20, 1],
            [25, 1],
        ];
    }
}
