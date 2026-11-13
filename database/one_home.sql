-- One Home database. Import this in phpMyAdmin (Import tab). All demo accounts use the password: password
CREATE DATABASE IF NOT EXISTS one_home CHARACTER SET utf8mb4;
USE one_home;
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS reviews, payments, bookings, employee_services, services, users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(30),
  address VARCHAR(255),
  role ENUM('admin','employee','customer') NOT NULL,
  status ENUM('active','pending','inactive') NOT NULL DEFAULT 'active',
  avatar VARCHAR(255),
  skills VARCHAR(255),
  available_days VARCHAR(40) NOT NULL DEFAULT 'Mon,Tue,Wed,Thu,Fri',
  is_available TINYINT NOT NULL DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  icon VARCHAR(50) NOT NULL DEFAULT 'bi-tools',
  is_active TINYINT NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE employee_services (
  employee_id INT NOT NULL,
  service_id INT NOT NULL,
  PRIMARY KEY (employee_id, service_id),
  FOREIGN KEY (employee_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  employee_id INT NOT NULL,
  service_id INT NOT NULL,
  booking_date DATE NOT NULL,
  booking_time TIME NOT NULL,
  address VARCHAR(255) NOT NULL,
  description TEXT,
  photo VARCHAR(255),
  price DECIMAL(10,2) NOT NULL,
  status ENUM('Pending','Confirmed','Ongoing','Completed','Cancelled','Declined') NOT NULL DEFAULT 'Pending',
  decline_reason VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES users(id),
  FOREIGN KEY (employee_id) REFERENCES users(id),
  FOREIGN KEY (service_id) REFERENCES services(id)
) ENGINE=InnoDB;

CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT NOT NULL UNIQUE,
  amount DECIMAL(10,2) NOT NULL,
  method ENUM('cash','card') NOT NULL DEFAULT 'cash',
  status ENUM('Unpaid','Paid','Refunded') NOT NULL DEFAULT 'Unpaid',
  card_last4 CHAR(4),
  paid_at DATETIME NULL,
  FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT NOT NULL UNIQUE,
  customer_id INT NOT NULL,
  employee_id INT NOT NULL,
  rating TINYINT NOT NULL,
  comment TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------- Demo data (password for everyone: password) ----------
INSERT INTO users (id,name,email,password,phone,address,role,status,skills,available_days) VALUES
(1,'Admin','admin@onehome.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0917-000-0001','Baguio City','admin','active',NULL,'Mon,Tue,Wed,Thu,Fri'),
(2,'Juan Dela Cruz','juan@onehome.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0917-111-1111','Aurora Hill, Baguio City','employee','active','Pipe repair, leak fixing, water heater installation','Mon,Tue,Wed,Thu,Fri,Sat'),
(3,'Maria Santos','maria@onehome.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0917-222-2222','Irisan, Baguio City','employee','active','Wiring, outlets, lighting, appliance repair','Mon,Tue,Wed,Thu,Fri'),
(4,'Pedro Bautista','pedro@onehome.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0917-333-3333','Pacdal, Baguio City','employee','active','Deep cleaning, garden care','Tue,Wed,Thu,Fri,Sat,Sun'),
(5,'Ana Reyes','ana@onehome.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0917-444-4444','Loakan, Baguio City','employee','active','Plumbing, cleaning, pest control','Mon,Wed,Fri,Sat,Sun'),
(6,'Carlo Mendoza','carlo@onehome.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0917-555-5555','Camp 7, Baguio City','employee','pending','Plumber (new applicant)','Mon,Tue,Wed,Thu,Fri'),
(7,'Liza Ramos','liza@onehome.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0918-666-6666','Session Road area, Baguio City','customer','active',NULL,'Mon,Tue,Wed,Thu,Fri'),
(8,'Mark Villanueva','mark@onehome.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0918-777-7777','Camp 7, Baguio City','customer','active',NULL,'Mon,Tue,Wed,Thu,Fri');

INSERT INTO services (id,name,description,price,icon) VALUES
(1,'Plumbing','Leaks, clogged drains, faucet and toilet repair, pipe installation.',500,'bi-droplet-fill'),
(2,'Electrical Services','Wiring, outlets, switches, lighting and circuit breaker problems.',600,'bi-lightning-charge-fill'),
(3,'House Cleaning','General and deep cleaning for your whole house.',800,'bi-stars'),
(4,'Appliance Repair','Washing machine, refrigerator, TV and other appliance repair.',450,'bi-wrench-adjustable'),
(5,'Pest Control','Termite, cockroach, rat and ant treatment.',700,'bi-bug-fill'),
(6,'Painting','Interior and exterior wall painting.',1200,'bi-paint-bucket'),
(7,'Gardening','Lawn mowing, plant care and garden clean-up.',400,'bi-flower1');

INSERT INTO employee_services (employee_id,service_id) VALUES (2,1),(2,6),(3,2),(3,4),(4,3),(4,7),(5,1),(5,3),(5,5),(6,1);

INSERT INTO bookings (id,customer_id,employee_id,service_id,booking_date,booking_time,address,description,price,status) VALUES
(1,7,2,1,'2026-08-12','09:00:00','Session Road area, Baguio City','Kitchen sink is leaking.',500,'Completed'),
(2,7,3,2,'2026-09-05','10:00:00','Session Road area, Baguio City','Outlet in the living room sparks.',600,'Completed'),
(3,8,2,1,'2026-09-20','14:00:00','Camp 7, Baguio City','Clogged bathroom drain.',500,'Completed'),
(4,8,4,3,'2026-10-25','09:00:00','Camp 7, Baguio City','Deep cleaning of a 2-storey house.',800,'Pending'),
(5,7,3,4,'2026-10-22','13:00:00','Session Road area, Baguio City','Washing machine will not spin.',450,'Confirmed');

INSERT INTO payments (booking_id,amount,method,status,card_last4,paid_at) VALUES
(1,500,'cash','Paid',NULL,'2026-08-12 11:00:00'),
(2,600,'card','Paid','4242','2026-09-05 12:30:00'),
(3,500,'cash','Unpaid',NULL,NULL),
(4,800,'cash','Unpaid',NULL,NULL),
(5,450,'card','Unpaid',NULL,NULL);

INSERT INTO reviews (booking_id,customer_id,employee_id,rating,comment) VALUES
(1,7,2,5,'Fast and clean work. Fixed our leaking sink in one hour!'),
(2,7,3,4,'Good service, a little late but the work was great.');
