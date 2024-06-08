CREATE DATABASE ServiceExchangeHadiAdam;
USE ServiceExchangeHadiAdam;

-- Table Roles
CREATE TABLE Roles (
  role_id INT AUTO_INCREMENT PRIMARY KEY,
  role_name VARCHAR(255) NOT NULL
);

-- Table Users
CREATE TABLE Users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  time_credit INT NOT NULL DEFAULT 10,
  role_id INT NOT NULL,
  FOREIGN KEY (role_id) REFERENCES Roles(role_id)
);

-- Table Categories
CREATE TABLE Categories (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  category_logical_value VARCHAR(255) NOT NULL,
  category_name VARCHAR(255) NOT NULL
);

-- Table Services
CREATE TABLE Services (
  service_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT  NOT NULL,
  category_id INT,
  service_type VARCHAR(255) NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  is_published BOOLEAN NOT NULL DEFAULT FALSE,
  FOREIGN KEY (user_id) REFERENCES Users(user_id),
  FOREIGN KEY (category_id) REFERENCES Categories(category_id)
);

-- Table Service_Requests
CREATE TABLE Service_Requests (
  request_id INT AUTO_INCREMENT PRIMARY KEY,
  service_id INT,
  requester_id INT,
  provider_id INT,
  request_status_id INT,
  requested_hours INT NOT NULL,
  requested_date DATETIME NOT NULL,
  FOREIGN KEY (service_id) REFERENCES Services(service_id),
  FOREIGN KEY (requester_id) REFERENCES Users(user_id),
  FOREIGN KEY (provider_id) REFERENCES Users(user_id),
  FOREIGN KEY (request_status_id) REFERENCES Users(user_id)
);

-- Table Service_Requests
CREATE TABLE Service_Requests_Status (
  status_id INT AUTO_INCREMENT PRIMARY KEY,
  status_logical_value VARCHAR(255) NOT NULL,
  status_label VARCHAR(255) NOT NULL
);

-- Table Transactions
CREATE TABLE Transactions (
  transaction_id INT AUTO_INCREMENT PRIMARY KEY,
  service_id INT,
  provider_id INT,
  receiver_id INT,
  hours_exchanged INT NOT NULL,
  transaction_date DATETIME NOT NULL,
  FOREIGN KEY (service_id) REFERENCES Services(service_id),
  FOREIGN KEY (provider_id) REFERENCES Users(user_id),
  FOREIGN KEY (receiver_id) REFERENCES Users(user_id)
);

-- Table Reviews
CREATE TABLE Reviews (
  review_id INT AUTO_INCREMENT PRIMARY KEY,
  service_id INT,
  reviewer_id INT,
  rating INT NOT NULL,
  comment TEXT,
  FOREIGN KEY (service_id) REFERENCES Services(service_id),
  FOREIGN KEY (reviewer_id) REFERENCES Users(user_id)
);

-- Table Notifications
CREATE TABLE Notifications (
  notification_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  message TEXT NOT NULL,
  is_read BOOLEAN NOT NULL DEFAULT FALSE,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Table Messages
CREATE TABLE Messages (
  message_id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT,
  receiver_id INT,
  content TEXT NOT NULL,
  sent_at DATETIME NOT NULL,
  FOREIGN KEY (sender_id) REFERENCES Users(user_id),
  FOREIGN KEY (receiver_id) REFERENCES Users(user_id)
);

-- Table Favorites
CREATE TABLE Favorites (
  favorite_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  service_id INT,
  FOREIGN KEY (user_id) REFERENCES Users(user_id),
  FOREIGN KEY (service_id) REFERENCES Services(service_id)
);

-- Table Badges
CREATE TABLE Badges (
  badge_id INT AUTO_INCREMENT PRIMARY KEY,
  badge_logical_value VARCHAR(255) NOT NULL,
  badge_name VARCHAR(255) NOT NULL,
  description TEXT
);

-- Table User_Badges
CREATE TABLE User_Badges (
  user_badge_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  badge_id INT,
  awarded_at DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(user_id),
  FOREIGN KEY (badge_id) REFERENCES Badges(badge_id)
);
