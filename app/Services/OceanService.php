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
        foreach (Config('ocean') as $key => $oceanMaster) {
            try {
                $response = $this->httpClient->request('GET', $oceanMaster['URL'], [
                    'Content-Type' => 'text/html; charset=UTF-8',
                ]);

                $bodyStr = $response->getBody()->getContents();
                if (isset($oceanMaster['characterCode'])) {
                    $bodyStr = mb_convert_encoding($bodyStr, "UTF-8", $oceanMaster['characterCode']);
                }

                if ($response->getStatusCode() === 304) {
                    \Log::info('[非更新] 304 ' . $key);
                    return;
                }

                $transparency = $this->matchPatterns($oceanMaster['patterns'], $bodyStr);
                $previousOcean = Ocean::find($oceanMaster['ID']);
                if ($previousOcean && $transparency === $previousOcean->transparency) {
                    \Log::info('[非更新] ' . $key);
                } else {
                    \Log::info('[更新] ' . $key);
                    $ocean = Ocean::updateOrCreate([
                        'id' => $oceanMaster['ID']
                    ], [
                        'key' => $key,
                        'name' => $oceanMaster['name'],
                        'nickname' => array_key_exists('nickname', $oceanMaster) ? $oceanMaster['nickname'] : null,
                        'transparency' => $transparency,
                        'url' => $oceanMaster['URL']
                    ]);
                    $ocean->transparency = $transparency;
                    $ocean->save();

                    OceanHistory::create([
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
            var_dump($matches);
            if (isset($matches[0])) {
                $subject = $matches[0];
            }
            if (isset($matches[1])) {
                $match = $matches[1];
            } else {
                // HACK: まとめてリファクタするときに修正するので一旦忘れる
                $match = '';
            }
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

        // 「：」削除
        $result = str_replace("：", "", $result);

        return $result;
    }
}
