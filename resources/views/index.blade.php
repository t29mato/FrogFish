<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-120931690-4"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-120931690-4');
  </script>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta property="og:url" content="{{ config('app.url') }}" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="伊豆半島周辺の透明度マップ" />
  <meta property="og:description" content="伊豆半島周辺の海況を毎日チェックしてるダイバーさん向けの透明度マップです。" />
  <meta property="og:site_name" content="伊豆半島周辺の透明度マップ" />
  <meta property="og:image" content="{{ asset('images/ogp-min.png') }}" />

  <title>伊豆半島周辺の透明度マップ</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ config('app.url') }}" rel="canonical" />
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <style>
    .photo {
      width: 100%;
      margin: 0;
      padding: 0;
      position: relative;
      margin-bottom: 10px;
    }

    .photo img {
      width: 100%;
    }

    .background {
      font-size: 15px;
      text-align: center;
      padding: 0;
      color: #fff;
      background: rgba(255, 255, 255, 1.0);
      /* 帯の透明度 */
      position: absolute;
      /* 絶対位置指定 */
    }

    .iwa {
      top: 18%;
      left: 60%;
    }

    .text {
      color: black;
      padding: 1px 3px;
      border: none;
    }
  </style>

</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-6 offset-lg-3">
        <h2>伊豆半島周辺の透明度マップ
          @if ($environment === 'local')
          （ローカル環境）
          @elseif ($environment === 'develop')
          （開発環境）
          @else
          {{-- Production --}}
          @endif
        </h2>
        <div class="photo">
          <img class="img" style="border: 5px solid black" src="{{ asset('images/izu-hanto-tiny.png') }}" />
          @foreach ($oceanFormated as $ocean)
          <span class="background d-inline-block" style="top: {{ $ocean['css_top'] }}; left: {{ $ocean['css_left'] }}" tabindex="0">
            <a href="{{ '/oceans/' . $ocean['id'] }}">
              <button class="text" style="pointer-events: none; background: rgba(2, 160, 233, {{ $ocean['transparencyLevel'] }})" type="button" disabled>{{ $ocean['name'] }} {{ $ocean['transparency'] }}</button>
            </a>
          </span>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <footer class="bd-footer text-muted">
    <div class="row">
      <div class="col-lg-6 offset-lg-3">
        <p style="text-align: center;">
          <span style="background: linear-gradient(to right, white, rgba(2, 160, 233, 1)); padding: 5px; color: black; border: 1px solid rgba(2, 160, 233, 1);">0m　5m　10m　15m　20m　25m</span>
        </p>
        <div class="alert alert-primary" role="alert">
          [NEWS]<br>
          2020年8月22日 ポイント名クリックで過去の透明度を履歴できるように変更。<br>
          2021年1月11日 参照先のHPの構造が変更されていたもののクローリングし続けるのが困難になったため開発者の気になるポイントの岩と富戸だけ取得・表示するように変更。
        </div>
        <p style="text-align: center;">参照しているホームページ<br>
          @foreach ($oceanFormated as $ocean)
          <a href="{{ $ocean['url'] }}" target="_blank">{{ $ocean['name'] . ' (' . $ocean['updated_at'] . ')' }}</a><br>
          @endforeach
        </p>
        <p></p>
        <p style="text-align: center;">お問い合わせ先：<a href="https://twitter.com/umineco2019" target="_blank" rel="noopener">@umineco2019</a></p>
      </div>
    </div>
  </footer>
</body>
</html>
