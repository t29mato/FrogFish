<?php

namespace app\Services;

use app\Services\OceanServiceInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use App\Ocean;
use App\OceanHistory;
use Illuminate\Support\Facades\DB;

/**
 * HTTP通信を利用して該当のウェブサイトから最新のHtmlを取得する
 * 透明度が更新されていれば、DBのデータを更新する。DBのデータがなければ新規にレコードを作成する
 */
class OceanService implements OceanServiceInterface
{
    private $httpClient;

    function __construct()
    {
        $this->httpClient = new Client();
    }

    public function execute(): void
    {
        foreach (Config('ocean') as $name => $ocean) {
            $response = $this->httpClient->request('GET', $ocean['URL'], [
                'Content-type' => 'text/xml;charset="utf-8"',
            ]);
            $bodyStr = mb_convert_encoding($response->getBody()->getContents(), "utf-8", "sjis");

            // TODO: 304の場合はデータの更新がないので処理を終了する
            // if ($response->getStatusCode() === 304) {
            //     \Log::info('ステータスコード304のため処理を終了');
            //     return;
            // }

            $transparency = $this->matchPatterns($ocean['PATTERNS'], $bodyStr);
            try {
                $wasRecentlyCreated = Ocean::where('id', $ocean['ID'])
                    ->updateOrCreate([
                        'id' => $ocean['ID'],
                        'name' => $name,
                        'transparency' => $transparency
                    ])->wasRecentlyCreated;

                if ($wasRecentlyCreated) {
                    OceanHistory::create([
                        'ocean_id' => $ocean['ID'],
                        'transparency' => $transparency,
                        'raw_html' => $bodyStr
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
