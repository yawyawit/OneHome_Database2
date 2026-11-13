# One Home – Page Access Protection

## What this update protects

- Every PHP page under `admin/` requires an active administrator account.
- Every PHP page under `employee/` requires an active employee account.
- Every PHP page under `customer/` requires an active customer account.
- Protected action handlers check the logged-in user and use POST where they change data.
- `actions/process_profile.php` now checks that the account is still active before accepting profile updates.
- Customer booking/payment/review pages verify that the selected booking belongs to the signed-in customer.
- Employee booking pages/actions verify that the selected booking belongs to the signed-in employee.
- Private pages send no-cache headers so browsers are less likely to show old content after sign-out.
- Session cookies use a dedicated application cookie name, HTTP-only, SameSite=Lax, and Secure when HTTPS is detected.
- Apache rules block direct web access to `includes/` and `database/`, and prevent server-side scripts from running in `uploads/`.

## What users should expect

Copying a URL does not grant access. A visitor who is not signed in is redirected to the login page. A signed-in user with the wrong role is redirected to their own dashboard. A customer or employee who changes a booking ID in the URL can only view records assigned to their own account.

A user who is signed in with the correct role can still open a page by pasting its URL. That is normal: access control checks the user's permissions, not how the user reached the page. Completely disabling URL copying is not a security feature and should not be used instead of server-side authorization.

## Server configuration note

The `.htaccess` rules in this project are for Apache/XAMPP. For Nginx or another server, configure equivalent rules directly in the web-server configuration. Always use HTTPS when deploying publicly, set a strong database password, and test with separate customer, employee, and administrator accounts.
