-- Create the database if it doesn't exist and select it for use.
CREATE DATABASE IF NOT EXISTS trefiskmasar_db;
USE trefiskmasar_db;

-- Table for user accounts
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user' NOT NULL,
    status ENUM('pending', 'approved') DEFAULT 'approved' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for contact form messages
CREATE TABLE IF NOT EXISTS contacts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for seagull data
CREATE TABLE IF NOT EXISTS seagulls (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    species_name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    habitat VARCHAR(255),
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for API keys, correctly linked to the users table
CREATE TABLE IF NOT EXISTS api_keys (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    -- THIS IS THE FIX: user_id can now be NULL, allowing for unassigned keys.
    user_id INT UNSIGNED DEFAULT NULL,
    api_key VARCHAR(255) NOT NULL UNIQUE,
    -- Add role to key, Admin or user for different uses
    is_active BOOLEAN DEFAULT TRUE,
    requests_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Add some sample seagull data to populate the directory
INSERT INTO seagulls (species_name, description, habitat, image_url) VALUES
('Herring Gull', 'A large, noisy gull found across North America, Europe, and Asia. Known for its intelligence and adaptability.', 'Coasts, lakes, and urban areas', 'public/images/seagulls/herring_gull.jpg'),
('Black-headed Gull', 'A small, sociable gull with a distinctive dark brown head in summer (not actually black).', 'Marshes, lakes, and coastal areas', 'public/images/seagulls/black_headed_gull.jpg'),
('Great Black-backed Gull', 'The largest species of gull in the world. It is a powerful and opportunistic predator and scavenger.', 'North Atlantic coasts', 'public/images/seagulls/great_black_backed_gull.jpg');