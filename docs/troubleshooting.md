# Troubleshooting

## Command Not Found

```bash
composer dump-autoload
php artisan optimize:clear
```

## Local Package Not Updating

```bash
composer update seangly/laravel-csrm -W
```

## Wrong Controller Type

- Default mode is web controller.
- Use `--api` if you want `App\Http\Controllers\Api\...`.

## Existing Files Not Overwritten

Use `--force`:

```bash
php artisan csrm:make Product --force
```
