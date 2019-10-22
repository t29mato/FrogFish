# 開発環境のバージョン情報
- PHP 7.3.8
- Composer 1.8.6

# 環境構築
```
$ docker-compose up --build
$ docker-compose exec app composer install
$ docker-compose exec app php artisan migrate
$ touch database/database.sqlite
```
# 環境構築 (トラブルシュート)
## storage配下がPermission Deniedになる時
```
$ chmod -R 777 logs
$ chmod -R 777 storage/logs
$ chmod -R 777 storage/framework
```

# テスト
```
$ phpunit
```
詳細は、`tests/Services/OceanServiceTest.php`

# コマンド
## 全ポイントの情報取得
```
$ php artisan oceanService:execute
```

# 開発環境
http://3.114.115.73
