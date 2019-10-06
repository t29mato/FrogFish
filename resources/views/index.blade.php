<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <title>関東透明度マップ</title>

</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ポイント名</th>
              <th scope="col">透明度</th>
              <th scope="col">最終更新日時</th>
              <th scope="col">ホームページ</th>
            </tr>
          </thead>
          <tbody>
            <h1 class="bd-title">関東透明度マップ</h1>
            <p class="bd-lead">掲載ポイント数：{{ count($oceans) }}</p>
            @foreach ($oceans as $ocean)
            <tr>
              <td>{{ $ocean->name }}</td>
              <td>{{ $ocean->transparency }}</td>
              <td>{{ $ocean->updated_at }}</td>
              <td><a href="{{ $ocean->url }}">{{ $ocean->url }}</a></td>
            </tr>
            @endforeach

          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>

</html>