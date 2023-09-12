#Services

## Test db

For use in local development.

### Usage

If you have mysql or mariadb running, create a database named `dz_workspace_name` with the following settings:

- encoding: utf8mb4
- collation: utf8mb4_unicode_520_ci

Import the file `test-db/fixtures/dz_workspace_name.sql.gz`into this database.

### Usage with Docker

#### Create docker container

Copy `.env.example` to `.env` and update the environment variables as appropriate.

```bash
make start
```

This will create a docker container named `dz_workspace_name-db` running mariadb on port 20439.

### Stop docker container

```bash
make stop
```
