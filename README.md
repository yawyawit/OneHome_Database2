# One Home

A simple home-services web app for Baguio City (school project).
Three kinds of users: **Customers**, **Employees (service providers)** and **Admins**.

**Tech:** HTML, CSS, Bootstrap 5, basic JavaScript, procedural PHP, MySQL/MariaDB (XAMPP).

## How to run (each member, locally)
1. Install **XAMPP** and start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Copy this folder (`one-home`) into `C:\xampp\htdocs\`.
3. Open **http://localhost/phpmyadmin** -> **Import** -> choose `database/one_home.sql` -> **Go**.
   (This creates the `one_home` database with demo data.)
4. Open **http://localhost/one-home/** in your browser.
5. If your MySQL has a password, edit `includes/dbconn.php`.

> Bootstrap and Bootstrap Icons load from a CDN, so you need internet when testing.

## Demo accounts (password for all: `password`)
| Role | Email |
|---|---|
| Admin | admin@onehome.test |
| Employee | juan@onehome.test (plumbing, painting), maria@onehome.test (electrical, appliances), pedro@onehome.test, ana@onehome.test |
| Customer | liza@onehome.test, mark@onehome.test |
| Pending employee (needs approval) | carlo@onehome.test |

## Try this demo flow
1. Login as **Liza** (customer) -> Services -> Plumbing -> Book Now -> pick a date/time -> choose **Ana** or **Juan** -> Confirm Booking.
2. Logout, login as that employee -> **Requests** -> Accept.
3. Login as Liza -> **My Bookings** shows *Confirmed*.
4. Login as the employee -> **My Jobs** -> Start Job -> Mark Completed.
5. Login as Liza -> **Pay Now** (simulated card, e.g. `4242 4242 4242 4242`, `12/28`, `123`) -> Rate & Review.
6. Login as **Admin** -> Bookings, Payments, Reviews, Reports.

## Access control (who can open what)
Every protected page starts with `require_role('admin' | 'employee' | 'customer')` (see `includes/auth.php`).
If a user opens a page of another role (even by typing the URL) they are sent back to their own dashboard.
Deactivated or not-yet-approved accounts cannot log in. Admin accounts cannot be created from the register page.

## Folder guide
- `customer/`, `employee/`, `admin/` - the pages of each role
- `actions/` - scripts that process forms (login, register, booking, payment, review, profile)
- `includes/` - shared code: `dbconn.php` (database), `auth.php` (login/roles), `functions.php` (helpers), header/footer/navbar
- `database/` - SQL export and schema notes
- `uploads/` - customer job photos; `images/profiles/` - employee pictures

## Payment (first version)
- **Pay after service:** employee presses *Cash Received* after the job -> status becomes Paid.
- **Simulated card:** the customer fills a demo card form. **Nothing is charged and the full card number is never saved** (only the last 4 digits).
- To change this later (e.g. a real gateway), edit `actions/process_payment.php` and `customer/payment.php` only.

## Ideas for later (not in version 1)
CSRF tokens on forms, email/SMS verification when changing email or phone, real payment gateway, employee payouts, notifications by email.

## Deployment
Upload the files to a PHP host (PHP 7.4+ / 8.x), import `database/one_home.sql`, then update the database login in `includes/dbconn.php`. Make sure `uploads/` and `images/profiles/` are writable.
