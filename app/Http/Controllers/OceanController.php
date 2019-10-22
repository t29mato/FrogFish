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
                'name' => $ocean->name,
                'transparency' => $ocean->transparency,
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
}
