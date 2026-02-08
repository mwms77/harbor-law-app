# Resend.com Email Setup

This app is configured to use [Resend](https://resend.com) for transactional email (notifications, confirmations).

## 1. Install the package

```bash
composer install
```

(The `resend/resend-laravel` package is already in `composer.json`.)

## 2. Get your API key

1. Sign up or log in at [resend.com](https://resend.com).
2. Go to **API Keys** and create a new key.
3. Copy the key (starts with `re_`).

## 3. Configure .env

Add or update these in your `.env`:

```env
# Resend
MAIL_MAILER=resend
RESEND_API_KEY=re_xxxxxxxxxxxxxxxxxxxxxxxx

# From address (must be a verified domain in Resend, or use onboarding@resend.dev for testing)
MAIL_FROM_ADDRESS=noreply@harbor.law
MAIL_FROM_NAME="Harbor Law"

# Admin (for notifications to you)
ADMIN_EMAIL=matt@harbor.law
```

## 4. Verify your domain (production)

1. In Resend: **Domains** → **Add Domain**.
2. Add your domain (e.g. `harbor.law`).
3. Add the DNS records Resend shows (SPF, DKIM, etc.) at your DNS provider.
4. Use a from address on that domain (e.g. `noreply@harbor.law`).

For local testing you can use Resend’s test address: `onboarding@resend.dev` as `MAIL_FROM_ADDRESS` (no domain verification needed).

## 5. Queue (required for notifications)

Notifications are queued. Use the database driver and run a worker:

```env
QUEUE_CONNECTION=database
```

```bash
php artisan queue:work
```

## 6. Clear config cache (production)

After changing `.env`:

```bash
php artisan config:clear
php artisan config:cache
```

## Switching back to SES or SMTP

Set `MAIL_MAILER` to your preferred driver and the matching env vars (e.g. `ses` + AWS_* or `smtp` + MAIL_HOST, etc.). No code changes needed.
