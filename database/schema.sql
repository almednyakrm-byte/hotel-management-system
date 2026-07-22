CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_email (email),
  INDEX idx_username (username)
);

CREATE TABLE bookings (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  room_id INT NOT NULL,
  service_id INT,
  booking_date DATE NOT NULL,
  check_in DATE NOT NULL,
  check_out DATE NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (room_id) REFERENCES rooms(id),
  FOREIGN KEY (service_id) REFERENCES services(id),
  INDEX idx_user_id (user_id),
  INDEX idx_room_id (room_id),
  INDEX idx_service_id (service_id)
);

CREATE TABLE rooms (
  id INT AUTO_INCREMENT,
  room_number INT NOT NULL,
  room_type VARCHAR(255) NOT NULL,
  capacity INT NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_room_number (room_number)
);

CREATE TABLE services (
  id INT AUTO_INCREMENT,
  service_name VARCHAR(255) NOT NULL,
  service_type VARCHAR(255) NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_service_name (service_name)
);

INSERT INTO users (username, email, password, role) VALUES
  ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin'),
  ('user1', 'user1@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'user'),
  ('guest1', 'guest1@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'guest');

INSERT INTO rooms (room_number, room_type, capacity, price) VALUES
  (101, 'Single', 1, 100.00),
  (102, 'Double', 2, 150.00),
  (103, 'Suite', 4, 250.00);

INSERT INTO services (service_name, service_type, price) VALUES
  ('Breakfast', 'Food', 10.00),
  ('Lunch', 'Food', 15.00),
  ('Dinner', 'Food', 20.00);

INSERT INTO bookings (user_id, room_id, service_id, booking_date, check_in, check_out) VALUES
  (1, 1, 1, '2022-01-01', '2022-01-02', '2022-01-03'),
  (2, 2, 2, '2022-01-05', '2022-01-06', '2022-01-07'),
  (3, 3, 3, '2022-01-10', '2022-01-11', '2022-01-12');