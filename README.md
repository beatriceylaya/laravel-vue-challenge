## Coffee Machine (Laravel + Vue)

- **Backend**: Laravel (Dockerized via Laravel Sail)
- **Frontend**: Vue 3 + Vite

## Prerequisites
- **Docker Desktop** (Linux containers)
- **Node.js**: `>= 20.19` (or `>= 22.12`)
- **Yarn** (classic) for the frontend (`yarn --version`)

## First-time setup

### 1) Clone and install backend dependencies
From the repo root:

- **Backend env**
  - Ensure `backend/.env` exists (copy from `backend/.env.example` if needed).

- **Install backend deps**
  - Run composer install (either locally if you have PHP/composer, or inside the container after bringing it up).

### 2) Start backend services (Sail)
Run this from the `backend/` folder.

```bash
sail up -d
```

Then run the usual Laravel first-time steps (inside the Sail container):
- Generate app key
```bash
sail artisan key:generate
```
- Run migrations
```bash
sail artisan migrate
```

If you don’t have a `sail` alias set up, use:

```bash
./vendor/bin/sail up -d
```

### 3) Configure and run frontend
- **Frontend env**
  - Create `frontend/.env` from `frontend/.env.example`
  - Set `VITE_API_URL` to your backend base URL (for example: `http://localhost/api`).

Install and start the dev server from `frontend/`:

```bash
yarn
yarn dev
```

## Usage (run separately)

### Backend
From `backend/`:

```bash
sail up -d
```

### Frontend
From `frontend/`:

```bash
yarn
yarn dev
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
4. **Redis**: Redis stores the current machine state (water/coffee levels).