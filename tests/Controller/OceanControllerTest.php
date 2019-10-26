<?php

namespace Tests\Services;

use Tests\TestCase;
use App\Http\Controllers\OceanController;
use Illuminate\Support\Carbon;

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
            [10, 0.4],
            [15, 0.6],
            [20, 0.8],
            [25, 1],
        ];
    }

    /**
     * @dataProvider additionProvider最終更新日時
     *
     * 60分以内なら分で表示
     * 24時間以内なら時間で表示
     * それ以外は日数で表示
     */
    public function test_最終更新日を時間に合わせて調整($transparency, $expected)
    {
        $reflection = new \ReflectionClass($this->oceanController);
        $trimUpdateAtFunction = $reflection->getMethod('trimUpdateAt');
        $trimUpdateAtFunction->setAccessible(true);

        $dtNow = new Carbon('2019-10-26 12:00:00.000000');
        var_dump($dtNow);

        $actual = $trimUpdateAtFunction->invoke($this->oceanController, $transparency);
        $this->assertEquals($expected, $actual);
    }

    public function additionProvider最終更新日時()
    {
        return [
            ['2019-10-26 11:50:00.000000', '10分前'],
        ];
    }
}
