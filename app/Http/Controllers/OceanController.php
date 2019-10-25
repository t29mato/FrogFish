<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ocean;
use Illuminate\Support\Facades\App;

class OceanController extends Controller
{
    public function index()
    {
        $oceans = Ocean::all();
        $oceanFormated = [];
        foreach ($oceans as $index => $ocean) {
            array_push($oceanFormated, [
                'name' => $ocean->nickname ? $ocean->nickname : $ocean->name,
                'transparency' => $ocean->transparency,
                'transparencyLevel' => $this->calculateTransparencyLevel(
                    $this->calculateTransparencyInt($ocean->transparency)
                ),
                'url' => $ocean->url,
                'updated_at' => $ocean->updated_at->format('m/d H:i'),
                'css_top' => Config('ocean')[$ocean->key]['CSS']['top'],
                'css_left' => Config('ocean')[$ocean->key]['CSS']['left'],
            ]);
        }

        return view('index', [
            'oceanFormated' => $oceanFormated,
            'environment' => App::environment()
        ]);
    }

    private function calculateTransparencyInt(string $transparency): int
    {
        $result = 0;
        if ($transparency === '-') {
            $result = 0;
        } else if (strpos($transparency, '〜') !== false) {
            preg_match('!([0-9]+)〜([0-9]+)m!', $transparency, $matches);
            $result = round(
                ((intval($matches[1]) + intval($matches[2])) / 2),
                0,
                PHP_ROUND_HALF_UP
            );
        } else {
            preg_match('!([0-9]+)m!', $transparency, $matches);
            $result = intval($matches[1]);
        }
        return $result;
    }

    private function calculateTransparencyLevel(int $transparency): float
    {
        $transparencyLevelMax = Config('const')['TransparencyLevelMax'];
        if ($transparency >= $transparencyLevelMax) {
            return 1;
        } else if ($transparency <= 0) {
            return 0;
        } else {
            return $transparency / $transparencyLevelMax;
        }
    }
}
