# 開発環境のバージョン情報
- PHP 7.3.8
- Composer 1.8.6

# 環境構築
```
$ cd /project/root/path
$ composer install
$ php ./artisan key:generate
$ php ./artisan migrate
$ php ./artisan db:seed
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
