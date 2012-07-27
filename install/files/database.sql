--
-- Structure de la table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_children` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_brothers` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_posts_list` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL COMMENT '1 --> Catégorie, 2 --> Evt, 3 --> Racine',
  `display_contact_form` int(11) NOT NULL COMMENT 'Indique si on affiche un formulaire de contact',
  `display_children` int(11) NOT NULL,
  `display_brothers` int(11) NOT NULL,
  `is_secure` int(255) NOT NULL,
  `txt_secure` text COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `redirect_category_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `online` (`online`),
  KEY `type` (`type`),
  KEY `parent_id` (`parent_id`),
  KEY `type_2` (`type`,`online`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Structure de la table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `focus`
--

CREATE TABLE IF NOT EXISTS `focus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `order_by` int(11) NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Structure de la table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_content` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prefix` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `img_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `img_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_link` int(11) NOT NULL,
  `display_comments` int(11) NOT NULL,
  `display_home_page` int(11) NOT NULL,
  `order_by` int(11) NOT NULL,
  `message_mail` text COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'Identifiant de l''utilisateur ayant créé le post',
  `modified_by` int(11) NOT NULL COMMENT 'Identifiant de l''utilisateur ayant modifié le post',
  `category_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Structure de la table `posts_comments`
--

CREATE TABLE IF NOT EXISTS `posts_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `post_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `posts_posts_types`
--

CREATE TABLE IF NOT EXISTS `posts_posts_types` (
  `post_id` int(11) NOT NULL,
  `posts_type_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL COMMENT 'Sert à améliorer les traitements lors des recherches',
  `website_id` int(11) NOT NULL COMMENT 'Utilisé lors de la suppression d''un site',
  PRIMARY KEY (`post_id`,`posts_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `posts_types`
--

CREATE TABLE IF NOT EXISTS `posts_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `column_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_by` int(11) NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sliders`
--

CREATE TABLE IF NOT EXISTS `sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `order_by` int(11) NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Structure de la table `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `layout` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;

--
-- Contenu de la table `templates`
--

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

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `second_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `login` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `online` int(11) NOT NULL,
  `users_group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `name`, `second_name`, `login`, `password`, `email`, `role`, `online`, `users_group_id`) VALUES
(1, 'Administrateur Général', '', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '', 'admin', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users_groups_websites`
--

CREATE TABLE IF NOT EXISTS `users_groups_websites` (
  `users_group_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`users_group_id`,`website_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users_logs`
--

CREATE TABLE IF NOT EXISTS `users_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `type` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `websites`
--

CREATE TABLE IF NOT EXISTS `websites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tpl_logo` text COLLATE utf8_unicode_ci NOT NULL,
  `tpl_header` text COLLATE utf8_unicode_ci NOT NULL,
  `tpl_layout` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tpl_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt_slogan` text COLLATE utf8_unicode_ci NOT NULL,
  `txt_posts` text COLLATE utf8_unicode_ci NOT NULL,
  `txt_newsletter` text COLLATE utf8_unicode_ci NOT NULL,
  `seo_page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seo_page_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seo_page_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `secure_activ` int(11) NOT NULL,
  `log_users_activ` int(11) NOT NULL,
  `ga_code` text COLLATE utf8_unicode_ci NOT NULL,
  `search_engine_position` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `footer_gauche` text COLLATE utf8_unicode_ci NOT NULL,
  `footer_social` text COLLATE utf8_unicode_ci NOT NULL,
  `footer_droite` text COLLATE utf8_unicode_ci NOT NULL,
  `footer_bottom` text COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
