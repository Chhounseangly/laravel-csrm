# Installation

## Packagist

```bash
composer require seangly/laravel-csrm
```

## Local Path Repository

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../laravel-csrm",
      "options": {
        "symlink": true
      }
    }
  ],
  "require": {
    "seangly/laravel-csrm": "@dev"
  }
}
```

Then run:

```bash
composer update seangly/laravel-csrm -W
```
