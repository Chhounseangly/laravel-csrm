# Usage

## Initial Setup

```bash
php artisan csrm:install
```

This command generates the base CSRM classes only:
- `BaseController`
- `BaseService`
- `BaseRepository`
- `BaseRepositoryInterface`

## Generate Module

```bash
php artisan csrm:make Product
```

## Controller Mode

- Default: web controller
- Use `--api` for API controller
- By default, controller methods are empty and do not include repository/service scaffolding

```bash
php artisan csrm:make Product --only=controller
php artisan csrm:make Product --only=controller --api
```

## Selective Generation

```bash
php artisan csrm:make Product --only=model,migration,repository
```

Available options:

- `model`
- `migration`
- `repository`
- `service`
- `controller`
