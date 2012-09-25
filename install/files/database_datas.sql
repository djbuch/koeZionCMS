INSERT INTO `users` (`id`, `name`, `second_name`, `login`, `password`, `email`, `role`, `online`, `users_group_id`) VALUES
(1, 'Administrateur Général', '', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@admin.com', 'admin', 1, 1);

INSERT INTO `users_groups` (`id`, `name`, `online`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(1, 'Administrateur Général', 1, '2012-09-06 10:34:08', '2012-09-06 10:34:08', 1, 1);

INSERT INTO `users_groups_websites` (`users_group_id`, `website_id`) VALUES
(1, 1);

INSERT INTO `templates` (`id`, `name`, `layout`, `code`, `color`, `online`, `created`) VALUES
(1, 'Bronze', 'frontoffice', 'bronze', '5e4b52', 1, '2012-01-06 03:00:21'),
(2, 'Brown', 'frontoffice', 'brown', '665a55', 1, '2012-01-06 03:00:38'),
(3, 'Business Green', 'frontoffice', 'businessgreen', '587358', 1, '2012-01-06 03:00:53'),
(4, 'Business Purple', 'frontoffice', 'businesspurple', '4a3f5b', 1, '2012-01-06 03:01:09'),
(5, 'Business Teal', 'frontoffice', 'businessteal', '598d82', 1, '2012-01-06 03:01:24'),
(6, 'Coral Blue', 'frontoffice', 'coralblue', '5cc7ca', 1, '2012-01-06 03:01:58'),
(7, 'Crimson', 'frontoffice', 'crimson', '6a0f0f', 1, '2012-01-06 03:03:04'),
(8, 'Dark', 'frontoffice', 'dark', '292929', 1, '2012-01-06 03:03:44'),
(9, 'Deep Purple', 'frontoffice', 'deeppurple', '3c3c52', 1, '2012-01-06 03:04:09'),
(10, 'Dentist Green', 'frontoffice', 'dentistgreen', '9db29d', 1, '2012-01-06 03:04:45'),
(11, 'Friendly Grey', 'frontoffice', 'friendlygrey', '95a7a3', 1, '2012-01-06 03:05:13'),
(12, 'Funky Pink', 'frontoffice', 'funkypink', 'c867c4', 1, '2012-01-06 03:05:35'),
(13, 'Funky Purple', 'frontoffice', 'funkypurple', '926ccb', 1, '2012-01-06 03:05:58'),
(14, 'Gold', 'frontoffice', 'gold', 'b37731', 1, '2012-01-06 03:06:14'),
(15, 'Hospital Green', 'frontoffice', 'hospitalgreen', '75aa87', 1, '2012-01-06 03:06:41'),
(16, 'Intense Pink', 'frontoffice', 'intensepink', 'dc66a7', 1, '2012-01-06 03:07:04'),
(17, 'Navy Blue', 'frontoffice', 'navyblue', '3d4c54', 1, '2012-01-06 03:07:33'),
(18, 'Neon Blue', 'frontoffice', 'neonblue', '15a3d7', 1, '2012-01-06 03:07:58'),
(19, 'Ocean Blue', 'frontoffice', 'oceanblue', '2c4a59', 1, '2012-01-06 03:08:21'),
(20, 'Olive', 'frontoffice', 'olive', '73744f', 1, '2012-01-06 03:08:37'),
(21, 'Orange', 'frontoffice', 'orange', 'e17e4b', 1, '2012-01-06 03:08:56'),
(22, 'Pink Panther', 'frontoffice', 'pinkpanther', 'bf7bc7', 1, '2012-01-06 03:09:18'),
(23, 'Purple', 'frontoffice', 'pornopurple', '8752d6', 1, '2012-01-06 03:09:47'),
(24, 'Red', 'frontoffice', 'red', '9e2b2b', 1, '2012-01-06 03:10:02'),
(25, 'Sea Blue', 'frontoffice', 'seablue', '236c92', 1, '2012-01-06 03:10:24'),
(26, 'Selen', 'frontoffice', 'selen', 'c5ca19', 1, '2012-01-06 03:10:49'),
(27, 'Soft Blue', 'frontoffice', 'softblue', '056fa5', 1, '2012-01-06 03:11:12'),
(28, 'Soft Green', 'frontoffice', 'softgreen', '90a850', 1, '2012-01-06 03:11:34'),
(29, 'Soft Purple', 'frontoffice', 'softpurple', '6b6bc5', 1, '2012-01-06 03:11:56'),
(30, 'Soft Teal', 'frontoffice', 'softteal', '92c9bd', 1, '2012-01-06 03:12:25'),
(31, 'Yellow', 'frontoffice', 'yellow', 'edc72e', 1, '2012-01-06 03:13:26'),
(32, 'Brown / Orange', 'frontoffice', 'brownorange', '665a55', 1, '2012-01-06 03:00:38'),
(33, 'Orange People', 'frontoffice', 'orangepeople', 'ca5835', 1, '2012-02-24 16:10:18'),
(34, 'Neon Blue V2', 'frontoffice', 'neonblue_v2', '15a3d7', 1, '2012-01-06 03:07:58'),
(35, 'Pink&Dark', 'frontoffice', 'pinkanddark', 'e6436c', 1, '2012-01-06 03:07:58'),
(36, 'Brown Gimmick', 'frontoffice', 'brownorangegimmick', 'e17e4b', 1, '2012-01-06 03:00:38');