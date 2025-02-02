CREATE DATABASE event_management;

USE event_management;

-- Users Table (Admins & Users)
CREATE TABLE
    users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM ('admin', 'user') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

-- Events Table
CREATE TABLE
    events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        max_capacity INT UNSIGNED NOT NULL CHECK (max_capacity > 0),
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE CASCADE
    );

-- Attendees Table
CREATE TABLE
    attendees (
        id INT AUTO_INCREMENT PRIMARY KEY,
        event_id INT NOT NULL,
        user_id INT NOT NULL,
        registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE (event_id, user_id), -- Prevent duplicate registrations
        FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
    );