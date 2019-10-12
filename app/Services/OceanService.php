<?php

namespace App\Services;

use App\Services\OceanServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;

use Illuminate\Support\Facades\Cache;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use Kevinrob\GuzzleCache\Storage\LaravelCacheStorage;

use App\Ocean;
use App\OceanHistory;

/**
 * HTTP通信を利用して該当のウェブサイトから最新のHtmlを取得する
 * 透明度が更新されていれば、DBのデータを更新する。DBのデータがなければ新規にレコードを作成する
 */
class OceanService implements OceanServiceInterface
{
    private $httpClient;

    function __construct()
    {
        // https://github.com/guzzle/guzzle/issues/1905#issuecomment-325611336
        // 304エラーの時に処理をより早く終了するためにいれたが、実際に304が返ってきたのは確認できていない。(200しか返ってこない)
        $stack = HandlerStack::create();
        $stack->push(
            new CacheMiddleware(
                new PrivateCacheStrategy(
                    new LaravelCacheStorage(
                        Cache::store('redis')
                    )
                )
            ),
            'cache'
        );
        $this->httpClient = new Client(['handler' => $stack]);
    }

    public function execute(): void
    {
        foreach (Config('ocean') as $name => $oceanMaster) {
            try {
                $response = $this->httpClient->request('GET', $oceanMaster['URL'], [
                    'Content-type' => 'text/xml;charset="utf-8"',
                ]);

                $bodyStr = mb_convert_encoding($response->getBody()->getContents(), "utf-8", "sjis");

                if ($response->getStatusCode() === 304) {
                    \Log::info('[非更新] 304 ' . $name);
                    return;
                }

                $transparency = $this->matchPatterns($oceanMaster['PATTERNS'], $bodyStr);
                $previousOcean = Ocean::find($oceanMaster['ID']);

                if ($transparency === $previousOcean->transparency) {
                    \Log::info('[非更新] ' . $name);
                } else {
                    \Log::info('[更新] ' . $name);
                    $ocean = Ocean::updateOrCreate([
                        'name' => $name,
                        'transparency' => $transparency,
                        'url' => $oceanMaster['URL']
                    ]);
                    OceanHistory::updateOrCreate([
                        'ocean_id' => $oceanMaster['ID'],
                        'transparency' => $transparency,
                        'raw_html' => $bodyStr,
                    ]);
                }
            } catch (Exception $ex) {
                \Log::error($name . 'のデータ更新に失敗しました: ' . $ex);
            }
        }
    }

    private function matchPatterns(array $patterns, string $subject): string
    {
        $match = '';

        // 正規表現の数だけ文字を抽出する
        foreach ($patterns as $key => $pattern) {
            preg_match($pattern, $subject, $matches);
            if (isset($matches[0])) {
                $subject = $matches[0];
            }
            $match = $matches[1];
        }

        $match = $this->trimString($match);

        if (preg_match("/[0-9]/", $match)) {
            return $match;
        } else {
            return '-';
        }
    }


    private function trimString(string $string): string
    {
        // 全角半角スペースを削除
        $result = preg_replace("/( |　)/", "", $string);

        // 「全角」英数字を「半角」に変換
        $result = mb_convert_kana($result, 'rn');

        // 「?m〜?m」は「?〜?m」に変換
        $result = str_replace("m～", "～", $result);

        // m以降の文字は削除
        $result = preg_replace("/m.+/", "m", $result);

        return $result;
    }
}
