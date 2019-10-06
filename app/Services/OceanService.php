<?php

namespace app\Services;

use app\Services\OceanServiceInterface;
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

                $wasRecentlyCreated = Ocean::where('id', $oceanMaster['ID'])
                    ->updateOrCreate([
                        'id' => $oceanMaster['ID'],
                        'name' => $name,
                        'transparency' => $transparency,
                        'url' => $oceanMaster['URL']
                    ])->wasRecentlyCreated;

                if ($wasRecentlyCreated) {
                    OceanHistory::create([
                        'ocean_id' => $oceanMaster['ID'],
                        'transparency' => $transparency,
                        'raw_html' => $bodyStr,
                    ]);
                    \Log::info('[更新] ' . $name);
                }
                \Log::info('[非更新] ' . $name);
            } catch (Exception $ex) {
                \Log::error($name . 'のデータ更新に失敗しました: ' . $ex);
            }
        }


        // Html上の日付に変更がない場合は、データの更新がないと判断して処理を終了する
        // $latestData = DB::('最新の日付を取得する');
        // if ($response->body->date === $latestDate) {
        //     return;
        // }

        // データの更新を行う
        // 終了
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
        return $match;
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
