# One Home Database

Database: `one_home` (utf8mb4, InnoDB).

## Fresh installation
Import `one_home.sql` in phpMyAdmin. It creates the tables and sample data. **It drops existing project tables first**, so export a backup before importing.

## Existing installation
Back up the database, then run `upgrade_existing_database.sql` once. It adds query indexes, review foreign keys and constraints, and five additional employee demo accounts without dropping existing user or booking records. Do not run the migration after importing the new full `one_home.sql`; the indexes/constraints will already exist.

## Tables
| Table | Purpose |
|---|---|
| `users` | Admin, employee and customer accounts |
| `services` | Service catalogue and starting prices |
| `employee_services` | Many-to-many employee/service mapping |
| `bookings` | Customer service requests and job status |
| `payments` | One payment record per booking |
| `reviews` | One customer review per completed booking |

## Relationships and protections
- `employee_services.employee_id` -> `users.id`
- `employee_services.service_id` -> `services.id`
- `bookings.customer_id` and `bookings.employee_id` -> `users.id`
- `bookings.service_id` -> `services.id`
- `payments.booking_id` -> `bookings.id` (unique; cascade delete)
- `reviews.booking_id` -> `bookings.id` (unique; cascade delete)
- `reviews.customer_id` and `reviews.employee_id` -> `users.id`
- Email is unique; payment amount and service price cannot be negative; review rating must be 1 to 5.
- Indexes support role/status lookups, booking lists, employee availability, payments, and reviews.

## Demo password
All sample accounts use `password`. These are for local testing only; change the passwords before any public deployment.
