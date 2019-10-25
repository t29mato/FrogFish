<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>伊豆半島周辺の透明度マップ</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
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
  <script>
    $(function() {
      $('[data-toggle="tooltip"]').tooltip()
    })
  </script>

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
          <span class="background d-inline-block" style="top: {{ $ocean['css_top'] }}; left: {{ $ocean['css_left'] }}" tabindex="0" data-toggle="tooltip" title="{{ $ocean['updated_at'] }}">
            <button class="text" style="pointer-events: none; background: rgba(2, 160, 233, {{ $ocean['transparencyLevel'] }})" type="button" disabled>{{ $ocean['name'] }} {{ $ocean['transparency'] }}</button>
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
        <p style="text-align: center;">更新のタイミング：15分おき（ポイントをタップで最終更新日時を確認できます）</p>
        <p style="text-align: center;">参照しているホームページ：
          @foreach ($oceanFormated as $ocean)
          <a href="{{ $ocean['url'] }}" target="_blank">{{ $ocean['name'] }}</a>　
          @endforeach
        </p>
        <p></p>
        <p style="text-align: center;">お問い合わせ先：<a href="https://twitter.com/umineco2019" target="_blank" rel="noopener">@umineco2019</a></p>
      </div>
    </div>
  </footer>
</body>
</html>
