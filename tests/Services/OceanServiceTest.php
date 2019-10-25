<?php

namespace Tests\Services;

use Tests\TestCase;
use App\Services\OceanService;
use GuzzleHttp\Client;

/**
 * 1. HTTP通信を利用して該当のウェブサイトから最新のHtmlを取得する
 * → テスト上、Html通信はせず、実際のレスポンスを上書きする
 * 2. HTTPのリクエストヘッダIf-Modified-Sinceが更新されている、かつHtml中の日付情報が更新されている場合は、データを更新する
 * → ステータスコードが304の場合、400の場合、200の場合をテストする
 * 3. データの更新はDBに行う
 * → 実際にインメモリのDBを利用して、DBが更新されていることを確認する
 * 4. 取得するデータは、Modified-At、日付、Html全文、透明度とする
 * → 3. と同様の趣旨で行う
 * 5. 日付と透明度が正規表現を用いて正しく取得できていることを確認する
 */
class OceanServiceTest extends TestCase
{
    /**
     * @var OceanService
     * @return void
     */

    private $oceanService;
    private $httpClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->oceanService = new OceanService();
        $this->httpClient = new Client();
    }

    /**
     * @dataProvider additionProviderポイント_IWA
     */
    public function test_matchPatterns_IWA($htmlPath, $patterns, $expected)
    {
        // PrivateメソッドをテストするためにReflection導入
        // https://qiita.com/penton310/items/6b437061391016179631
        $reflection = new \ReflectionClass($this->oceanService);
        $matchPatternsFunction = $reflection->getMethod('matchPatterns');
        $matchPatternsFunction->setAccessible(true);

        $html = file_get_contents(__DIR__  . $htmlPath);
        $actual = $matchPatternsFunction->invoke($this->oceanService, Config($patterns), $html);
        $this->assertEquals($expected, $actual);
    }

    public function additionProviderポイント_IWA()
    {
        return [
            ['/SampleHtml/Iwa/2019-09-13.html', 'ocean.Iwa.patterns', '5m'],
            ['/SampleHtml/Iwa/2019-09-20.html', 'ocean.Iwa.patterns', '8～10m'],
            ['/SampleHtml/Iwa/2019-10-06.html', 'ocean.Iwa.patterns', '3～5m'],
            ['/SampleHtml/Iwa/2019-10-07.html', 'ocean.Iwa.patterns', '3～5m'],
            ['/SampleHtml/Iwa/2019-10-10.html', 'ocean.Iwa.patterns', '3m'],
        ];
    }

    /**
     * @dataProvider additionProviderポイント_KAWANA
     */
    public function test_matchPatterns_KAWANA($htmlPath, $patterns, $expected)
    {
        // PrivateメソッドをテストするためにReflection導入
        // https://qiita.com/penton310/items/6b437061391016179631
        $reflection = new \ReflectionClass($this->oceanService);
        $matchPatternsFunction = $reflection->getMethod('matchPatterns');
        $matchPatternsFunction->setAccessible(true);

        $html = file_get_contents(__DIR__  . $htmlPath);
        $actual = $matchPatternsFunction->invoke($this->oceanService, Config($patterns), $html);
        $this->assertEquals($expected, $actual);
    }

    public function additionProviderポイント_KAWANA()
    {
        return [
            ['/SampleHtml/Kawana/2019-09-20.html', 'ocean.Kawana.patterns', '8～10m'],
            ['/SampleHtml/Kawana/2019-09-21.html', 'ocean.Kawana.patterns', '8～10m'],
            ['/SampleHtml/Kawana/2019-10-06.html', 'ocean.Kawana.patterns', '5～8m'],
            ['/SampleHtml/Kawana/2019-10-07.html', 'ocean.Kawana.patterns', '5～8m'],
            ['/SampleHtml/Kawana/2019-10-11.html', 'ocean.Kawana.patterns', '-'],
        ];
    }

    /**
     * @dataProvider additionProviderポイント_FUTO
     */
    public function test_matchPatterns_FUTO($htmlPath, $patterns, $expected)
    {
        // PrivateメソッドをテストするためにReflection導入
        // https://qiita.com/penton310/items/6b437061391016179631
        $reflection = new \ReflectionClass($this->oceanService);
        $matchPatternsFunction = $reflection->getMethod('matchPatterns');
        $matchPatternsFunction->setAccessible(true);

        $html = file_get_contents(__DIR__  . $htmlPath);
        $actual = $matchPatternsFunction->invoke($this->oceanService, Config($patterns), $html);
        $this->assertEquals($expected, $actual);
    }

    public function additionProviderポイント_FUTO()
    {
        return [
            ['/SampleHtml/Futo/2019-10-13.html', 'ocean.Futo.patterns', '-'],
            ['/SampleHtml/Futo/2019-10-19.html', 'ocean.Futo.patterns', '5〜8m'],
        ];
    }

    /**
     * @dataProvider additionProviderポイント_IOP
     */
    public function test_matchPatterns_IOP($htmlPath, $patterns, $expected)
    {
        // PrivateメソッドをテストするためにReflection導入
        // https://qiita.com/penton310/items/6b437061391016179631
        $reflection = new \ReflectionClass($this->oceanService);
        $matchPatternsFunction = $reflection->getMethod('matchPatterns');
        $matchPatternsFunction->setAccessible(true);

        $html = file_get_contents(__DIR__  . $htmlPath);
        $actual = $matchPatternsFunction->invoke($this->oceanService, Config($patterns), $html);
        $this->assertEquals($expected, $actual);
    }

    public function additionProviderポイント_IOP()
    {
        return [
            ['/SampleHtml/IOP/2019-10-01.html', 'ocean.IOP.patterns', '10～15m'],
            ['/SampleHtml/IOP/2019-10-13.html', 'ocean.IOP.patterns', '-'],
            ['/SampleHtml/IOP/2019-10-20.html', 'ocean.IOP.patterns', '10m'],
        ];
    }

    /**
     * @dataProvider additionProviderポイント_IZUOSHIMA
     */
    public function test_matchPatterns_IZUOSHIMA($htmlPath, $patterns, $expected)
    {
        // PrivateメソッドをテストするためにReflection導入
        // https://qiita.com/penton310/items/6b437061391016179631
        $reflection = new \ReflectionClass($this->oceanService);
        $matchPatternsFunction = $reflection->getMethod('matchPatterns');
        $matchPatternsFunction->setAccessible(true);

        $html = file_get_contents(__DIR__  . $htmlPath);
        $actual = $matchPatternsFunction->invoke($this->oceanService, Config($patterns), $html);
        $this->assertEquals($expected, $actual);
    }

    public function additionProviderポイント_IZUOSHIMA()
    {
        return [
            ['/SampleHtml/IzuOshima/2019-10-09.html', 'ocean.IzuOshima.patterns', '12～15m'],
            ['/SampleHtml/IzuOshima/2019-10-13.html', 'ocean.IzuOshima.patterns', '15m'],
        ];
    }

    /**
     * @dataProvider additionProviderポイント_OSEZAKI
     */
    public function test_matchPatterns_OSEZAKI($htmlPath, $patterns, $expected)
    {
        // PrivateメソッドをテストするためにReflection導入
        // https://qiita.com/penton310/items/6b437061391016179631
        $reflection = new \ReflectionClass($this->oceanService);
        $matchPatternsFunction = $reflection->getMethod('matchPatterns');
        $matchPatternsFunction->setAccessible(true);

        $html = file_get_contents(__DIR__  . $htmlPath);
        $actual = $matchPatternsFunction->invoke($this->oceanService, Config($patterns), $html);
        $this->assertEquals($expected, $actual);
    }

    public function additionProviderポイント_OSEZAKI()
    {
        return [
            ['/SampleHtml/Osezaki/2019-10-23.html', 'ocean.Osezaki.patterns', '3～8m'],
        ];
    }

    /**
     * @dataProvider additionProviderポイント_KUMOMI
     */
    public function test_matchPatterns_KUMOMI($htmlPath, $patterns, $expected)
    {
        // PrivateメソッドをテストするためにReflection導入
        // https://qiita.com/penton310/items/6b437061391016179631
        $reflection = new \ReflectionClass($this->oceanService);
        $matchPatternsFunction = $reflection->getMethod('matchPatterns');
        $matchPatternsFunction->setAccessible(true);

        $html = file_get_contents(__DIR__  . $htmlPath);
        $actual = $matchPatternsFunction->invoke($this->oceanService, Config($patterns), $html);
        $this->assertEquals($expected, $actual);
    }

    public function additionProviderポイント_KUMOMI()
    {
        return [
            ['/SampleHtml/Kumomi/2019-10-25.html', 'ocean.Kumomi.patterns', '12～15m'],
        ];
    }
}
