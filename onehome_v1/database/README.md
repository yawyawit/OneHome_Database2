# Database Notes

Database name: `one_home`. Import `one_home.sql` in phpMyAdmin. **Importing again resets all data** (it drops the tables first).
When a teammate changes the tables, export a new `one_home.sql` from phpMyAdmin (Export -> Quick -> SQL), replace this file, and commit it. The schema uses InnoDB foreign keys and expects a MySQL/MariaDB version that supports CHECK constraints.

## Tables
| Table | Purpose | Main columns |
|---|---|---|
| `users` | Admins, employees and customers | id, name, email (unique), password (hashed), phone, address, role (`admin/employee/customer`), status (`active/pending/inactive`), avatar, skills, available_days (e.g. `Mon,Tue`), is_available |
| `services` | Services offered | id, name, description, price, icon (Bootstrap icon class), is_active |
| `employee_services` | Which employee does which service (many-to-many) | employee_id, service_id |
| `bookings` | Customer requests | id, customer_id, employee_id, service_id, booking_date, booking_time, address, description, photo, price, status (`Pending/Confirmed/Ongoing/Completed/Cancelled/Declined`), decline_reason |
| `payments` | One payment record per booking | id, booking_id (unique), amount, method (`cash/card`), status (`Unpaid/Paid/Refunded`), card_last4, paid_at |
| `reviews` | One review per completed booking | id, booking_id (unique), customer_id, employee_id, rating (1-5), comment |

## Relationships
- `bookings.customer_id` and `bookings.employee_id` -> `users.id`; `bookings.service_id` -> `services.id`
- `payments.booking_id` and `reviews.booking_id` -> `bookings.id`
- `reviews.customer_id` and `reviews.employee_id` -> `users.id`

## Status flow
`Pending` -> (employee accepts) `Confirmed` -> (start) `Ongoing` -> (complete) `Completed` -> customer pays + reviews.
A pending request can also become `Declined` (employee) and a pending/confirmed one `Cancelled` (customer).

## Rules in the code
- Customer can review only their own `Completed` booking, once.
- An employee cannot have two Confirmed/Ongoing jobs within 2 hours of each other.
- Only employees who offer the service, work on the chosen weekday, and are free at that time are shown to the customer.
