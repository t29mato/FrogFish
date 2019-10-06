<?php

namespace App\Services;

/**
 * Fetchを用いて指定されたURLのHTMLから正規表現置換と文字列置換を用いて透明度を抽出する
 * 最終更新日を保存しておき更新がなければ置換なしで終了
 */
interface OceanServiceInterface
{
    public function execute(): void;
    // public function matchPatterns(array $patterns, string $subject): string;
}
