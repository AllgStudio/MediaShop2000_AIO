CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO User VALUES (1, 'admin', '$2y$10$rsiFtq97A7W58Fs7ZX27nelrK9qItNqnNicgGjWlPpHg1tfgDaMB2','admin@admin.com', 'admin', '2024-01-01 00:00:00', '2024-01-01 00:00:00');
INSERT INTO User VALUES (2, 'user', '$2y$10$/XHfArdPL0gGkMEpKMfmde4sw5XfdqW/hoqBHKQi9N4rjbEw7Xb06','user@user.com', 'user', '2024-01-01 00:00:00', '2024-01-01 00:00:00');


