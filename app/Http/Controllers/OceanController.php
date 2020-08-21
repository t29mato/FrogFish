<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ocean;
use App\OceanHistory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class OceanController extends Controller
{
    public function index()
    {
        $oceans = Ocean::all();
        $oceanFormated = [];
        $dtNow = new Carbon();
        foreach ($oceans as $index => $ocean) {
            array_push($oceanFormated, [
                'id' => $ocean->id,
                'name' => $ocean->nickname ? $ocean->nickname : $ocean->name,
                'transparency' => $ocean->transparency,
                'transparencyLevel' => $this->calculateTransparencyLevel(
                    $this->calculateTransparencyInt($ocean->transparency)
                ),
                'url' => $ocean->url,
                'updated_at' => $this->diffUpdateAt($ocean->updated_at, $dtNow),
                'css_top' => Config('ocean')[$ocean->key]['CSS']['top'],
                'css_left' => Config('ocean')[$ocean->key]['CSS']['left'],
            ]);
        }

        return view('index', [
            'oceanFormated' => $oceanFormated,
            'environment' => App::environment()
        ]);
    }

    public function ocean($ocean_id)
    {
        $now = new Carbon();
        $oceans = OceanHistory::where('ocean_histories.ocean_id', '=', $ocean_id)
            ->where('ocean_histories.created_at', '>', $now->subYear()->format('Y-m-d'))
            ->join('oceans', 'ocean_id', '=', 'oceans.id')
            ->select('oceans.name', 'ocean_histories.transparency', 'oceans.id', 'ocean_histories.created_at')
            ->orderBy('ocean_histories.created_at', 'desc')
            ->get();
        foreach ($oceans as $index => $ocean) {
            // var_dump($ocean->name, $ocean->transparency, $ocean->created_at->format('Y-m-d'));
            // var_dump($ocean->created_at->format('Y-m-d') . ': ' . $ocean->transparency . '<br>');
        }
        return view('ocean', [
            'oceans' => $oceans
        ]);
    }

    private function calculateTransparencyInt(string $transparency): int
    {
        $result = 0;
        if ($transparency === '-') {
            $result = 0;
        } else if (strpos($transparency, 'ã€œ') !== false) {
            preg_match('!([0-9]+)ã€œ([0-9]+)m!', $transparency, $matches);
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

    private function diffUpdateAt(Carbon $dtUpdatedAt, Carbon $dtNow): string
    {
        $diffInMinutes = $dtNow->diffInMinutes($dtUpdatedAt);
        $diffInHours = $dtNow->diffInHours($dtUpdatedAt);
        $diffInDays = $dtNow->diffInDays($dtUpdatedAt);

        $suffix = 'に更新';

        if ($diffInMinutes <= 60) {
            return $diffInMinutes . '分前' . $suffix;
        } else if ($diffInHours <= 24) {
            return $diffInHours . '時間前' . $suffix;
        } else {
            return $diffInDays . '日前' . $suffix;
        }
    }
}
