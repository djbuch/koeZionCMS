DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_colonne_droite` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_posts_list` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `display_form` int(11) NOT NULL,
  `display_children` int(11) NOT NULL,
  `display_brothers` int(11) NOT NULL,
  `is_secure` int(255) NOT NULL,
  `txt_secure` longtext COLLATE utf8_unicode_ci NOT NULL,
  `message_mail` longtext COLLATE utf8_unicode_ci NOT NULL,
  `tpl_layout` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tpl_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `redirect_category_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `online` (`online`),
  KEY `type` (`type`),
  KEY `parent_id` (`parent_id`),
  KEY `type_2` (`type`,`online`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `categories_right_buttons`;
CREATE TABLE IF NOT EXISTS `categories_right_buttons` (
  `category_id` int(11) NOT NULL,
  `right_button_id` int(11) NOT NULL,
  `order_by` int(11) NOT NULL,
  PRIMARY KEY (`category_id`,`right_button_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cpostal` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `message_backoffice` longtext COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `focus`;
CREATE TABLE IF NOT EXISTS `focus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `order_by` int(11) NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `controller_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_by` int(11) NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modules_type_id` int(11) NOT NULL,
  `plugin_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `modules_types`;
CREATE TABLE IF NOT EXISTS `modules_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_by` int(11) NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `plugins`;
CREATE TABLE IF NOT EXISTS `plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `installed` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prefix` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `img_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `img_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_link` int(11) NOT NULL,
  `display_form` int(11) NOT NULL,
  `display_home_page` int(11) NOT NULL,
  `order_by` int(11) NOT NULL,
  `message_mail` longtext COLLATE utf8_unicode_ci NOT NULL,
  `publication_date` date NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `posts_comments`;
CREATE TABLE IF NOT EXISTS `posts_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cpostal` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `message_backoffice` longtext COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `post_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `posts_posts_types`;
CREATE TABLE IF NOT EXISTS `posts_posts_types` (
  `post_id` int(11) NOT NULL,
  `posts_type_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`posts_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `posts_types`;
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

DROP TABLE IF EXISTS `right_buttons`;
CREATE TABLE IF NOT EXISTS `right_buttons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `searches`;
CREATE TABLE IF NOT EXISTS `searches` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `model` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `datas` longtext COLLATE utf8_unicode_ci,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `model_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `data` (`datas`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `sliders`;
CREATE TABLE IF NOT EXISTS `sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` longtext COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `order_by` int(11) NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `templates`;
CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `layout` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `users`;
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

DROP TABLE IF EXISTS `users_groups`;
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

DROP TABLE IF EXISTS `users_groups_websites`;
CREATE TABLE IF NOT EXISTS `users_groups_websites` (
  `users_group_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`users_group_id`,`website_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `users_logs`;
CREATE TABLE IF NOT EXISTS `users_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `type` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `websites`;
CREATE TABLE IF NOT EXISTS `websites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tpl_logo` longtext COLLATE utf8_unicode_ci NOT NULL,
  `tpl_header` longtext COLLATE utf8_unicode_ci NOT NULL,
  `tpl_layout` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tpl_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt_slogan` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_posts` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_newsletter` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_mail_contact` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_mail_comments` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_mail_newsletter` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_secure` longtext COLLATE utf8_unicode_ci NOT NULL,
  `seo_page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seo_page_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seo_page_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `secure_activ` int(11) NOT NULL,
  `log_users_activ` int(11) NOT NULL,
  `ga_code` longtext COLLATE utf8_unicode_ci NOT NULL,
  `search_engine_position` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `footer_gauche` longtext COLLATE utf8_unicode_ci NOT NULL,
  `footer_social` longtext COLLATE utf8_unicode_ci NOT NULL,
  `footer_droite` longtext COLLATE utf8_unicode_ci NOT NULL,
  `footer_bottom` longtext COLLATE utf8_unicode_ci NOT NULL,
  `footer_addthis` longtext COLLATE utf8_unicode_ci NOT NULL,
  `css_hack` longtext COLLATE utf8_unicode_ci NOT NULL,
  `js_hack` longtext COLLATE utf8_unicode_ci NOT NULL,
  `slider_type` int(11) NOT NULL DEFAULT '1',
  `online` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;