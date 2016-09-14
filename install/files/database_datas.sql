--
-- Contenu de la table `modules`
--

INSERT INTO `modules` (`id`, `name`, `controller_name`, `action_name`, `others_actions`, `prohibited_actions`, `icon`, `no_display_in_menu`, `order_by`, `online`, `created`, `modules_type_id`, `plugin_id`) VALUES
(1, 'Pages', 'categories', '', '', '', '', 0, 3, 1, CURRENT_TIMESTAMP, 2, 0),
(2, 'Slides', 'sliders', '', '', '', '', 0, 4, 1, CURRENT_TIMESTAMP, 2, 0),
(3, 'Focus', 'focus', '', '', '', '', 0, 5, 1, CURRENT_TIMESTAMP, 2, 0),
(4, 'Widgets colonne', 'right_buttons', '', '', '', '', 0, 6, 1, CURRENT_TIMESTAMP, 2, 0),
(5, 'Articles', 'posts', '', '', '', '', 0, 0, 1, CURRENT_TIMESTAMP, 4, 0),
(6, 'Types d\'article', 'posts_types', '', '', '', '', 0, 1, 1, CURRENT_TIMESTAMP, 4, 0),
(7, 'Commentaires articles', 'posts_comments', '', '', '', '', 0, 2, 1, CURRENT_TIMESTAMP, 4, 0),
(8, 'Contacts', 'contacts', '', '', '', '', 0, 0, 1, CURRENT_TIMESTAMP, 10, 0),
(9, 'Sites Internet', 'websites', '', '', '', '', 0, 0, 1, CURRENT_TIMESTAMP, 8, 0),
(10, 'Utilisateurs', 'users', '', '', '', '', 0, 1, 1, CURRENT_TIMESTAMP, 9, 0),
(11, 'Plugins', 'plugins', '', '', '', '', 0, 2, 1, CURRENT_TIMESTAMP, 6, 0),
(13, 'Tableau de bord', 'dashboard', '', '', '', '', 0, 1, 1, CURRENT_TIMESTAMP, 7, 0),
(14, 'Newsletter', 'contacts', 'newsletter', '', '', '', 0, 1, 1, CURRENT_TIMESTAMP, 10, 0),
(16, 'Exports', 'exports', '', '', '', '', 0, 0, 1, CURRENT_TIMESTAMP, 11, 0),
(17, 'Portfolios', 'portfolios', '', '', '', '', 0, 1, 1, CURRENT_TIMESTAMP, 12, 0),
(18, 'Eléments de portfolios', 'portfolios_elements', '', '', '', '', 0, 2, 1, CURRENT_TIMESTAMP, 12, 0),
(19, 'Types de portfolios', 'portfolios_types', '', '', '', '', 0, 3, 1, CURRENT_TIMESTAMP, 12, 0),
(20, 'Paramètres', 'configs', 'portfolios_liste', '', '', '', 0, 4, 1, CURRENT_TIMESTAMP, 12, 0);

--
-- Contenu de la table `modules_types`
--
INSERT INTO `modules_types` (`id`, `name`, `icon`, `order_by`, `online`, `created`, `modified`, `modified_by`, `plugin_id`) VALUES
(7, 'TABLEAU DE BORD', 'fa fa-dashboard', 0, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 0),
(2, 'CONTENUS', 'fa fa-folder-open', 1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 0),
(4, 'ACTUALITES / BLOG', 'fa fa-comments', 2, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 0),
(6, 'PLUGINS', 'fa fa-plug', 7, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 0),
(5, 'PARAMETRES', 'fa fa-cogs', 10, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 0),
(8, 'SITES INTERNET', 'fa fa-globe', 8, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 0),
(9, 'UTILISATEURS', 'fa fa-user', 9, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 0),
(10, 'CONTACTS', 'fa fa-envelope', 3, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 0),
(11, 'EXPORTS', 'fa fa-download', 6, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 0),
(12, 'PORTFOLIOS', 'fa fa-picture-o', 4, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 0);

INSERT INTO `users` (`id`, `lastname`, `firstname`, `phone`, `mobile`, `email`, `password`, `date_of_birth`, `facebook_id`, `facebook_link`, `facebook_locale`, `online`, `created`, `modified`, `customers_type_id`, `users_group_id`) VALUES
(1, 'Superadmin', '', '', '', 'superadmin', 'superadmin', '0000-00-00', '', '', '', 1, '0000-00-00 00:00:00', '2016-06-06 16:56:45', 0, 1);

