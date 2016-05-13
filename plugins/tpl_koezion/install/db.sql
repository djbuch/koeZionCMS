DROP TABLE IF EXISTS `plugin_tpl_koezion_configs`;
CREATE TABLE IF NOT EXISTS `plugin_tpl_koezion_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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