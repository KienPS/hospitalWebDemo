# Hospital Web Demo

Dự án cntt - giữa kỳ

### Requirements:
- PHP 8.3
- Symfony: https://symfony.com/download

### Run

- Cài đặt MySQL DATABASE URL trong .env
- Chạy migration với ```php bin/console make:migration``` => ``` php bin/console doctrine:migrations:migrate ```
- Chạy ```symfony server:start``` và vào ```localhost:8000```
