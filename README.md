# BlastEmail

BlastEmail is a robust email marketing application built with Laravel. It provides a comprehensive suite of tools to manage your email marketing campaigns, from list management to detailed performance tracking.

## Features

-   **Campaign Management**: Create, configure, and schedule email campaigns with a multi-step wizard.
-   **Email Lists & Subscribers**: Organize your audience into segmented lists and manage subscriber details.
-   **Email Templates**: Create and save reusable email templates for consistent branding.
-   **Performance Tracking**: Track email openings and link clicks in real-time.
-   **Dashboard Analytics**: Visualize campaign performance with statistics on open rates, click rates, and total engagement.
-   **Queue-based Sending**: efficient email dispatching using Laravel Queues.

## Requirements

-   PHP ^8.2
-   Composer
-   Node.js & NPM
-   Database (MySQL, SQLite, etc.)

## Installation

1.  **Clone the repository:**

    ```bash
    git clone <repository_url>
    cd blastemail
    ```

2.  **Install PHP dependencies:**

    ```bash
    composer install
    ```

3.  **Environment Setup:**
    Copy the example environment file and configure your database settings:

    ```bash
    cp .env.example .env
    ```

    Update the `.env` file with your database credentials and mail server configuration.

4.  **Generate Application Key:**

    ```bash
    php artisan key:generate
    ```

5.  **Run Migrations:**
    Set up the database schema:

    ```bash
    php artisan migrate
    ```

6.  **Install Frontend Dependencies:**
    ```bash
    npm install
    npm run build
    ```

## Usage

### Development Server

Start the local development server:

```bash
php artisan serve
```

### Queue Worker

To process queued emails (sending campaigns), you need to run the queue listener:

```bash
php artisan queue:listen
```

### Mailpit (Local Email Testing)

To test email sending locally, you can use [Mailpit](https://github.com/axllent/mailpit). Run it using Docker:

```bash
docker run -d \
--name=mailpit \
--restart=always \
-p 8025:8025 \
-p 1025:1025 \
axllent/mailpit
```

Configure your `.env` file to use Mailpit:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

You can access the Mailpit web interface at `http://localhost:8025`.

Access the application at `http://localhost:8000`.

## Testing

The project includes a comprehensive test suite using Pest PHP. To run the tests:

```bash
php artisan test
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
