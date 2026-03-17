# Coffee Machine API

A small Laravel API that simulates a coffee machine with water/coffee containers and a few drink types.

## Prerequisites

Make sure you have the following installed:
- Docker Desktop (required for Laravel Sail)
- Git

## Setup

### Clone the Repository

```bash
git clone https://github.com/beatriceylaya/laravel-vue-challenge.git
cd laravel-vue-challenge
```

### Setup environment

Copy the environment file.
```bash
cp .env.example .env
```
### Setup (Laravel Sail)

If Sail is not installed in the project yet:

```bash
composer require laravel/sail --dev
php artisan sail:install
```

Then start the containers and run setup steps inside Sail:

```bash
./vendor/bin/sail up -d
./vendor/bin/sail composer install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

## Access the App
Visit:

```bash
http://localhost
```

## API Endpoints

All endpoints are prefixed with `/api/machine`.

- **GET** `/api/machine/status`: current water/coffee levels + which drinks can be made
- **POST** `/api/machine/fill-water`: add water (ml)
- **POST** `/api/machine/fill-coffee`: add coffee (g)
- **POST** `/api/machine/brew`: brew a drink

## Using the Postman collection

A Postman collection is included at:

- `docs/postman/coffee-machine-api.postman_collection`

### Import

In Postman:

- **Import** → **File** → select `docs/postman/coffee-machine-api.postman_collection`

### Configure the base URL (`{{url}}`)

The requests use a `{{url}}` variable.

Pick one of these options:

- **Option A (recommended)**: create/use a Postman **Environment** (e.g. “Local”) and set:
  - `url` = the value of `APP_URL` from your `.env` (currently `http://localhost:8000`)
- **Option B**: set the **Collection variable** `url` to the same value

## Assumptions Made
1. **Units**: All water quantities are measured in **ml** throughout. The interface uses `float` for precision.
2. **Initial State**: Both containers start full when machine is first used.
3. **Ristretto**: The spec defined a ristretto recipe -- but only espresso, double espresso, and americano are stated in the API endpoint allowed functions. Thus, it is not included in the allowed types but ready for future use.
4. **Redis - state**: For simplicity, Redis is chosen.
