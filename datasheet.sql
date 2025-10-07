CREATE DATABASE portal_db;
USE portal_db;

CREATE TABLE events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  event_name VARCHAR(100),
  event_date DATE,
  description TEXT
);
