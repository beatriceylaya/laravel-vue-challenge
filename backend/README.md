# Backend (Laravel API)

This folder contains the Laravel API for the Coffee Machine project.

For full project setup (backend + frontend), see the root README:
- `../README.md`

## Backend setup (quick)

From this `backend/` folder:

- **Environment**
  - Ensure `backend/.env` exists (copy from `backend/.env.example` if needed)

- **Start containers (Laravel Sail)**

```bash
./vendor/bin/sail up -d
```

- **First-time commands (inside Sail)**

```bash
./vendor/bin/sail composer install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

## API

- **Route prefix**: `/api/machine`
- **Endpoints**:
  - `GET /api/machine/status`
  - `POST /api/machine/fill-water`
  - `POST /api/machine/fill-coffee`
  - `POST /api/machine/brew`

## Postman collection

- **File**: `docs/postman/coffee-machine-api.postman_collection`
- **Base URL variable**: set `{{url}}` to your backend base URL (typically `APP_URL`, e.g. `http://localhost:8000`)
