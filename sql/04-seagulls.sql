CREATE DATABASE IF NOT EXISTS trefiskmasar_db;
USE trefiskmasar_db;

CREATE TABLE IF NOT EXISTS seagulls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    species_name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    habitat VARCHAR(255),
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);