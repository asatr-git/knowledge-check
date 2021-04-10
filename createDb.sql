/*
SQLyog Community Edition- MySQL GUI v6.07
Host - 5.5.5-10.1.35-MariaDB : Database - quickstart
*********************************************************************
Server version : 5.5.5-10.1.35-MariaDB
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `actions` */

DROP TABLE IF EXISTS `actions`;

CREATE TABLE `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `backup_ctrlz` */

DROP TABLE IF EXISTS `backup_ctrlz`;

CREATE TABLE `backup_ctrlz` (
  `head_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `backup_ctrlz_viewed_messages` */

DROP TABLE IF EXISTS `backup_ctrlz_viewed_messages`;

CREATE TABLE `backup_ctrlz_viewed_messages` (
  `schema_id` int(11) DEFAULT NULL,
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `head_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `graphs` */

DROP TABLE IF EXISTS `graphs`;

CREATE TABLE `graphs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shema_id` int(11) DEFAULT NULL,
  `element_id` int(11) DEFAULT NULL,
  `position_x` int(11) DEFAULT NULL,
  `position_y` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1871 DEFAULT CHARSET=latin1;

/*Table structure for table `logs` */

DROP TABLE IF EXISTS `logs`;

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action_id` int(11) DEFAULT NULL,
  `descr` varchar(500) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Table structure for table `message` */

DROP TABLE IF EXISTS `message`;

CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `root` int(11) DEFAULT NULL,
  `discussion_id` int(11) DEFAULT NULL,
  `message_type` int(11) NOT NULL DEFAULT '7',
  `message_text` varchar(5000) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `calculated10` datetime DEFAULT NULL,
  `calculated50` datetime DEFAULT NULL,
  `context` varchar(250) DEFAULT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `is_comment_changed` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1085 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `message_type` */

DROP TABLE IF EXISTS `message_type`;

CREATE TABLE `message_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `font_color` varchar(50) DEFAULT NULL,
  `font_size` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `relation` */

DROP TABLE IF EXISTS `relation`;

CREATE TABLE `relation` (
  `message1_id` int(11) NOT NULL DEFAULT '11',
  `message2_id` int(11) NOT NULL DEFAULT '22',
  `relation_type` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

/*Table structure for table `relation_type` */

DROP TABLE IF EXISTS `relation_type`;

CREATE TABLE `relation_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `show_title` int(11) DEFAULT NULL,
  `show_arrow` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `rule_violations` */

DROP TABLE IF EXISTS `rule_violations`;

CREATE TABLE `rule_violations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schema_id` int(11) DEFAULT NULL,
  `element_id` int(11) DEFAULT NULL,
  `rule_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `system_current_mode` */

DROP TABLE IF EXISTS `system_current_mode`;

CREATE TABLE `system_current_mode` (
  `schema_id` int(11) DEFAULT NULL,
  `mode_id` int(11) DEFAULT NULL,
  `changed_by_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `system_modes` */

DROP TABLE IF EXISTS `system_modes`;

CREATE TABLE `system_modes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `tasks` */

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `user_mode` */

DROP TABLE IF EXISTS `user_mode`;

CREATE TABLE `user_mode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `mode_id` int(11) DEFAULT NULL,
  `schema_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=latin1;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `viewed_comments` */

DROP TABLE IF EXISTS `viewed_comments`;

CREATE TABLE `viewed_comments` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `read_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `viewed_messages` */

DROP TABLE IF EXISTS `viewed_messages`;

CREATE TABLE `viewed_messages` (
  `schema_id` int(11) DEFAULT NULL,
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `read_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
