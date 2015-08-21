INSERT INTO `modules` (`id`, `name`, `controller_name`, `action_name`, `no_display_in_menu`, `order_by`, `online`, `created`, `modules_type_id`, `plugin_id`) VALUES
(1, 'Catégories', 'categories', '', 0, 3, 1, '2012-11-29 22:58:29', 2, 0),
(2, 'Sliders', 'sliders', '', 0, 4, 1, '2012-11-29 22:58:46', 2, 0),
(3, 'Focus', 'focus', '', 0, 5, 1, '2012-11-29 22:59:01', 2, 0),
(4, 'Boutons colonne de droite', 'right_buttons', '', 0, 6, 1, '2012-11-29 22:59:29', 2, 0),
(5, 'Articles', 'posts', '', 0, 8, 1, '2012-11-29 23:00:13', 4, 0),
(6, 'Types d''article', 'posts_types', '', 0, 9, 1, '2012-11-29 23:00:35', 4, 0),
(7, 'Commentaires articles', 'posts_comments', '', 0, 10, 1, '2012-11-29 23:01:11', 4, 0),
(8, 'Contacts', 'contacts', '', 0, 7, 1, '2012-11-29 23:06:17', 2, 0),
(9, 'Sites Internet', 'websites', '', 0, 0, 1, '2012-11-30 09:27:12', 5, 0),
(10, 'Liste des utilisateurs', 'users', '', 0, 1, 1, '2012-11-30 09:27:32', 5, 0),
(11, 'Plugins', 'plugins', '', 0, 2, 1, '2012-11-30 09:27:47', 5, 0),
(12, 'Statistiques de visites', 'dashboard', 'statistiques', 0, 2, 1, '2012-11-30 09:27:47', 7, 0),
(13, 'Tableau de bord', 'dashboard', 'version', 0, 1, 1, '2013-10-08 00:52:32', 7, 0);


INSERT INTO `modules_types` (`id`, `name`, `order_by`, `online`, `created`, `modified`, `modified_by`) VALUES
(7, 'Tableau de bord', 0, 1, '2012-12-01 06:39:27', '2012-12-01 06:39:27', 1),
(2, 'Contenus', 1, 1, '2012-12-01 06:38:44', '2012-12-01 06:38:44', 1),
(4, 'Actualités / Blog', 2, 1, '2012-12-01 06:39:16', '2012-12-01 06:39:16', 1),
(6, 'Plugins', 3, 1, '2012-12-03 22:39:48', '2012-12-03 22:39:48', 1),
(5, 'Paramètres', 4, 1, '2012-12-01 06:39:27', '2012-12-01 06:39:27', 1);

INSERT INTO `users` (`id`, `name`, `second_name`, `login`, `password`, `email`, `online`, `users_group_id`) VALUES
(1, 'Superadmin', 				'', 'superadmin', 		'superadmin', 		'superadmin@monsite.com', 		1, 1);

INSERT INTO `users_groups` (`id`, `name`, `online`, `is_deletable`, `created`, `modified`, `created_by`, `modified_by`, `role_id`) VALUES
(1, 'Super administrateur', 	1, 0, '2012-09-06 10:34:08', '2012-09-06 10:34:08', 1, 1, 1),
(2, 'Administrateur de site', 	1, 0, '2012-09-06 10:34:08', '2012-09-06 10:34:08', 1, 1, 2),
(3, 'Utilisateur', 				1, 0, '2012-09-06 10:34:08', '2012-09-06 10:34:08', 1, 1, 3);

INSERT INTO `users_groups_websites` (`users_group_id`, `website_id`) VALUES
(1, 1);