INSERT INTO `users_groups` (`id`, `name`, `online`, `is_deletable`, `created`, `modified`, `created_by`, `modified_by`, `role_id`) VALUES
(1, 'Super administrateur', 	1, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 1, 1),
(2, 'Administrateur de site', 	1, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 1, 2),
(3, 'Utilisateur', 				1, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 1, 3);

INSERT INTO `users_groups_websites` (`users_group_id`, `website_id`) VALUES
(1, 1);

INSERT INTO `unwanted_crawlers` (`id`, `name`, `url`, `ip`, `online`, `created`) VALUES
(1, 'I LOVE ITALY', 'iloveitaly', '208.73.211.70', 1, CURRENT_TIMESTAMP),
(3, 'FLOATING SHARE BUTTONS', 'floating-share-buttons', '', 1, CURRENT_TIMESTAMP),
(4, 'TRAFFIC 2 MONEY', 'traffic2money', '', 1, CURRENT_TIMESTAMP),
(5, '4 WEBMASTERS', '4webmasters.org', '', 1, CURRENT_TIMESTAMP),
(6, 'FREE FLOATING BUTTONS', 'free-floating-buttons', '', 1, CURRENT_TIMESTAMP),
(7, 'SEXYALI', 'sexyali', '', 1, CURRENT_TIMESTAMP),
(8, 'GET FREE SOCIAL TRAFFIC', 'get-free-social-traffic', '', 1, CURRENT_TIMESTAMP),
(9, 'SUCCESS SEO', 'success-seo', '', 1, CURRENT_TIMESTAMP),
(10, 'TRAFFIC MONETIZER', 'trafficmonetizer', '', 1, CURRENT_TIMESTAMP),
(11, 'FREE SOCIAL BUTTONS', 'free-social-buttons', '', 1, CURRENT_TIMESTAMP),
(12, 'EVENT TRACKING', 'event-tracking', '', 1, CURRENT_TIMESTAMP),
(13, 'EROT', 'erot.co', '', 1, CURRENT_TIMESTAMP),
(14, 'GET FREE TRAFFIC NOW', 'Get-Free-Traffic-Now', '', 1, CURRENT_TIMESTAMP),
(15, 'BUTTONS FOR WEBSITE', 'buttons-for-website', '', 1, CURRENT_TIMESTAMP),
(16, 'CHINESE AMEZON', 'chinese-amezon', '', 1, CURRENT_TIMESTAMP),
(17, 'E BUYEASY', 'e-buyeasy', '', 1, CURRENT_TIMESTAMP),
(18, 'VIDEOS FOR YOUR BUSINESS', 'videos-for-your-business', '', 1, CURRENT_TIMESTAMP),
(19, 'HONGFANJI', 'hongfanji', '', 1, CURRENT_TIMESTAMP),
(20, 'DARODAR', 'darodar', '', 1, CURRENT_TIMESTAMP),
(21, 'HOW TO EARN QUICK MONEY', 'how-to-earn-quick-money', '', 1, CURRENT_TIMESTAMP),
(22, 'GW EC', 'gw-ec', '', 1, CURRENT_TIMESTAMP),
(23, 'SEMALT', 'semalt', '', 1, CURRENT_TIMESTAMP);

--
-- GESTION DES DONNEES DU TEMPLATE
--
INSERT INTO `plugins` (`id`, `code`, `name`, `description`, `author`, `online`, `installed`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(NULL, 'tpl_koezion', 'Template koéZion', 'Gestion de la configuration du template koéZion', 'koéZionCMS', 1, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 1);

INSERT INTO `templates` 
	(`id`, `name`, `layout`, `version`, `code`, `color`, `picture`, `online`, `created`) 
	VALUES
	(NULL, 'Template koéZion', 'koezion', 'Basic', '', '', '', 1, CURRENT_TIMESTAMP);

INSERT INTO `modules` 
SET
    `name` = 'Template koéZion', 
    `controller_name` = 'tpl_koezion_configs', 
    `action_name` = 'manage', 
    `order_by` = 1, 
    `online` = 1, 
    `created` = CURRENT_TIMESTAMP, 
    `modules_type_id` = (SELECT `id` FROM `modules_types` WHERE `name` = 'PLUGINS' LIMIT 1), 
    `plugin_id` = (SELECT `id` FROM `plugins` WHERE `code` = 'tpl_koezion' LIMIT 1);		