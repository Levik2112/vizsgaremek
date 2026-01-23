CREATE DATABASE szalon
CHARACTER SET utf8mb4
COLLATE utf8mb4_hungarian_ci;

USE szalon;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','worker','client') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE workers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    profession VARCHAR(50),
    qualification VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    price INT,
    duration INT
);

CREATE TABLE worker_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    worker_id INT NOT NULL,
    day_of_week TINYINT NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    UNIQUE KEY uniq_day (worker_id, day_of_week)
);



CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    worker_id INT,
    service_id INT,
    appointment_time DATETIME,
    status ENUM('booked','cancelled') DEFAULT 'booked',
    FOREIGN KEY (client_id) REFERENCES users(id),
    FOREIGN KEY (worker_id) REFERENCES workers(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
);

ALTER TABLE users
ADD phone VARCHAR(30),
ADD email_change_code VARCHAR(10),
ADD password_change_code VARCHAR(10),
ADD code_expires DATETIME;

INSERT INTO users (name, email, password, role)
VALUES (
    'Admin',
    'admin',
    '$2y$10$Jc2E6qsdYXcvGhtXKmVgAuVdVsmCEMHK49Ef6VIiiPpXrVmdhdMWK',
    'admin'
);
