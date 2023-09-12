# SiamCarDeal Services API

This SiamCarDeal Services API is for use in local development.

The application is based on `Laravel Framework 9.7.0`, which requires a minimum `PHP version of 8.0`.

## Installation

### Go to API application's directory.

```bash
cd apps/api
```

Note: Ignore this step if you are already in `apps/api` directory.

### Setup environment variable

```bash
cp .env.example .env
```

### Composer install

```bash
make docker.composer
```

### Start container

```bash
make docker.start
```

The API is available at this url: `http://localhost:20198`

### Stop container

```bash
make docker.stop
```
