--
-- Contenu de la table `modules`
--

INSERT INTO `modules` (`id`, `name`, `controller_name`, `action_name`, `others_actions`, `prohibited_actions`, `icon`, `no_display_in_menu`, `order_by`, `online`, `created`, `modules_type_id`, `plugin_id`) VALUES
(1, 'Pages', 'categories', '', '', '', '', 0, 3, 1, '2012-11-29 22:58:29', 2, 0),
(2, 'Slides', 'sliders', '', '', '', '', 0, 4, 1, '2012-11-29 22:58:46', 2, 0),
(3, 'Focus', 'focus', '', '', '', '', 0, 5, 1, '2012-11-29 22:59:01', 2, 0),
(4, 'Widgets colonne', 'right_buttons', '', '', '', '', 0, 6, 1, '2012-11-29 22:59:29', 2, 0),
(5, 'Articles', 'posts', '', '', '', '', 0, 0, 1, '2012-11-29 23:00:13', 4, 0),
(6, 'Types d\'article', 'posts_types', '', '', '', '', 0, 1, 1, '2012-11-29 23:00:35', 4, 0),
(7, 'Commentaires articles', 'posts_comments', '', '', '', '', 0, 2, 1, '2012-11-29 23:01:11', 4, 0),
(8, 'Contacts', 'contacts', '', '', '', '', 0, 0, 1, '2012-11-29 23:06:17', 10, 0),
(9, 'Sites Internet', 'websites', '', '', '', '', 0, 0, 1, '2012-11-30 09:27:12', 8, 0),
(10, 'Utilisateurs', 'users', '', '', '', '', 0, 1, 1, '2012-11-30 09:27:32', 9, 0),
(11, 'Plugins', 'plugins', '', '', '', '', 0, 2, 1, '2012-11-30 09:27:47', 6, 0),
(13, 'Tableau de bord', 'dashboard', '', '', '', '', 0, 1, 1, '2013-10-08 00:52:32', 7, 0),
(14, 'Newsletter', 'contacts', 'newsletter', '', '', '', 0, 1, 1, '2016-04-07 10:46:39', 10, 0),
(15, 'Paramètres', 'configs', 'posts_liste', '', '', '', 0, 3, 1, '2016-04-07 10:48:57', 4, 0),
(16, 'Exports', 'exports', '', '', '', '', 0, 0, 1, '2016-04-08 17:50:57', 11, 0),
(17, 'Portfolios', 'portfolios', '', '', '', '', 0, 1, 1, '2016-04-11 16:51:28', 12, 0),
(18, 'Eléments de portfolios', 'portfolios_elements', '', '', '', '', 0, 2, 1, '2016-04-11 16:51:28', 12, 0),
(19, 'Types de portfolios', 'portfolios_types', '', '', '', '', 0, 3, 1, '2016-04-12 15:08:48', 12, 0),
(20, 'Paramètres', 'configs', 'portfolios_liste', '', '', '', 0, 4, 1, '2016-04-12 18:28:45', 12, 0);

--
-- Contenu de la table `modules_types`
--
INSERT INTO `modules_types` (`id`, `name`, `icon`, `order_by`, `online`, `created`, `modified`, `modified_by`, `plugin_id`) VALUES
(7, 'TABLEAU DE BORD', 'fa fa-dashboard', 0, 1, '2012-12-01 06:39:27', '2015-09-15 08:54:33', 1, 0),
(2, 'CONTENUS', 'fa fa-folder-open', 1, 1, '2012-12-01 06:38:44', '2015-09-15 08:59:51', 1, 0),
(4, 'ACTUALITES / BLOG', 'fa fa-comments', 2, 1, '2012-12-01 06:39:16', '2015-09-15 09:00:07', 1, 0),
(6, 'PLUGINS', 'fa fa-plug', 7, 1, '2012-12-03 22:39:48', '2015-09-15 09:00:17', 1, 0),
(5, 'PARAMETRES', 'fa fa-cogs', 10, 1, '2012-12-01 06:39:27', '2015-09-15 09:00:35', 1, 0),
(8, 'SITES INTERNET', 'fa fa-globe', 8, 1, '2016-04-07 10:44:41', '2016-04-07 14:58:15', 1, 0),
(9, 'UTILISATEURS', 'fa fa-user', 9, 1, '2016-04-07 10:44:49', '2016-04-07 14:57:19', 1, 0),
(10, 'CONTACTS', 'fa fa-envelope', 3, 1, '2016-04-07 10:45:25', '2016-04-07 14:57:45', 1, 0),
(11, 'EXPORTS', 'fa fa-download', 6, 1, '2016-04-08 17:50:15', '2016-04-08 17:50:15', 1, 0),
(12, 'PORTFOLIOS', 'fa fa-picture-o', 4, 1, '2016-04-11 17:50:15', '2016-04-11 17:50:15', 1, 0),
(13, 'EXPORTS', 'fa fa-download', 5, 1, '2016-04-11 16:52:52', '2016-04-11 16:52:52', 1, 0);

INSERT INTO `users` (`id`, `name`, `second_name`, `login`, `password`, `email`, `online`, `users_group_id`) VALUES
(1, 'Superadmin', 				'', 'superadmin', 		'superadmin', 		'superadmin@monsite.com', 		1, 1);

INSERT INTO `users_groups` (`id`, `name`, `online`, `is_deletable`, `created`, `modified`, `created_by`, `modified_by`, `role_id`) VALUES
(1, 'Super administrateur', 	1, 0, '2012-09-06 10:34:08', '2012-09-06 10:34:08', 1, 1, 1),
(2, 'Administrateur de site', 	1, 0, '2012-09-06 10:34:08', '2012-09-06 10:34:08', 1, 1, 2),
(3, 'Utilisateur', 				1, 0, '2012-09-06 10:34:08', '2012-09-06 10:34:08', 1, 1, 3);

INSERT INTO `users_groups_websites` (`users_group_id`, `website_id`) VALUES
(1, 1);

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