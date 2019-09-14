<?php

namespace app\Services;

use app\Services\OceanServiceInterface;

class OceanService implements OceanServiceInterface
{
    public function matchPatterns(array $patterns, string $subject): string
    {
        $match = '';
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

        return $result;
    }
}
