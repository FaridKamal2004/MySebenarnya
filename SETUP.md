# MySebenarnya System Setup Instructions

Follow these steps to set up and run the MySebenarnya System:

## Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL database
- XAMPP (or similar local development environment)

## Setup Steps

1. **Clone the repository**

   The repository should be in your XAMPP htdocs folder at `c:/xampp/htdocs/MySebenarnya`.

2. **Install dependencies**

   ```
   cd c:/xampp/htdocs/MySebenarnya
   composer install
   ```

3. **Create and configure the environment file**

   ```
   cp .env.example .env
   ```

   Then edit the `.env` file to set your database connection details:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mysebenarnya
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate application key**

   ```
   php artisan key:generate
   ```

5. **Create the database**

   Create a new MySQL database named `mysebenarnya`.

6. **Run migrations and seeders**

   ```
   php artisan migrate
   php artisan db:seed
   ```

7. **Create storage link**

   ```
   php artisan storage:link
   ```

8. **Start the development server**

   ```
   php artisan serve
   ```

   Or access through XAMPP at `http://localhost/MySebenarnya/public`

## Default Login Credentials

### MCMC Admin
- Email: admin@mcmc.gov.my
- Password: password

## System Structure

The MySebenarnya System has three user roles:

1. **Public User** - Can submit inquiries and view their status
2. **Agency User** - Can respond to inquiries assigned to them
3. **MCMC User** - Can manage users, assign inquiries to agencies, and generate reports

## Troubleshooting

If you encounter the error "Table 'mysebenarnya.sessions' doesn't exist", run the migrations again:

```
php artisan migrate
```

If you have any other issues, please check the Laravel documentation or contact the development team.