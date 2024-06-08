USE ServiceExchangeHadiAdam;

-- Seed Roles
INSERT INTO Roles (role_name) VALUES
('admin'),
('user');

-- Seed Users
INSERT INTO Users (username, password, email, time_credit, role_id) VALUES
('admin', 'admin_password_hash', 'admin@example.com', 10, 1),
('hadi', 'password_hash', 'hadi@example.com', 10, 2),
('adam', 'password_hash', 'adam@example.com', 10, 2),
('chris', 'password_hash', 'chris@example.com', 10, 2),
('mehdi', 'password_hash', 'mehdi@example.com', 10, 2);

-- Seed Categories
INSERT INTO Categories (category_logical_value, category_name) VALUES
('gardening', 'Jardinage'),
('sewing', 'Couture'),
('it_support', 'Support Informatique'),
('painting', 'Peinture'),
('tutoring', 'Soutien Scolaire'),
('pet_sitting', 'Garde d\'animaux');

-- Seed Services
INSERT INTO Services (user_id, category_id, service_type, title, description, is_published) VALUES
(2, 1, 'Jardinage', 'Tonte de pelouse', 'Je vais tondre votre pelouse pendant 1 heure.', true),
(3, 2, 'Couture', 'Retouches de vêtements', 'Je peux retoucher vos vêtements pour mieux les ajuster.', true),
(4, 3, 'Support Informatique', 'Réparation d\'ordinateur', 'Je vais réparer vos problèmes informatiques.', true),
(2, 4, 'Peinture', 'Peinture de murs', 'Je peux peindre vos murs avec la couleur de votre choix.', false),
(3, 5, 'Soutien Scolaire', 'Tutorat de mathématiques', 'Je peux tutorat en mathématiques pour les élèves du secondaire.', true),
(4, 6, 'Garde d\'animaux', 'Promenade de chien', 'Je vais promener votre chien pendant une heure.', true);

-- Seed Service Requests Status
INSERT INTO Service_Requests_Status (status_logical_value, status_label) VALUES
('pending', 'En attente'),
('accepted', 'Acceptée'),
('completed', 'Complétée'),
('rejected', 'Refusée');

-- Seed Service Requests
INSERT INTO Service_Requests (service_id, requester_id, provider_id, request_status_id, requested_hours, requested_date) VALUES
(1, 3, 2, 2, 1, '2024-06-10 10:00:00'),
(2, 4, 3, 3, 2, '2024-06-11 11:00:00'),
(3, 2, 4, 1, 1, '2024-06-12 12:00:00');

-- Seed Transactions
INSERT INTO Transactions (service_id, provider_id, receiver_id, hours_exchanged, transaction_date) VALUES
(2, 3, 4, 2, '2024-06-11 13:00:00');

-- Seed Reviews
INSERT INTO Reviews (service_id, reviewer_id, rating, comment) VALUES
(1, 3, 5, 'Service excellent et rapide.'),
(2, 4, 4, 'Bon travail mais pourrait être plus rapide.'),
(3, 2, 5, 'Très satisfait du service.');

-- Seed Notifications
INSERT INTO Notifications (user_id, message, is_read) VALUES
(2, 'Votre demande de service a été acceptée.', false),
(3, 'Votre demande de service a été complétée.', true),
(4, 'Vous avez reçu un nouveau message.', false);

-- Seed Messages
INSERT INTO Messages (sender_id, receiver_id, content, sent_at) VALUES
(2, 3, 'Pouvez-vous m\'aider avec ma pelouse ?', '2024-06-09 09:00:00'),
(3, 4, 'Oui, bien sûr !', '2024-06-09 09:15:00'),
(4, 2, 'Merci beaucoup !', '2024-06-09 09:30:00');

-- Seed Favorites
INSERT INTO Favorites (user_id, service_id) VALUES
(2, 1),
(3, 2),
(4, 3);

-- Seed Badges
INSERT INTO Badges (badge_logical_value, badge_name, description) VALUES
('helper', 'Helper', 'Attribué pour avoir aidé 10 personnes.'),
('super_helper', 'Super Helper', 'Attribué pour avoir aidé 50 personnes.'),
('top_helper', 'Top Helper', 'Attribué pour avoir aidé 100 personnes.');

-- Seed User Badges
INSERT INTO User_Badges (user_id, badge_id, awarded_at) VALUES
(2, 1, '2024-06-10 10:00:00'),
(3, 2, '2024-06-11 11:00:00'),
(4, 3, '2024-06-12 12:00:00');