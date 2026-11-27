-- One-time upgrade for an EXISTING One Home database.
-- Back up the one_home database before running this file.
USE one_home;

ALTER TABLE users
  ADD INDEX idx_users_role_status (role, status),
  ADD INDEX idx_users_available (role, status, is_available),
  ADD CONSTRAINT chk_users_available CHECK (is_available IN (0,1));

ALTER TABLE services
  ADD INDEX idx_services_active_name (is_active, name),
  ADD CONSTRAINT chk_services_price CHECK (price >= 0),
  ADD CONSTRAINT chk_services_active CHECK (is_active IN (0,1));

ALTER TABLE employee_services
  ADD INDEX idx_employee_services_service (service_id, employee_id);

ALTER TABLE bookings
  ADD INDEX idx_bookings_customer_date (customer_id, booking_date),
  ADD INDEX idx_bookings_employee_date_status (employee_id, booking_date, status),
  ADD INDEX idx_bookings_service_status (service_id, status),
  ADD INDEX idx_bookings_created (created_at),
  ADD CONSTRAINT chk_bookings_price CHECK (price >= 0);

ALTER TABLE payments
  ADD INDEX idx_payments_status_date (status, paid_at),
  ADD CONSTRAINT chk_payments_amount CHECK (amount >= 0);

ALTER TABLE reviews
  ADD CONSTRAINT fk_reviews_customer FOREIGN KEY (customer_id) REFERENCES users(id),
  ADD CONSTRAINT fk_reviews_employee FOREIGN KEY (employee_id) REFERENCES users(id),
  ADD INDEX idx_reviews_employee (employee_id, created_at),
  ADD INDEX idx_reviews_customer (customer_id, created_at),
  ADD CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5);

-- Add more providers without changing existing accounts.
INSERT INTO users (name,email,password,phone,address,role,status,skills,available_days)
SELECT 'Rafael Dizon','rafael@onehome.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0918-111-1111','Bakakeng, Baguio City','employee','active','Electrical wiring, appliance troubleshooting','Mon,Tue,Wed,Thu,Fri,Sat'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='rafael@onehome.test');

INSERT INTO users (name,email,password,phone,address,role,status,skills,available_days)
SELECT 'Grace Navarro','grace@onehome.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0918-222-2222','Quezon Hill, Baguio City','employee','active','House cleaning, pest control','Mon,Tue,Wed,Thu,Fri,Sat'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='grace@onehome.test');

INSERT INTO users (name,email,password,phone,address,role,status,skills,available_days)
SELECT 'Miguel Torres','miguel@onehome.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0918-333-3333','Trancoville, Baguio City','employee','active','Painting, appliance repair','Tue,Wed,Thu,Fri,Sat'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='miguel@onehome.test');

INSERT INTO users (name,email,password,phone,address,role,status,skills,available_days)
SELECT 'Bea Flores','bea@onehome.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0918-444-4444','Camp 8, Baguio City','employee','active','Gardening, house cleaning','Mon,Wed,Thu,Fri,Sat,Sun'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='bea@onehome.test');

INSERT INTO users (name,email,password,phone,address,role,status,skills,available_days)
SELECT 'Daniel Garcia','daniel@onehome.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0918-555-5555','Happy Homes, Baguio City','employee','active','Plumbing, electrical maintenance','Mon,Tue,Wed,Thu,Fri'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='daniel@onehome.test');

INSERT IGNORE INTO employee_services (employee_id,service_id)
SELECT u.id,s.id FROM users u JOIN services s
WHERE (u.email='rafael@onehome.test' AND s.name IN ('Electrical Services','Appliance Repair'))
   OR (u.email='grace@onehome.test' AND s.name IN ('House Cleaning','Pest Control'))
   OR (u.email='miguel@onehome.test' AND s.name IN ('Painting','Appliance Repair'))
   OR (u.email='bea@onehome.test' AND s.name IN ('Gardening','House Cleaning'))
   OR (u.email='daniel@onehome.test' AND s.name IN ('Plumbing','Electrical Services'));
