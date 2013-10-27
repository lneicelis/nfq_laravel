-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Darbinė stotis: localhost
-- Atlikimo laikas: 2013 m. Spa 27 d. 12:17
-- Serverio versija: 5.5.24-log
-- PHP versija: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Duomenų bazė: `laravel`
--

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `short_description` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cover_photo` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Sukurta duomenų kopija lentelei `albums`
--

INSERT INTO `albums` (`id`, `user_id`, `title`, `short_description`, `description`, `location`, `cover_photo`, `created_at`, `updated_at`) VALUES
(1, 2, 'Cars', NULL, 'Super cars', NULL, 32, '2013-10-25 12:27:06', '2013-10-26 17:51:19'),
(2, 2, 'Phones', NULL, 'Some description', NULL, 14, '2013-10-25 12:27:13', '2013-10-26 16:58:07'),
(3, 2, 'People', NULL, NULL, NULL, 15, '2013-10-26 10:22:42', '2013-10-26 10:28:57'),
(4, 2, 'Misc', NULL, 'Whatever is by hand', NULL, 31, '2013-10-26 11:52:47', '2013-10-26 17:43:32');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Sukurta duomenų kopija lentelei `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2012_12_06_225921_migration_cartalyst_sentry_install_users', 1),
('2012_12_06_225929_migration_cartalyst_sentry_install_groups', 1),
('2012_12_06_225945_migration_cartalyst_sentry_install_users_groups_pivot', 1),
('2012_12_06_225988_migration_cartalyst_sentry_install_throttle', 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(11) unsigned DEFAULT NULL,
  `file_name` varchar(22) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shoot_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33 ;

--
-- Sukurta duomenų kopija lentelei `photos`
--

INSERT INTO `photos` (`id`, `album_id`, `file_name`, `description`, `shoot_date`, `created_at`, `updated_at`) VALUES
(0, 0, 'default.jpg', NULL, NULL, '2013-10-25 16:57:20', '2013-10-25 16:57:20'),
(6, 1, 'BKRrcEzRnGauyEBN.png', NULL, NULL, '2013-10-25 16:57:32', '2013-10-25 16:57:32'),
(7, 1, 'cs3lGUYCBJwfvRQ5.jpg', NULL, NULL, '2013-10-25 16:57:43', '2013-10-25 16:57:43'),
(14, 2, 'Rt1mA1EKMFXbMucq.jpeg', 'ipad-mini-creative-apple.jpeg', NULL, '2013-10-26 09:58:19', '2013-10-26 09:58:19'),
(15, 3, 'NcsKaejQxXOwX65D.jpg', '527428_189368977866309_222025465_n.jpg', NULL, '2013-10-26 10:24:17', '2013-10-26 10:24:17'),
(18, 2, '7VZJORI2WgIDvsjO.jpg', '8437_10151910567032324_1046987090_n.jpg', NULL, '2013-10-26 16:42:43', '2013-10-26 16:42:43'),
(19, 2, 'nyzhyHPVmjlUR2rq.jpg', 'na.jpg', NULL, '2013-10-26 16:43:22', '2013-10-26 16:43:22'),
(20, 2, 'fhX90yx4vhK8jJpS.png', 'regular_expressions_cheat_sheet.png', NULL, '2013-10-26 16:43:29', '2013-10-26 16:43:29'),
(21, 2, 'fBDR53wSqWvQsTvv.jpg', 'lion_5-wallpaper-1920x1080.jpg', NULL, '2013-10-26 16:45:30', '2013-10-26 16:45:30'),
(22, 2, 'lSxaQsTj35Gzplq8.png', 'photo_gallery.png', NULL, '2013-10-26 16:47:13', '2013-10-26 16:47:13'),
(23, 2, 'QcefssHfuKV8T5U3.jpg', 'git.jpg', NULL, '2013-10-26 16:51:40', '2013-10-26 16:51:40'),
(31, 4, 'aBqez7PuQWncGglF.jpg', 'vireslio.jpg', NULL, '2013-10-26 17:17:47', '2013-10-26 17:17:47'),
(32, 1, '7CyQyBVhdur8qF9P.jpg', 'Ford-Mustang-Shelby-GT500.jpg', NULL, '2013-10-26 17:50:03', '2013-10-26 17:50:03');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `photo_tags`
--

CREATE TABLE IF NOT EXISTS `photo_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `coordinates` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `throttle`
--

CREATE TABLE IF NOT EXISTS `throttle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `last_attempt_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `throttle_user_id_unique` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Sukurta duomenų kopija lentelei `throttle`
--

INSERT INTO `throttle` (`id`, `user_id`, `ip_address`, `attempts`, `suspended`, `banned`, `last_attempt_at`, `suspended_at`, `banned_at`) VALUES
(1, 2, '::1', 0, 0, 0, NULL, NULL, NULL),
(2, 3, '::1', 0, 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `persist_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_password_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_activation_code_index` (`activation_code`),
  KEY `users_reset_password_code_index` (`reset_password_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Sukurta duomenų kopija lentelei `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `permissions`, `activated`, `activation_code`, `activated_at`, `last_login`, `persist_code`, `reset_password_code`, `first_name`, `last_name`, `created_at`, `updated_at`) VALUES
(1, 'adsf', '$2y$10$G8pQF274/jUKGXjDY367wOnKkHG3DzSlzZQM6VTjnxNPhH1dqEH2G', NULL, 0, '7rbfHtszjqLFz2hvQvNUj2W7kd8boZBTPI9K9CYixb', NULL, NULL, NULL, NULL, NULL, NULL, '2013-10-19 07:10:17', '2013-10-19 07:10:18'),
(2, 'l.neicelis@gmail.com', '$2y$10$jIsb1LI4rHAT0gtZm2Er0ei.7Ue1uMhERXpb8D0UIBTukLz7HTEje', NULL, 1, NULL, '2013-10-19 07:37:54', '2013-10-26 15:33:55', '$2y$10$Dn3BStJJjWF.pBv2.VYCzOlXRiuHS8GR88.dT3dmVYJpRSIbkyQV.', NULL, NULL, NULL, '2013-10-19 07:37:54', '2013-10-26 15:33:55'),
(3, 'test@test.com', '$2y$10$CFg0esGTAxZq0nBjPxKxYOmcOGlhk3Pra0XHa8SoKg.qitpvdjMSG', NULL, 1, NULL, '2013-10-26 17:58:18', '2013-10-26 17:59:20', '$2y$10$XH46CrJYZsjLosmNSOHh4O4.waZ9wNhAUSdSirKvpxwZC7pLBG1Fq', NULL, NULL, NULL, '2013-10-26 17:58:17', '2013-10-26 17:59:20');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