INSERT INTO `templates` (`id`, `name`, `layout`, `version`, `code`, `color`, `online`, `created`) VALUES
(1, 'Bronze', 'koezion', 'Basic', 'bronze', '5e4b52', 1, '2012-01-06 03:00:21'),
(2, 'Brown', 'koezion', 'Basic', 'brown', '665a55', 1, '2012-01-06 03:00:38'),
(3, 'Business Green', 'koezion', 'Basic', 'businessgreen', '587358', 1, '2012-01-06 03:00:53'),
(4, 'Business Purple', 'koezion', 'Basic', 'businesspurple', '4a3f5b', 1, '2012-01-06 03:01:09'),
(5, 'Business Teal', 'koezion', 'Basic', 'businessteal', '598d82', 1, '2012-01-06 03:01:24'),
(6, 'Coral Blue', 'koezion', 'Basic', 'coralblue', '5cc7ca', 1, '2012-01-06 03:01:58'),
(7, 'Crimson', 'koezion', 'Basic', 'crimson', '6a0f0f', 1, '2012-01-06 03:03:04'),
(8, 'Dark', 'koezion', 'Basic', 'dark', '292929', 1, '2012-01-06 03:03:44'),
(9, 'Deep Purple', 'koezion', 'Basic', 'deeppurple', '3c3c52', 1, '2012-01-06 03:04:09'),
(10, 'Dentist Green', 'koezion', 'Basic', 'dentistgreen', '9db29d', 1, '2012-01-06 03:04:45'),
(11, 'Friendly Grey', 'koezion', 'Basic', 'friendlygrey', '95a7a3', 1, '2012-01-06 03:05:13'),
(12, 'Funky Pink', 'koezion', 'Basic', 'funkypink', 'c867c4', 1, '2012-01-06 03:05:35'),
(13, 'Funky Purple', 'koezion', 'Basic', 'funkypurple', '926ccb', 1, '2012-01-06 03:05:58'),
(14, 'Gold', 'koezion', 'Basic', 'gold', 'b37731', 1, '2012-01-06 03:06:14'),
(15, 'Hospital Green', 'koezion', 'Basic', 'hospitalgreen', '75aa87', 1, '2012-01-06 03:06:41'),
(16, 'Intense Pink', 'koezion', 'Basic', 'intensepink', 'dc66a7', 1, '2012-01-06 03:07:04'),
(17, 'Navy Blue', 'koezion', 'Basic', 'navyblue', '3d4c54', 1, '2012-01-06 03:07:33'),
(18, 'Neon Blue', 'koezion', 'Basic', 'neonblue', '15a3d7', 1, '2012-01-06 03:07:58'),
(19, 'Ocean Blue', 'koezion', 'Basic', 'oceanblue', '2c4a59', 1, '2012-01-06 03:08:21'),
(20, 'Olive', 'koezion', 'Basic', 'olive', '73744f', 1, '2012-01-06 03:08:37'),
(21, 'Orange', 'koezion', 'Basic', 'orange', 'e17e4b', 1, '2012-01-06 03:08:56'),
(22, 'Pink Panther', 'koezion', 'Basic', 'pinkpanther', 'bf7bc7', 1, '2012-01-06 03:09:18'),
(23, 'Purple', 'koezion', 'Basic', 'pornopurple', '8752d6', 1, '2012-01-06 03:09:47'),
(24, 'Red', 'koezion', 'Basic', 'red', '9e2b2b', 1, '2012-01-06 03:10:02'),
(25, 'Sea Blue', 'koezion', 'Basic', 'seablue', '236c92', 1, '2012-01-06 03:10:24'),
(26, 'Selen', 'koezion', 'Basic', 'selen', 'c5ca19', 1, '2012-01-06 03:10:49'),
(27, 'Soft Blue', 'koezion', 'Basic', 'softblue', '056fa5', 1, '2012-01-06 03:11:12'),
(28, 'Soft Green', 'koezion', 'Basic', 'softgreen', '90a850', 1, '2012-01-06 03:11:34'),
(29, 'Soft Purple', 'koezion', 'Basic', 'softpurple', '6b6bc5', 1, '2012-01-06 03:11:56'),
(30, 'Soft Teal', 'koezion', 'Basic', 'softteal', '92c9bd', 1, '2012-01-06 03:12:25'),
(31, 'Yellow', 'koezion', 'Basic', 'yellow', 'edc72e', 1, '2012-01-06 03:13:26'),
(32, 'Brown / Orange', 'koezion', 'Basic', 'brownorange', '665a55', 1, '2012-01-06 03:00:38'),
(33, 'Orange People', 'koezion', 'Basic', 'orangepeople', 'ca5835', 1, '2012-02-24 16:10:18'),
(34, 'Neon Blue V2', 'koezion', 'Basic', 'neonblue_v2', '15a3d7', 1, '2012-01-06 03:07:58'),
(35, 'Pink&Dark', 'koezion', 'Basic', 'pinkanddark', 'e6436c', 1, '2012-01-06 03:07:58'),
(36, 'Brown Gimmick', 'koezion', 'Basic', 'brownorangegimmick', 'e17e4b', 1, '2012-01-06 03:00:38'),
(37, 'Dark V2', 'koezion', 'Basic', 'dark_v2', '292929', 1, '2012-01-06 03:03:44'),
(38, 'Chesterfield', 'koezion', 'Basic', 'chesterfield', '292929', 1, '2012-01-06 03:03:44'),
(39, 'Brown chocolate', 'koezion', 'Chocolate', 'chocolate_brown', '000000', 1, '2013-11-09 03:03:44'),
(40, 'Néon blue chocolate', 'koezion', 'Chocolate', 'chocolate_neonblue', '000000', 1, '2013-11-09 03:03:44'),
(41, 'Purple chocolate', 'koezion', 'Chocolate', 'chocolate_purple', '000000', 1, '2013-11-09 03:03:44'),
(42, 'Red chocolate', 'koezion', 'Chocolate', 'chocolate_red', '000000', 1, '2013-11-09 03:03:44'),
(43, 'Empty', 'empty', 'empty', 'empty', '000000', 1, '2014-07-15 03:03:44');

