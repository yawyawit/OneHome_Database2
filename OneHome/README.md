# One Home

A home-service booking system for the school project. It has three separate workspaces: **Administrator**, **Employee**, and **Customer**.

## Technology
- PHP 7.4+ / PHP 8.x
- MySQL or MariaDB
- Bootstrap 5 and Bootstrap Icons
- Procedural PHP with MySQLi prepared statements

## Install with XAMPP
1. Back up your existing `one_home` database if it contains data you need.
2. Copy the `OneHome` folder into `C:\xampp\htdocs\`.
3. Start Apache and MySQL.
4. Open phpMyAdmin and import `database/one_home.sql` for a fresh installation.
5. Open `http://localhost/OneHome/`.

The SQL file creates a fresh database and demo records. **Importing it again drops and recreates the project tables.** For an existing database, back it up and use `database/upgrade_existing_database.sql` one time instead. That migration adds indexes, review foreign keys, and extra employee demo accounts without dropping existing records.

## Demo accounts
All demo accounts use the password: `password` (demo accounts only).

| Role | Email |
|---|---|
| Administrator | admin@onehome.test |
| Customer | liza@onehome.test |
| Customer | mark@onehome.test |
| Employee | juan@onehome.test |
| Employee | maria@onehome.test |
| Employee | pedro@onehome.test |
| Employee | ana@onehome.test |
| Employee | rafael@onehome.test |
| Employee | grace@onehome.test |
| Employee | miguel@onehome.test |
| Employee | bea@onehome.test |
| Employee | daniel@onehome.test |
| Pending employee | carlo@onehome.test |

New accounts must use a password with at least 10 characters. The seeded demo password is shorter only so the sample accounts are easy to use during local testing. Change it before any real deployment.

## Role workspaces
- **Administrator:** forest-green control area for users, bookings, services, payments, reviews, and reports.
- **Employee:** warm brown workspace focused on new requests, jobs, schedule, availability, and earnings.
- **Customer:** clean white-and-green service browsing and booking experience.

The navigation and dashboard are intentionally shorter so each role sees the pages and tasks relevant to them.

## More service providers
The sample database now includes multiple active employees for each service. The employee selection page shows active, available employees who are linked to the chosen service. It also filters by work day and existing confirmed/ongoing jobs when a date and time are supplied.

## Security improvements
- Prepared statements for database values.
- Password hashing with `password_hash()` and verification with `password_verify()`.
- CSRF tokens on POST forms and POST-only actions.
- Session cookies use HttpOnly and SameSite settings; session IDs are regenerated and sessions expire after 30 minutes of inactivity.
- Role checks remain on protected pages.
- Uploads accept only JPEG, PNG, or GIF images up to 2 MB; server-generated filenames are used.
- Apache rules disable directory listings, block direct access to database/include folders, and prevent PHP execution in upload folders.
- Security response headers and no-cache headers for signed-in pages.
- Database errors are logged on the server instead of displaying SQL details to visitors.
- Database indexes, foreign keys, and CHECK constraints protect relationships and important values.

## Database credentials
For local XAMPP, `includes/dbconn.php` defaults to:
- Host: `localhost`
- User: `root`
- Password: blank
- Database: `one_home`
- Port: `3307`

Change the port if your MySQL uses `3306`. The connection can also read `ONEHOME_DB_HOST`, `ONEHOME_DB_USER`, `ONEHOME_DB_PASS`, `ONEHOME_DB_NAME`, and `ONEHOME_DB_PORT` environment variables.

For deployment, do not use the MySQL `root` account. Create a dedicated database user with only the permissions the application needs, set a strong password, and configure the environment variables. Use HTTPS and ensure the web server honors the `.htaccess` protections (Apache only).

Example database account setup (replace the password with a strong, unique one):
```sql
CREATE USER 'onehome_app'@'localhost' IDENTIFIED BY 'REPLACE_WITH_A_STRONG_PASSWORD';
GRANT SELECT, INSERT, UPDATE, DELETE ON one_home.* TO 'onehome_app'@'localhost';
FLUSH PRIVILEGES;
```

## Important deployment note
This is a polished school-project build, not a fully audited commercial platform. Before public production use, configure HTTPS, a dedicated DB user, backups, email verification, persistent login throttling, monitoring, and test the full booking/payment workflow on the actual hosting environment. The card payment is simulated only; no real transaction is processed.
