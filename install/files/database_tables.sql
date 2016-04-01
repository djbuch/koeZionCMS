--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `redirect_to` longtext COLLATE utf8_unicode_ci NOT NULL,
  `activate_link` int(11) NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `illustration_1` longtext COLLATE utf8_unicode_ci NOT NULL,
  `illustration_2` longtext COLLATE utf8_unicode_ci NOT NULL,
  `subtitle_1` longtext COLLATE utf8_unicode_ci NOT NULL,
  `subtitle_2` longtext COLLATE utf8_unicode_ci NOT NULL,
  `title_colonne_droite` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_children` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_brothers` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_posts_list` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `secondary_type` int(11) NOT NULL,
  `display_form` int(11) NOT NULL,
  `display_children` int(11) NOT NULL,
  `display_brothers` int(11) NOT NULL,
  `is_secure` int(255) NOT NULL,
  `txt_secure` longtext COLLATE utf8_unicode_ci NOT NULL,
  `message_mail` longtext COLLATE utf8_unicode_ci NOT NULL,
  `tpl_layout` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tpl_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `css_file` longtext COLLATE utf8_unicode_ci NOT NULL,
  `js_file` longtext COLLATE utf8_unicode_ci NOT NULL,
  `publication_start_date` date NOT NULL,
  `publication_end_date` date NOT NULL,
  `online` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `redirect_category_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `online` (`online`),
  ADD KEY `type` (`type`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `type_2` (`type`,`online`);

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `categories_focus_websites`;
CREATE TABLE IF NOT EXISTS `categories_focus_websites` (
  `category_id` int(11) NOT NULL,
  `focus_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`,`focus_id`,`website_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `categories_posts_posts_types`;
CREATE TABLE IF NOT EXISTS `categories_posts_posts_types` (
  `category_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `posts_type_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`,`post_id`,`posts_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `categories_posts_websites`;
CREATE TABLE IF NOT EXISTS `categories_posts_websites` (
  `category_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  `display_home_page` int(11) NOT NULL,
  PRIMARY KEY (`category_id`,`post_id`,`website_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `categories_right_buttons`;
CREATE TABLE IF NOT EXISTS `categories_right_buttons` (
  `category_id` int(11) NOT NULL,
  `right_button_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `order_by` int(11) NOT NULL,
  PRIMARY KEY (`category_id`,`right_button_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `categories_sliders_websites`;
CREATE TABLE IF NOT EXISTS `categories_sliders_websites` (
  `category_id` int(11) NOT NULL,
  `slider_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`,`slider_id`,`website_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `configs`;
CREATE TABLE IF NOT EXISTS `configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 

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
  `details_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details_img` longtext COLLATE utf8_unicode_ci NOT NULL,
  `details_content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `order_by` int(11) NOT NULL,
  `publication_start_date` date NOT NULL,
  `publication_end_date` date NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `controller_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `others_actions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `prohibited_actions` longtext COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `no_display_in_menu` int(11) NOT NULL,
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
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_by` int(11) NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `plugin_id` int(11) NOT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Structure de la table `posts`
--
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `short_content_illustration` longtext COLLATE utf8_unicode_ci NOT NULL,
  `short_content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `content_illustration` longtext COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `redirect_to` longtext COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prefix` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shooting_time` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `illustration` longtext COLLATE utf8_unicode_ci NOT NULL,
  `img_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `img_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_colonne_droite` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_children` int(11) NOT NULL,
  `display_brothers` int(11) NOT NULL,
  `display_link` int(11) NOT NULL,
  `display_form` int(11) NOT NULL,
  `display_posts_types` int(11) NOT NULL,
  `is_secure` int(11) NOT NULL,
  `txt_secure` longtext COLLATE utf8_unicode_ci NOT NULL,
  `order_by` int(11) NOT NULL,
  `message_mail` longtext COLLATE utf8_unicode_ci NOT NULL,
  `publication_start_date` date NOT NULL,
  `publication_end_date` date NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `posts_comments`;
CREATE TABLE IF NOT EXISTS `posts_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cpostal` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `message_backoffice` longtext COLLATE utf8_unicode_ci NOT NULL,
  `send_newsletter` int(11) NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `post_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `posts_right_buttons`;
CREATE TABLE IF NOT EXISTS `posts_right_buttons` (
  `post_id` int(11) NOT NULL,
  `right_button_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `order_by` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`right_button_id`)
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

--
-- Structure de la table `right_buttons`
--

DROP TABLE IF EXISTS `right_buttons`;
CREATE TABLE IF NOT EXISTS `right_buttons` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `display_home_page` int(11) NOT NULL,
  `display_all_pages` int(11) NOT NULL,
  `display_all_pages_top` int(11) NOT NULL,
  `order_by` int(11) NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `website_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour la table `right_buttons`
--
ALTER TABLE `right_buttons`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour la table `right_buttons`
--
ALTER TABLE `right_buttons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  `publication_start_date` date NOT NULL,
  `publication_end_date` date NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `templates`;
CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `layout` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` longtext COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `unwanted_crawlers`;
CREATE TABLE IF NOT EXISTS `unwanted_crawlers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
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
  `online` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `users_group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `is_deletable` int(11) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
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

DROP TABLE IF EXISTS `users_websites`;
CREATE TABLE IF NOT EXISTS `users_websites` (
  `user_id` int(11) NOT NULL,
  `website_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`website_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Structure de la table `websites`
--

DROP TABLE IF EXISTS `websites`;
CREATE TABLE `websites` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url_alias` longtext COLLATE utf8_unicode_ci NOT NULL,
  `tpl_logo` longtext COLLATE utf8_unicode_ci NOT NULL,
  `tpl_header` longtext COLLATE utf8_unicode_ci NOT NULL,
  `tpl_layout` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tpl_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt_slogan` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_posts` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_newsletter` longtext COLLATE utf8_unicode_ci NOT NULL,
  `subject_mail_contact` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt_mail_contact` longtext COLLATE utf8_unicode_ci NOT NULL,
  `subject_mail_comments` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt_mail_comments` longtext COLLATE utf8_unicode_ci NOT NULL,
  `subject_mail_newsletter` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt_mail_newsletter` longtext COLLATE utf8_unicode_ci NOT NULL,
  `subject_mail_inscription_user` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt_mail_inscription_user` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_confirm_inscription_user` longtext COLLATE utf8_unicode_ci NOT NULL,
  `subject_mail_validated_user` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt_mail_validated_user` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_confirm_validated_user` longtext COLLATE utf8_unicode_ci NOT NULL,
  `subject_mail_refused_user` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt_mail_refused_user` longtext COLLATE utf8_unicode_ci NOT NULL,
  `subject_mail_lost_password_user` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txt_mail_lost_password_user` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_confirm_lost_password_user` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_after_form_contact` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_after_form_comments` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_after_newsletter` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_after_form_user` longtext COLLATE utf8_unicode_ci NOT NULL,
  `txt_secure` longtext COLLATE utf8_unicode_ci NOT NULL,
  `seo_page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seo_page_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seo_page_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `secure_activ` int(11) NOT NULL,
  `log_users_activ` int(11) NOT NULL,
  `moderate_new_users` int(11) NOT NULL,
  `ga_code` longtext COLLATE utf8_unicode_ci NOT NULL,
  `ga_login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ga_password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ga_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_map_activ` int(11) NOT NULL,
  `contact_map_page` int(11) NOT NULL,
  `contact_map_address` longtext COLLATE utf8_unicode_ci NOT NULL,
  `contact_map_lat` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_map_lng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `search_engine_position` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `header_txt` longtext COLLATE utf8_unicode_ci NOT NULL,
  `footer_gauche` longtext COLLATE utf8_unicode_ci NOT NULL,
  `footer_centre` longtext COLLATE utf8_unicode_ci NOT NULL,
  `footer_droite` longtext COLLATE utf8_unicode_ci NOT NULL,
  `footer_bottom` longtext COLLATE utf8_unicode_ci NOT NULL,
  `footer_social` longtext COLLATE utf8_unicode_ci NOT NULL,
  `footer_addthis` longtext COLLATE utf8_unicode_ci NOT NULL,
  `css_hack_file` longtext COLLATE utf8_unicode_ci NOT NULL,
  `js_hack_file` longtext COLLATE utf8_unicode_ci NOT NULL,
  `connect_background` longtext COLLATE utf8_unicode_ci NOT NULL,
  `connect_logo` longtext COLLATE utf8_unicode_ci NOT NULL,
  `connect_text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `connect_css_file` longtext COLLATE utf8_unicode_ci NOT NULL,
  `connect_js_file` longtext COLLATE utf8_unicode_ci NOT NULL,
  `slider_type` int(11) NOT NULL DEFAULT '1',
  `favicon` text COLLATE utf8_unicode_ci NOT NULL,
  `hook_filename` longtext COLLATE utf8_unicode_ci NOT NULL,
  `online` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `template_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour la table `websites`
--
ALTER TABLE `websites`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour la table `websites`
--
ALTER TABLE `websites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;