INSERT INTO `unwanted_crawlers` (`id`, `name`, `url`, `ip`, `online`, `created`) VALUES
(1, 'I LOVE ITALY', 'iloveitaly', '208.73.211.70', 1, '2015-08-20 18:42:17'),
(3, 'FLOATING SHARE BUTTONS', 'floating-share-buttons', '', 1, '2015-08-21 11:28:24'),
(4, 'TRAFFIC 2 MONEY', 'traffic2money', '', 1, '2015-08-21 11:28:42'),
(5, '4 WEBMASTERS', '4webmasters.org', '', 1, '2015-08-21 11:29:12'),
(6, 'FREE FLOATING BUTTONS', 'free-floating-buttons', '', 1, '2015-08-21 11:29:45'),
(7, 'SEXYALI', 'sexyali', '', 1, '2015-08-21 11:30:12'),
(8, 'GET FREE SOCIAL TRAFFIC', 'get-free-social-traffic', '', 1, '2015-08-21 11:30:33'),
(9, 'SUCCESS SEO', 'success-seo', '', 1, '2015-08-21 11:31:00'),
(10, 'TRAFFIC MONETIZER', 'trafficmonetizer', '', 1, '2015-08-21 11:31:23'),
(11, 'FREE SOCIAL BUTTONS', 'free-social-buttons', '', 1, '2015-08-21 11:31:44'),
(12, 'EVENT TRACKING', 'event-tracking', '', 1, '2015-08-21 11:32:32'),
(13, 'EROT', 'erot.co', '', 1, '2015-08-21 11:32:57'),
(14, 'GET FREE TRAFFIC NOW', 'Get-Free-Traffic-Now', '', 1, '2015-08-21 11:33:31'),
(15, 'BUTTONS FOR WEBSITE', 'buttons-for-website', '', 1, '2015-08-21 11:33:49'),
(16, 'CHINESE AMEZON', 'chinese-amezon', '', 1, '2015-08-21 11:34:19'),
(17, 'E BUYEASY', 'e-buyeasy', '', 1, '2015-08-21 11:35:21'),
(18, 'VIDEOS FOR YOUR BUSINESS', 'videos-for-your-business', '', 1, '2015-08-21 11:35:54'),
(19, 'HONGFANJI', 'hongfanji', '', 1, '2015-08-21 11:36:36'),
(20, 'DARODAR', 'darodar', '', 1, '2015-08-21 11:37:12'),
(21, 'HOW TO EARN QUICK MONEY', 'how-to-earn-quick-money', '', 1, '2015-08-21 11:38:02'),
(22, 'GW EC', 'gw-ec', '', 1, '2015-08-21 11:38:50'),
(23, 'SEMALT', 'semalt', '', 1, '2015-08-21 11:38:50');