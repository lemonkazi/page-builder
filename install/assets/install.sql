-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 22, 2017 at 04:40 PM
-- Server version: 5.7.17-0ubuntu0.16.04.1
-- PHP Version: 5.6.30-1+deb.sury.org~xenial+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `co_bloxby`
--

-- --------------------------------------------------------

--
-- Table structure for table `apps_settings`
--

CREATE TABLE `apps_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NULL,
  `value` text NULL,
  `default_value` text NULL,
  `description` text NULL,
  `required` int(1) NULL DEFAULT '0',
  `created_at` datetime NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `apps_settings`
--

INSERT INTO `apps_settings` (`id`, `name`, `value`, `default_value`, `description`, `required`, `created_at`, `modified_at`) VALUES
(1, 'elements_dir', 'elements', 'elements', '<h4>Elements Directory</h4>\r\n<p>\r\nThe directory where all your element HTML files are stored. This value is relative to the directory in which you installed the application. Do not add a trailing "/"\r\n</p>', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'images_dir', 'images', 'images', '<h4>Image Directory</h4>\r\n<p>\r\nThis is the main directory for the images used by your elements. The images located in this directory belong to the administrator and can not be deleted by regular users. This directory needs to have <b>full read and write permissions!</b>\r\n</p>', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'images_uploadDir', 'images/uploads', 'images/uploads', '<h4>Image Upload Directory</h4>\r\n<p>\r\nThis directory is used to store images uploaded by regular users. Each user will have his/her own directory within this directory. This directory needs to have <b>full read and write permissions!</b>.\r\n</p>', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'upload_allowed_types', 'image/gif, image/jpg, image/png', 'image/gif, image/jpg, image/png', '<h4>Allowed Image Types</h4>\r\n<p>\r\nThe types of images users are allowed to upload, separated by "|".\r\n</p>', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'upload_max_size', '1000', '1000', '<h4>Maximum Upload Filesize</h4>\r\n<p>\r\nThe maximum allowed filesize for images uploaded by users. This number is represents the number of kilobytes. Please note that this number of overruled by possible server settings.\r\n</p>', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'images_allowedExtensions', 'jpg|png|gif|svg', 'jpg|png|gif|svg', '<h4>Allowed Extensions</h4>\r\n<p>\r\nThese allowed extensions are used when displayed the image library to the user, only these file types will be visible. \r\n</p>', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'export_pathToAssets', 'elements/bundles|images', 'elements/bundles|images', '<h4>Assets Included in the export</h4>\r\n<p>\r\nThe collection of asset paths included in the export function. These paths are relative to the folder in which the application was installed and should have NO trailing "/". The paths are separated by "|".\r\n</p>', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'export_fileName', 'website.zip', 'website.zip', '<h4>The Export File Name</h4>\r\n<p>\r\nThe name of the ZIP archive file downloaded when exporting a site. We recommend using the ".zip" file extension (others might work, but have not been tested).\r\n</p>', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'language', 'english', 'english', '<h4>Application Language</h4>\r\n<p>\r\n"english" by default. If you\'re changing this to anything else, please be sure to have all required language files translated and located in the correct folder inside "/application/languages/yourlanguage".\r\n</p>', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'google_api', '', '', '<h4>Google Maps API</h4>\r\n<p>\r\nTo be able to use Google Maps within blocks, you will need to provide a properly functioning Google Maps API with access to the Geocoding library.".\r\n</p>', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'custom_fonts', '', '', '<h4>Google Fonts</h4>\r\n<p>\r\nTo allow usage of Google Fonts, you will need to provide a JSON string defining which fonts to load. This string should use the following format:\r\n</p>\r\n<pre>[\r\n   {\r\n       \"nice_name\": \"Roboto\",\r\n      \"css_name\": \"\'Roboto\', sans-serif\",\r\n       \"api_entry\": \"Roboto\"\r\n   },\r\n  {\r\n       \"nice_name\": \"Oswald\",\r\n      \"css_name\": \"\'Oswald\', sans-serif\",\r\n       \"api_entry\": \"Oswald\"\r\n   }\r\n]</pre>\r\n<p>\r\nFor more information, please refer to our knowledge base.\r\n</p>', 0, '0000-00-00 00:00:00', NULL),
(14, 'show_locked_blocks', 'yes', 'yes', '<h4>Show disabled blocks as locked</h4>\r\n<p>\r\nIf a user\'s subscription package has some block limitation, these blocks and block categories will not be hidden for users but will be shown with the blocked icon.\r\n</p>', 0, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blocks_categories`
--

CREATE TABLE `blocks_categories` (
  `blocks_categories_id` int(11) NOT NULL,
  `category_name` varchar(255) NULL,
  `list_order` int(4) NULL DEFAULT '999'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blocks_categories`
--

INSERT INTO `blocks_categories` (`blocks_categories_id`, `category_name`, `list_order`) VALUES
(1, 'All Blocks', 999),
(2, 'Empty block', 999),
(3, 'Call to action', 999),
(4, 'Contact', 999),
(5, 'Content', 999),
(6, 'Countdown timers', 999),
(7, 'Dividers', 999),
(8, 'Embed', 999),
(9, 'Headers', 999),
(10, 'Features', 999),
(11, 'Footers', 999),
(12, 'Gallery', 999),
(13, 'Maps', 999),
(14, 'Navigation bars', 999),
(15, 'Popups', 999),
(16, 'Pricing', 999),
(17, 'Processes', 999),
(18, 'Signup', 999),
(19, 'Slideshows', 999),
(20, 'Subscribe', 999),
(21, 'Team', 999),
(22, 'Testimonals', 999),
(23, 'Titles', 999);

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE `blocks` (
  `blocks_id` int(11) NOT NULL,
  `blocks_category` int(11) NULL,
  `blocks_url` varchar(255) NULL,
  `blocks_height` varchar(10) NULL,
  `blocks_thumb` varchar(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Dumping data for table `blocks`
--

INSERT INTO `blocks` (`blocks_id`, `blocks_category`, `blocks_url`, `blocks_height`, `blocks_thumb`) VALUES
(1, 2, 'elements/Yummy/empty1.html', '567', 'images/uploads/yummy_empty.jpg'),
(2, 3, 'elements/Yummy/cta2.html', '567', 'images/uploads/y_cta2.jpg'),
(3, 3, 'elements/Yummy/cta4.html', '567', 'images/uploads/y_cta4.jpg'),
(4, 3, 'elements/Yummy/cta5.html', '567', 'images/uploads/y_cta5.jpg'),
(5, 3, 'elements/Yummy/cta8.html', '567', 'images/uploads/y_cta8.jpg'),
(6, 3, 'elements/Yummy/cta9.html', '567', 'images/uploads/y_cta9.jpg'),
(7, 4, 'elements/Yummy/contact1.html', '567', 'images/uploads/yummy_contact1.jpg'),
(8, 4, 'elements/Yummy/contact4.html', '567', 'images/uploads/yummy_contact4.jpg'),
(9, 4, 'elements/Yummy/contact6.html', '567', 'images/uploads/yummy_contact6.jpg'),
(10, 4, 'elements/Yummy/contact7.html', '567', 'images/uploads/yummy_contact7.jpg'),
(11, 4, 'elements/Yummy/contact8.html', '567', 'images/uploads/yummy_contact8.jpg'),
(12, 5, 'elements/Yummy/content2.html', '567', 'images/uploads/yummy_content2.jpg'),
(13, 5, 'elements/Yummy/content3.html', '567', 'images/uploads/yummy_content3.jpg'),
(14, 5, 'elements/Yummy/content7.html', '567', 'images/uploads/yummy_content7.jpg'),
(15, 5, 'elements/Yummy/content8.html', '567', 'images/uploads/yummy_content8.jpg'),
(16, 5, 'elements/Yummy/content9.html', '567', 'images/uploads/yummy_content9.jpg'),
(17, 5, 'elements/Yummy/content11.html', '567', 'images/uploads/yummy_content11.jpg'),
(18, 6, 'elements/Yummy/countdown1.html', '567', 'images/uploads/countdown1.png'),
(19, 6, 'elements/Yummy/countdown2.html', '567', 'images/uploads/countdown2.png'),
(20, 6, 'elements/Yummy/countdown3.html', '567', 'images/uploads/countdown3.png'),
(21, 7, 'elements/Yummy/divider1.html', '567', 'images/uploads/y_divider1.jpg'),
(22, 7, 'elements/Yummy/divider2.html', '567', 'images/uploads/y_divider2.jpg'),
(23, 7, 'elements/Yummy/divider3.html', '567', 'images/uploads/y_divider3.jpg'),
(24, 8, 'elements/Yummy/embed.html', '567', 'images/uploads/embed-block.png'),
(25, 9, 'elements/Yummy/features2.html', '567', 'images/uploads/yummy_features2.jpg'),
(26, 9, 'elements/Yummy/features4.html', '567', 'images/uploads/yummy_features4.jpg'),
(27, 9, 'elements/Yummy/features6.html', '567', 'images/uploads/yummy_features6.jpg'),
(28, 9, 'elements/Yummy/features8.html', '567', 'images/uploads/yummy_features8.jpg'),
(29, 9, 'elements/Yummy/features9.html', '567', 'images/uploads/yummy_features9.jpg'),
(30, 9, 'elements/Yummy/features11.html', '567', 'images/uploads/yummy_features11.jpg'),
(31, 9, 'elements/Yummy/features13.html', '567', 'images/uploads/yummy_features13.jpg'),
(32, 9, 'elements/Yummy/features16.html', '567', 'images/uploads/yummy_features16.jpg'),
(33, 9, 'elements/Yummy/features18.html', '567', 'images/uploads/yummy_features18.jpg'),
(34, 9, 'elements/Yummy/features20.html', '567', 'images/uploads/yummy_features20.jpg'),
(35, 10, 'elements/Yummy/footer1.html', '567', 'images/uploads/yummy_footer1.jpg'),
(36, 10, 'elements/Yummy/footer4.html', '567', 'images/uploads/yummy_footer4.jpg'),
(37, 10, 'elements/Yummy/footer7.html', '567', 'images/uploads/yummy_footer7.jpg'),
(38, 11, 'elements/Yummy/gallery3.html', '567', 'images/uploads/y_gallery3.jpg'),
(39, 11, 'elements/Yummy/gallery8.html', '567', 'images/uploads/y_gallery8.jpg'),
(40, 11, 'elements/Yummy/gallery9.html', '567', 'images/uploads/y_gallery9.jpg'),
(41, 11, 'elements/Yummy/gallery13.html', '567', 'images/uploads/y_gallery13.jpg'),
(42, 11, 'elements/Yummy/gallery16.html', '567', 'images/uploads/y_gallery16.jpg'),
(43, 12, 'elements/Yummy/header2.html', '567', 'images/uploads/yummy_header2.jpg'),
(44, 12, 'elements/Yummy/header3.html', '567', 'images/uploads/yummy_header3.jpg'),
(45, 12, 'elements/Yummy/header4.html', '567', 'images/uploads/yummy_header4.jpg'),
(46, 12, 'elements/Yummy/header7.html', '567', 'images/uploads/yummy_header7.jpg'),
(47, 12, 'elements/Yummy/header10.html', '90vh', 'images/uploads/yummy_header10.jpg'),
(48, 12, 'elements/Yummy/header12.html', '90vh', 'images/uploads/yummy_header12.jpg'),
(49, 12, 'elements/Yummy/header13.html', '90vh', 'images/uploads/yummy_header13.jpg'),
(50, 12, 'elements/Yummy/header17.html', '90vh', 'images/uploads/yummy_header17.jpg'),
(51, 12, 'elements/Yummy/header20.html', '90vh', 'images/uploads/yummy_header20.jpg'),
(52, 12, 'elements/Yummy/header21.html', '500', 'images/uploads/yummy_header21.jpg'),
(53, 12, 'elements/Yummy/header22.html', '500', 'images/uploads/yummy_header22.jpg'),
(54, 12, 'elements/Yummy/header24.html', '90vh', 'images/uploads/yummy_header24.jpg'),
(55, 13, 'elements/Yummy/map1.html', '567', 'images/uploads/y_map1.jpg'),
(56, 13, 'elements/Yummy/map2.html', '567', 'images/uploads/y_map2.jpg'),
(57, 13, 'elements/Yummy/map6.html', '567', 'images/uploads/y_map6.jpg'),
(58, 14, 'elements/Yummy/navbar1.html', '567', 'images/uploads/y_navbar1.jpg'),
(59, 14, 'elements/Yummy/navbar2.html', '567', 'images/uploads/y_navbar2.jpg'),
(60, 14, 'elements/Yummy/navbar7.html', '567', 'images/uploads/y_navbar7.jpg'),
(61, 14, 'elements/Yummy/navbar9.html', '567', 'images/uploads/y_navbar9.jpg'),
(62, 15, 'elements/Yummy/popup1.html', '567', 'images/uploads/popup1.png'),
(63, 15, 'elements/Yummy/popup2.html', '567', 'images/uploads/popup2.png'),
(64, 15, 'elements/Yummy/popup3.html', '567', 'images/uploads/popup3.png'),
(65, 16, 'elements/Yummy/pricing1.html', '567', 'images/uploads/yummy_pricing1.jpg'),
(66, 16, 'elements/Yummy/pricing2.html', '567', 'images/uploads/yummy_pricing2.jpg'),
(67, 16, 'elements/Yummy/pricing4.html', '567', 'images/uploads/yummy_pricing4.jpg'),
(68, 16, 'elements/Yummy/pricing6.html', '567', 'images/uploads/yummy_pricing6.jpg'),
(69, 16, 'elements/Yummy/pricing9.html', '567', 'images/uploads/yummy_pricing9.jpg'),
(70, 16, 'elements/Yummy/pricing11.html', '567', 'images/uploads/yummy_pricing11.jpg'),
(71, 16, 'elements/Yummy/pricing13.html', '567', 'images/uploads/yummy_pricing13.jpg'),
(72, 16, 'elements/Yummy/pricing14.html', '567', 'images/uploads/yummy_pricing14.jpg'),
(73, 16, 'elements/Yummy/pricing16.html', '567', 'images/uploads/yummy_pricing16.jpg'),
(74, 16, 'elements/Yummy/pricing18.html', '567', 'images/uploads/yummy_pricing18.jpg'),
(75, 17, 'elements/Yummy/process1.html', '567', 'images/uploads/y_process1.jpg'),
(76, 17, 'elements/Yummy/process2.html', '567', 'images/uploads/y_process2.jpg'),
(77, 17, 'elements/Yummy/process4.html', '567', 'images/uploads/y_process4.jpg'),
(78, 17, 'elements/Yummy/process5.html', '567', 'images/uploads/y_process5.jpg'),
(79, 17, 'elements/Yummy/process7.html', '567', 'images/uploads/y_process7.jpg'),
(80, 17, 'elements/Yummy/process8.html', '567', 'images/uploads/y_process8.jpg'),
(81, 17, 'elements/Yummy/process9.html', '567', 'images/uploads/y_process9.jpg'),
(82, 17, 'elements/Yummy/process12.html', '567', 'images/uploads/y_process12.jpg'),
(83, 17, 'elements/Yummy/process14.html', '567', 'images/uploads/y_process14.jpg'),
(84, 17, 'elements/Yummy/process18.html', '567', 'images/uploads/y_process18.jpg'),
(85, 17, 'elements/Yummy/process19.html', '567', 'images/uploads/y_process19.jpg'),
(86, 17, 'elements/Yummy/process23.html', '567', 'images/uploads/y_process23.jpg'),
(87, 18, 'elements/Yummy/signup1.html', '567', 'images/uploads/y_signup1.jpg'),
(88, 18, 'elements/Yummy/signup2.html', '567', 'images/uploads/y_signup2.jpg'),
(89, 18, 'elements/Yummy/signup3.html', '567', 'images/uploads/y_signup3.jpg'),
(90, 18, 'elements/Yummy/signup5.html', '567', 'images/uploads/y_signup5.jpg'),
(91, 18, 'elements/Yummy/signup6.html', '567', 'images/uploads/y_signup6.jpg'),
(92, 19, 'elements/Yummy/slideshow1.html', '567', 'images/uploads/y_slideshow1.jpg'),
(93, 19, 'elements/Yummy/slideshow2.html', '567', 'images/uploads/y_slideshow2.jpg'),
(94, 19, 'elements/Yummy/slideshow7.html', '567', 'images/uploads/y_slideshow7.jpg'),
(95, 19, 'elements/Yummy/slideshow9.html', '567', 'images/uploads/y_slideshow9.jpg'),
(96, 20, 'elements/Yummy/subscribe1.html', '567', 'images/uploads/yummy_subscribe1.jpg'),
(97, 20, 'elements/Yummy/subscribe2.html', '567', 'images/uploads/yummy_subscribe2.jpg'),
(98, 20, 'elements/Yummy/subscribe3.html', '567', 'images/uploads/yummy_subscribe3.jpg'),
(99, 20, 'elements/Yummy/subscribe10.html', '567', 'images/uploads/yummy_subscribe10.jpg'),
(100, 21, 'elements/Yummy/team1.html', '567', 'images/uploads/y_team1.jpg'),
(101, 21, 'elements/Yummy/team3.html', '567', 'images/uploads/y_team3.jpg'),
(102, 21, 'elements/Yummy/team10.html', '567', 'images/uploads/y_team10.jpg'),
(103, 21, 'elements/Yummy/team12.html', '567', 'images/uploads/y_team12.jpg'),
(104, 21, 'elements/Yummy/team14.html', '567', 'images/uploads/y_team14.jpg'),
(105, 21, 'elements/Yummy/team16.html', '567', 'images/uploads/y_team16.jpg'),
(106, 22, 'elements/Yummy/testimonial1.html', '567', 'images/uploads/y_testimonial1.jpg'),
(107, 22, 'elements/Yummy/testimonial3.html', '567', 'images/uploads/y_testimonial3.jpg'),
(108, 22, 'elements/Yummy/testimonial6.html', '567', 'images/uploads/y_testimonial6.jpg'),
(109, 22, 'elements/Yummy/testimonial10.html', '567', 'images/uploads/y_testimonial10.jpg'),
(110, 23, 'elements/Yummy/title1.html', '567', 'images/uploads/y_title1.jpg'),
(111, 23, 'elements/Yummy/title5.html', '567', 'images/uploads/y_title5.jpg'),
(112, 23, 'elements/Yummy/title7.html', '567', 'images/uploads/y_title7.jpg'),
(113, 23, 'elements/Yummy/title9.html', '567', 'images/uploads/y_title9.jpg'),
(114, 23, 'elements/Yummy/title10.html', '567', 'images/uploads/y_title10.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `blocks_fav`
--

CREATE TABLE `blocks_fav` (
  `blocks_id` int(11) NOT NULL,
  `user_id` int(11) NULL,
  `blocks_url` varchar(255) NULL,
  `blocks_height` varchar(10) NULL,
  `blocks_thumb` varchar(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NULL,
  `timestamp` int(10) UNSIGNED NULL DEFAULT '0',
  `data` blob NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `components`
--

CREATE TABLE `components` (
  `components_id` int(11) NOT NULL,
  `components_category` int(11) NULL,
  `components_thumb` varchar(255) NULL,
  `components_height` int(4) NULL,
  `components_markup` longtext NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `components`
--

INSERT INTO `components` (`components_id`, `components_category`, `components_thumb`, `components_height`, `components_markup`) VALUES
(1, 1, 'images/uploads/grid-12.png', 70, '<div data-component=\"grid\" class=\"row\">\n    <div class=\"col-md-12\">\n        <div data-container=\"true\"></div>\n    </div>\n</div>'),
(2, 1, 'images/uploads/grid-6-6.png', 70, '<div data-component=\"grid\" class=\"row\">\n    <div class=\"col-md-6\">\n        <div data-container=\"true\"></div>\n    </div>\n    <div class=\"col-md-6\">\n        <div data-container=\"true\"></div>\n    </div>\n</div>'),
(3, 1, 'images/uploads/grid-4-8.png', 70, '<div data-component=\"grid\" class=\"row\">\n    <div class=\"col-md-4\">\n        <div data-container=\"true\"></div>\n    </div>\n    <div class=\"col-md-8\">\n        <div data-container=\"true\"></div>\n    </div>\n</div>'),
(4, 1, 'images/uploads/grid-8-4.png', 70, '<div data-component=\"grid\" class=\"row\">\n    <div class=\"col-md-8\">\n        <div data-container=\"true\"></div>\n    </div>\n    <div class=\"col-md-4\">\n        <div data-container=\"true\"></div>\n    </div>\n</div>'),
(5, 1, 'images/uploads/grid-4-4-4.png', 70, '<div data-component=\"grid\" class=\"row\">\n    <div class=\"col-md-4\">\n        <div data-container=\"true\"></div>\n    </div>\n    <div class=\"col-md-4\">\n        <div data-container=\"true\"></div>\n    </div>\n    <div class=\"col-md-4\">\n        <div data-container=\"true\"></div>\n    </div>\n</div>'),
(6, 1, 'images/uploads/grid-3-3-3-3.png', 70, '<div data-component=\"grid\" class=\"row\">\n    <div class=\"col-md-3\">\n        <div data-container=\"true\"></div>\n    </div>\n    <div class=\"col-md-3\">\n        <div data-container=\"true\"></div>\n    </div>\n    <div class=\"col-md-3\">\n        <div data-container=\"true\"></div>\n    </div>\n    <div class=\"col-md-3\">\n        <div data-container=\"true\"></div>\n    </div>\n</div>'),
(7, 1, 'images/uploads/grid-3-3-6.png', 70, '<div data-component=\"grid\" class=\"row\">\n    <div class=\"col-md-3\">\n        <div data-container=\"true\"></div>\n    </div>\n    <div class=\"col-md-3\">\n        <div data-container=\"true\"></div>\n    </div>\n    <div class=\"col-md-6\">\n        <div data-container=\"true\"></div>\n    </div>\n</div>'),
(8, 1, 'images/uploads/grid-6-3-3.png', 70, '<div data-component=\"grid\" class=\"row\">\n    <div class=\"col-md-6\">\n        <div data-container=\"true\"></div>\n    </div>\n    <div class=\"col-md-3\">\n        <div data-container=\"true\"></div>\n    </div>\n    <div class=\"col-md-3\">\n        <div data-container=\"true\"></div>\n    </div>\n</div>'),
(9, 2, 'images/uploads/heading1.png', 70, '<div data-component=\"heading\" data-content=\"true\">\n    <h1>This is a H1 heading</h1>\n</div>'),
(10, 2, 'images/uploads/heading2.png', 70, '<div data-component=\"heading\" data-content=\"true\">\n    <h2>This is a H2 heading</h2>\n</div>'),
(11, 2, 'images/uploads/heading3.png', 70, '<div data-component=\"heading\" data-content=\"true\">\n    <h3>This is a H3 heading</h3>\n</div>'),
(12, 2, 'images/uploads/heading4.png', 70, '<div data-component=\"heading\" data-content=\"true\">\n    <h4>This is a H4 heading</h4>\n</div>'),
(13, 2, 'images/uploads/heading5.png', 70, '<div data-component=\"heading\" data-content=\"true\">\n    <h5>This is a H5 heading</h5>\n</div>'),
(14, 2, 'images/uploads/heading6.png', 70, '<div data-component=\"heading\" data-content=\"true\">\n    <h6>This is a H6 heading</h6>\n</div>'),
(15, 3, 'images/uploads/button-large.png', 70, '<div data-component=\"button\">\n    <a href=\"#\" class=\"btn btn-default btn-lg\">Large button</a>\n</div>'),
(16, 3, 'images/uploads/button-regular.png', 70, '<div data-component=\"button\">\n    <a href=\"#\" class=\"btn btn-default\">Default button</a>\n</div>'),
(17, 3, 'images/uploads/button-small.png', 70, '<div data-component=\"button\">\n    <a href=\"#\" class=\"btn btn-default btn-sm\">Small button</a>\n</div>'),
(18, 3, 'images/uploads/button-extrasmall.png', 70, '<div data-component=\"button\">\n    <a href=\"#\" class=\"btn btn-default btn-xs\">Extra small button</a>\n</div>'),
(19, 3, 'images/uploads/button-block.png', 70, '<div data-component=\"button\">\n    <a href=\"#\" class=\"btn btn-default btn-block\">Block button</a>\n</div>'),
(20, 4, 'images/uploads/media-youtube.png', 70, '<div data-component=\"video\">\n    <div class=\"videoWrapper\">\n        <iframe width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/uQBL7pSAXR8?controls=0&showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>\n        <div class=\"frameCover\" data-type=\"video\"></div>\n    </div>\n</div>'),
(21, 4, 'images/uploads/media-vimeo.png', 70, '<div data-component=\"video\">\n    <div class=\"videoWrapper\">\n        <iframe width=\"560\" height=\"315\" src=\"//player.vimeo.com/video/164814704?title=0&amp;byline=0&amp;portrait=0\" frameborder=\"0\" allowfullscreen=\"\"></iframe>\n        <div class=\"frameCover\" data-type=\"video\" data-selector=\".frameCover\"></div>\n    </div>\n</div>'),
(22, 4, 'images/uploads/media-image.png', 70, '<div data-component=\"image\">\n    <img src=\"/img/exaple-image.jpg\">\n</div>'),
(23, 5, 'images/uploads/lists-list.png', 70, '<div data-component=\"list\">\n    <ul data-dnd-list data-content=\"true\">\n        <li draggable=\"true\">List item one</li>\n        <li draggable=\"true\">List item two</li>\n        <li draggable=\"true\">List item three</li>\n        <li draggable=\"true\">List item four</li>\n    </ul>\n</div>'),
(24, 5, 'images/uploads/lists-list-links.png', 70, '<div data-component=\"list\">\n    <ul data-dnd-list data-content=\"true\">\n        <li draggable=\"true\"><a href=\"#\">List item one</a></li>\n        <li draggable=\"true\"><a href=\"#\">List item two</a></li>\n        <li draggable=\"true\"><a href=\"#\">List item three</a></li>\n        <li draggable=\"true\"><a href=\"#\">List item four</a></li>\n    </ul>\n</div>'),
(25, 5, 'images/uploads/lists-list-basic.png', 70, '<div data-component=\"list\">\n    <ul class=\"list-basic\" data-dnd-list data-content=\"true\">\n        <li draggable=\"true\">List item one</li>\n        <li draggable=\"true\">List item two</li>\n        <li draggable=\"true\">List item three</li>\n        <li draggable=\"true\">List item four</li>\n    </ul>\n</div>'),
(26, 5, 'images/uploads/lists-list-basic-links.png', 70, '<div data-component=\"list\">\n    <ul class=\"list-basic\" data-dnd-list data-content=\"true\">\n        <li draggable=\"true\"><a href=\"#\">List item one</a></li>\n        <li draggable=\"true\"><a href=\"#\">List item two</a></li>\n        <li draggable=\"true\"><a href=\"#\">List item three</a></li>\n        <li draggable=\"true\"><a href=\"#\">List item four</a></li>\n    </ul>\n</div>'),
(27, 5, 'images/uploads/lists-list-social.png', 70, '<div data-component=\"list\">\n    <ul class=\"list-basic list-horizontal social-basic-sm list-left\" data-dnd-list data-content=\"true\">\n        <li draggable=\"true\"><a href=\"#\" data-parent=\"1\" class=\"fa fa-facebook-official\"></a></li>\n        <li draggable=\"true\"><a href=\"#\" data-parent=\"1\" class=\"fa fa-twitter-square\"></a></li>\n        <li draggable=\"true\"><a href=\"#\" data-parent=\"1\" class=\"fa fa-github-square\"></a></li>\n        <li draggable=\"true\"><a href=\"#\" data-parent=\"1\" class=\"fa fa-pinterest-square\"></a></li>\n    </ul></div>'),
(28, 6, 'images/uploads/text-normal.png', 70, '<div data-content=\"true\" data-component=\"text\">\n    <p>\n        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sit amet gravida dolor, at efficitur nulla. Nam et tellus nisl. Nunc semper malesuada sem, a porta quam elementum ut. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque a vestibulum ipsum, id eleifend neque.\n    </p>\n</div>'),
(29, 6, 'images/uploads/text-lead.png', 70, '<div data-content=\"true\" data-component=\"text\">\n    <p class=\"lead\">\n        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sit amet gravida dolor, at efficitur nulla. Nam et tellus nisl. Nunc semper malesuada sem, a porta quam elementum ut. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque a vestibulum ipsum, id eleifend neque.\n    </p>\n</div>'),
(30, 6, 'images/uploads/text-small.png', 70, '<div data-content=\"true\" data-component=\"text\">\n    <p class=\"small\">\n        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sit amet gravida dolor, at efficitur nulla. Nam et tellus nisl. Nunc semper malesuada sem, a porta quam elementum ut. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque a vestibulum ipsum, id eleifend neque.\n    </p>\n</div>'),
(31, 7, 'images/uploads/icon-small.png', 70, '<div data-component=\"icon\">\n    <span class=\"fa fa-gift\"></span>\n</div>'),
(32, 7, 'images/uploads/icon-medium.png', 70, '<div data-component=\"icon\">\n    <span class=\"fa fa-gift\" style=\"font-size: 28px\"></span>\n</div>'),
(33, 7, 'images/uploads/icon-large.png', 70, '<div data-component=\"icon\">\n    <span class=\"fa fa-gift\" style=\"font-size: 40px\"></span>\n</div>'),
(34, 8, 'images/uploads/embed-component.png', 70, '<div data-component=\"embed\"></div>'),
(35, 9, 'images/uploads/map.png', 70, '<div data-component=\"map\">\n    <div class=\"mapWrapper\">\n        <div id=\"gmap\" class=\"gmap border-radius-lg\" style=\"height: 250px; background-image: url(\'../bundles/84b583547a2afc0a6737224fa3379874.png\'); background-position: center center\"></div>\n        <div class=\"mapOverlay\"></div>\n    </div>\n</div>'),
(36, 10, 'images/uploads/navbar-default.png', 70, '<div data-component=\"navbar\">\n    <nav class=\"navbar navbar-default bloxby-navbar-left-right\" style=\"margin-bottom: 0px;\">\n        <div class=\"navbar-header\">\n            <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#navbar-inverse-collapse\">\n                <span class=\"sr-only\">Toggle navigation</span>\n                <span class=\"fa fa-bars\"></span>\n            </button>\n            <a class=\"navbar-brand\" href=\"#\">YOUR BRAND</a>\n        </div>\n        <div class=\"collapse navbar-collapse\" id=\"navbar-inverse-collapse\">\n            <ul class=\"nav navbar-nav\" data-dnd-list>\n                <li class=\"active\">\n                    <a href=\"#\" data-parent=\"1\">Home</a>\n                </li>\n                <li>\n                    <a href=\"#\" data-parent=\"1\">Work</a>\n                </li>\n                <li>\n                    <a href=\"#\" data-parent=\"1\">Blog</a>\n                </li>\n                <li>\n                    <a href=\"#\" data-parent=\"1\">Contact</a>\n                </li>\n            </ul>\n            <div class=\"navbar-buttons hidden-xs\" data-container=\"true\">\n                <div data-component=\"button\">\n                    <a href=\"#\" class=\"btn btn-default btn-xs\">Learn more</a>\n                </div>\n            </div>\n        </div>\n    </nav>\n</div>'),
(37, 10, 'images/uploads/navbar-inverse.png', 70, '<div data-component=\"navbar\">\n    <nav class=\"navbar navbar-inverse bloxby-navbar-left-right\" style=\"margin-bottom: 0px;\">\n        <div class=\"navbar-header\">\n            <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#navbar-inverse-collapse\">\n                <span class=\"sr-only\">Toggle navigation</span>\n                <span class=\"fa fa-bars\"></span>\n            </button>\n            <a class=\"navbar-brand\" href=\"#\">YOUR BRAND</a>\n        </div>\n        <div class=\"collapse navbar-collapse\" id=\"navbar-inverse-collapse\">\n            <ul class=\"nav navbar-nav\" data-dnd-list>\n                <li class=\"active\">\n                    <a href=\"#\" data-parent=\"1\">Home</a>\n                </li>\n                <li>\n                    <a href=\"#\" data-parent=\"1\">Work</a>\n                </li>\n                <li>\n                    <a href=\"#\" data-parent=\"1\">Blog</a>\n                </li>\n                <li>\n                    <a href=\"#\" data-parent=\"1\">Contact</a>\n                </li>\n            </ul>\n            <div class=\"navbar-buttons hidden-xs\" data-container=\"true\">\n                <div data-component=\"button\">\n                    <a href=\"#\" class=\"btn btn-default btn-xs\">Learn more</a>\n                </div>\n            </div>\n        </div>\n    </nav>\n</div>'),
(38, 10, 'images/uploads/navbar-plain1.png', 70, '<div data-component=\"navbar\">\n    <nav class=\"navbar navbar-plain bloxby-navbar-left-right\" style=\"margin-bottom: 0px;\">\n        <div class=\"navbar-header\">\n            <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#navbar-inverse-collapse\">\n                <span class=\"sr-only\">Toggle navigation</span>\n                <span class=\"fa fa-bars\"></span>\n            </button>\n            <a class=\"navbar-brand\" href=\"#\">YOUR BRAND</a>\n        </div>\n        <div class=\"collapse navbar-collapse\" id=\"navbar-inverse-collapse\">\n            <ul class=\"nav navbar-nav\" data-dnd-list>\n                <li class=\"active\">\n                    <a href=\"#\" data-parent=\"1\">Home</a>\n                </li>\n                <li>\n                    <a href=\"#\" data-parent=\"1\">Work</a>\n                </li>\n                <li>\n                    <a href=\"#\" data-parent=\"1\">Blog</a>\n                </li>\n                <li>\n                    <a href=\"#\" data-parent=\"1\">Contact</a>\n                </li>\n            </ul>\n            <div class=\"navbar-buttons hidden-xs\" data-container=\"true\">\n                <div data-component=\"button\">\n                    <a href=\"#\" class=\"btn btn-default btn-xs\">Learn more</a>\n                </div>\n            </div>\n        </div>\n    </nav>\n</div>'),
(39, 10, 'images/uploads/navbar-plain2.png', 70, '<div data-component=\"navbar\">\n    <nav class=\"navbar navbar-plain bloxby-navbar-centered\" style=\"margin-bottom: 0px;\">\n        <div class=\"navbar-header\">\n            <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#navbar-inverse-collapse\">\n                <span class=\"sr-only\">Toggle navigation</span>\n                <span class=\"fa fa-bars\"></span>\n            </button>\n            <a class=\"navbar-brand\" href=\"#\">YOUR BRAND</a>\n        </div>\n        <div class=\"collapse navbar-collapse\" id=\"navbar-inverse-collapse\">\n            <ul class=\"nav navbar-nav\" data-dnd-list>\n                <li class=\"active\">\n                    <a href=\"#\" data-parent=\"1\">Home</a>\n                </li>\n                <li>\n                    <a href=\"#\" data-parent=\"1\">Work</a>\n                </li>\n                <li>\n                    <a href=\"#\" data-parent=\"1\">Blog</a>\n                </li>\n                <li>\n                    <a href=\"#\" data-parent=\"1\">Contact</a>\n                </li>\n            </ul>\n            <div class=\"navbar-buttons hidden-xs\" data-container=\"true\">\n                <div data-component=\"button\">\n                    <a href=\"#\" class=\"btn btn-default btn-xs\">Learn more</a>\n                </div>\n            </div>\n        </div>\n    </nav>\n</div>');


-- --------------------------------------------------------

--
-- Table structure for table `components_categories`
--

CREATE TABLE `components_categories` (
  `components_categories_id` int(11) NOT NULL,
  `category_name` varchar(255) NULL,
  `list_order` int(4) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `components_categories`
--

INSERT INTO `components_categories` (`components_categories_id`, `category_name`, `list_order`) VALUES
(1, 'Grids', 999),
(2, 'Headings', 999),
(3, 'Buttons', 999),
(4, 'Media', 999),
(5, 'Lists', 999),
(6, 'Text', 999),
(7, 'Icons', 999),
(8, 'Embeds', 999),
(9, 'Map', 999),
(10, 'Navigation', 999);

-- --------------------------------------------------------

--
-- Table structure for table `core_settings`
--

CREATE TABLE `core_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NULL,
  `value` text NULL,
  `default_value` text NULL,
  `description` text NULL,
  `required` int(1) NULL DEFAULT '0',
  `created_at` datetime NULL,
  `modified_at` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `core_settings`
--

INSERT INTO `core_settings` (`id`, `name`, `value`, `default_value`, `description`, `required`, `created_at`, `modified_at`) VALUES
(1, 'auto_update', 'no', 'yes', '<h5>Auto Update</h5>\n<p>\nAutomatic update core system when there is update available in Bloxby.\n</p>', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'license_key', '', '', '<h5>License Key</h5>\n     <p>\n       License Key, without this key you are unable to get further updates.\n      </p>', 1, '2017-06-17 16:02:08', '0000-00-00 00:00:00'),
(3, 'overwrite_blocks', 'yes', 'yes', '<h5>Overwrite blocks</h5><p>If turned on, the auto update will overwrite the blocks (when the update includes changes to the blocks). If you have made modifications to the default blocks and do not want to risk those changes being overwritten, you should turn this off.</p>', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `frames`
--

CREATE TABLE `frames` (
  `frames_id` int(11) NOT NULL,
  `pages_id` int(11) NULL,
  `sites_id` int(11) NULL,
  `frames_content` text NULL,
  `frames_height` varchar(20) NULL,
  `frames_original_url` varchar(255) NULL,
  `frames_loaderfunction` varchar(255) NULL,
  `frames_sandbox` int(1) NULL DEFAULT '0',
  `frames_timestamp` int(11) NULL,
  `frames_global` int(1) NULL DEFAULT '0',
  `frames_popup` varchar(20) DEFAULT '',
  `frames_embeds` longtext,
  `frames_settings` text NULL,
  `favourite` int(1) NULL DEFAULT '0',
  `revision` int(1) NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------
--
-- Table structure for table `integrations`
--

CREATE TABLE `integrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `configured` tinyint(1) NOT NULL DEFAULT '0',
  `value` text,
  `date` datetime NOT NULL,
  `token` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(7);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `gateway` varchar(20) DEFAULT NULL,
  `stripe_id` varchar(100) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `sites_number` int(11) DEFAULT NULL,
  `pages_number` int(11) DEFAULT NULL,
  `edit_sourcecode` varchar(10) DEFAULT NULL,
  `hosting_option` varchar(100) DEFAULT NULL,
  `export_site` varchar(10) DEFAULT NULL,
  `ftp_publish` varchar(10) DEFAULT 'no',
  `disk_space` varchar(50) DEFAULT NULL,
  `templates` text DEFAULT NULL,
  `blocks` text DEFAULT NULL,
  `price` double DEFAULT NULL,
  `currency` varchar(20) DEFAULT NULL,
  `subscription` varchar(50) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `pages_id` int(11) NOT NULL,
  `sites_id` int(11) NULL,
  `pages_name` varchar(255) NULL,
  `pages_timestamp` int(11) NULL,
  `pages_title` varchar(255) NULL,
  `pages_meta_keywords` text NULL,
  `pages_meta_description` text NULL,
  `pages_header_includes` text NULL,
  `pages_preview` text NULL,
  `pages_template` int(1) NULL DEFAULT '0',
  `pages_css` text NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `pagethumb` varchar(255) NULL,
  `google_fonts` longtext DEFAULT NULL,
  `cloudflare_dns` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment_log`
--

CREATE TABLE `payment_log` (
  `id` int(11) NOT NULL,
  `request` text NULL,
  `response` text NULL,
  `date` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment_settings`
--

CREATE TABLE `payment_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NULL,
  `value` text NULL,
  `default_value` text NULL,
  `description` text NULL,
  `required` int(1) NULL DEFAULT '0',
  `created_at` datetime NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_settings`
--

INSERT INTO `payment_settings` (`id`, `name`, `value`, `default_value`, `description`, `required`, `created_at`, `modified_at`) VALUES
(1, 'stripe_secret_key', '', '', 'Your Stripe Secret Key.\r\n<br>For more information <a href="https://stripe.com/docs/dashboard#api-keys" target="_blank">Click Here</a>', 0, '0000-00-00 00:00:00', NULL),
(2, 'stripe_publishable_key', '', '', 'Your Stripe Publishable Key.\r\n<br>For more information <a href="https://stripe.com/docs/dashboard#api-keys" target="_blank">Click Here</a>', 0, '0000-00-00 00:00:00', NULL),
(3, 'stripe_test_mode', 'test', 'test', 'Your Stripe Environment', 0, '0000-00-00 00:00:00', NULL),
(4, 'paypal_api_username', '', '', 'Enter your PayPal API username.\r\n<br> For more details <a href="https://developer.paypal.com/docs/classic/api/apiCredentials/#create-an-api-signature" target="_blank">Click Here</a>', 0, '0000-00-00 00:00:00', NULL),
(5, 'paypal_api_password', '', '', 'Enter PayPal API password.\r\n<br> For more details <a href="https://developer.paypal.com/docs/classic/api/apiCredentials/#create-an-api-signature" target="_blank">Click Here</a>', 0, '0000-00-00 00:00:00', NULL),
(6, 'paypal_api_signature', '', '', 'Enter PayPal API signature.\r\n<br> For more details <a href="https://developer.paypal.com/docs/classic/api/apiCredentials/#create-an-api-signature" target="_blank">Click Here</a>', 0, '0000-00-00 00:00:00', NULL),
(7, 'paypal_test_mode', '', '', 'Your PayPal Environment', 0, '0000-00-00 00:00:00', NULL),
(8, 'payment_gateway', 'stripe', 'stripe', 'Select Payment gateway you want for end users.', 0, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rest_api_keys`
--
CREATE TABLE `rest_api_keys` (
  `id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `settings_id` int(11) NOT NULL,
  `name` varchar(255) NULL,
  `value` longtext NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settings_id`, `name`, `value`) VALUES
(1, 'rest_on', '0'),
(2, 'rest_ip_whitelist_enabled', '0'),
(3, 'rest_ip_whitelist', ''),
(4, 'alogin_on', '0'),
(5, 'alogin_key', '');

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `sites_id` int(11) NOT NULL,
  `users_id` int(11) NULL,
  `sites_name` varchar(255) NULL,
  `custom_domain` varchar(150) DEFAULT NULL,
  `sub_domain` varchar(150) DEFAULT NULL,
  `sub_folder` varchar(150) DEFAULT NULL,
  `home_page` tinyint(4) NULL DEFAULT '0',
  `sites_created_on` varchar(100) NULL,
  `sites_lastupdate_on` varchar(100) NULL,
  `ftp_type` varchar(10) NULL,
  `ftp_server` varchar(255) NULL,
  `ftp_user` varchar(255) NULL,
  `ftp_password` varchar(255) NULL,
  `ftp_path` varchar(255) NULL DEFAULT '/',
  `ftp_port` int(8) NULL DEFAULT '21',
  `ftp_ok` int(1) NULL,
  `ftp_published` int(1) NULL DEFAULT '0',
  `publish_date` int(11) NULL DEFAULT '0',
  `global_css` text NULL,
   `global_js` text NULL,
  `remote_url` varchar(255) NULL,
  `sites_trashed` int(1) NULL DEFAULT '0',
  `viewmode` varchar(255) NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `sitethumb` varchar(255) NULL,
  `cloudflare_dns` int(1) NULL,
  `favicon` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `template_categories`
--

CREATE TABLE `template_categories` (
  `templates_categories_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `template_to_category`
--

CREATE TABLE `template_to_category` (
  `id` int(11) NOT NULL,
  `pages_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `package_id` int(11) NULL DEFAULT '0',
  `username` varchar(100) NULL,
  `email` varchar(255) NULL,
  `password` varchar(255) NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgot_code` varchar(40) DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `stripe_cus_id` varchar(50) DEFAULT NULL,
  `stripe_sub_id` varchar(50) DEFAULT NULL,
  `paypal_token` varchar(255) DEFAULT NULL,
  `paypal_profile_id` varchar(255) DEFAULT NULL,
  `paypal_profile_status` varchar(20) DEFAULT NULL,
  `paypal_last_transaction_id` varchar(30) DEFAULT NULL,
  `current_subscription_gateway` enum('stripe','paypal') NULL,
  `payer_id` varchar(128) DEFAULT NULL,
  `paypal_next_payment_date` varchar(128) DEFAULT NULL,
  `paypal_previous_payment_date` varchar(128) DEFAULT NULL,
  `type` varchar(30) NULL,
  `status` enum('Active','Inactive') NULL,
  `created_at` datetime NULL,
  `modified_at` datetime DEFAULT NULL,
  `login_token` varchar(40) NULL,
  `auto_login_token` varchar(40) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `package_id`, `username`, `email`, `password`, `first_name`, `last_name`, `activation_code`, `forgot_code`, `remember_code`, `last_login`, `stripe_cus_id`, `stripe_sub_id`, `paypal_token`, `paypal_profile_id`, `paypal_profile_status`, `paypal_last_transaction_id`, `current_subscription_gateway`, `payer_id`, `paypal_next_payment_date`, `paypal_previous_payment_date`, `type`, `status`, `created_at`, `modified_at`) VALUES
(1, 0, 'admin@admin.com', 'admin@admin.com', 'f865b53623b121fd34ee5426c792e5c3', 'Admin', 'istrator', '1b21159590f6513bdbe7094d4ad327b1', NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', '', '', '', 'Admin', 'Active', '2016-11-30 04:44:51', '2017-07-14 13:48:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apps_settings`
--
ALTER TABLE `apps_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blocks_categories`
--
ALTER TABLE `blocks_categories`
ADD PRIMARY KEY (`blocks_categories_id`),
ADD KEY `category_name` (`category_name`);


--
-- Indexes for table `blocks`
--
ALTER TABLE `blocks`
ADD PRIMARY KEY (`blocks_id`),
ADD KEY `blocks_category` (`blocks_category`),
ADD KEY `blocks_url` (`blocks_url`);

--
-- Indexes for table `blocks_fav`
--
ALTER TABLE `blocks_fav`
ADD PRIMARY KEY (`blocks_id`),
ADD KEY `user_id` (`user_id`);


--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `components`
--
ALTER TABLE `components`
ADD PRIMARY KEY (`components_id`),
ADD KEY `components_category` (`components_category`);

--
-- Indexes for table `components_categories`
--
ALTER TABLE `components_categories`
  ADD PRIMARY KEY (`components_categories_id`);

--
-- Indexes for table `core_settings`
--
ALTER TABLE `core_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frames`
--
ALTER TABLE `frames`
  ADD PRIMARY KEY (`frames_id`),
ADD KEY `pages_id` (`pages_id`),
ADD KEY `sites_id` (`sites_id`),
ADD KEY `frames_timestamp` (`frames_timestamp`);

--
-- Indexes for table `integrations`
--
ALTER TABLE `integrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
ADD PRIMARY KEY (`pages_id`),
ADD KEY `sites_id` (`sites_id`),
ADD KEY `pages_name` (`pages_name`);


--
-- Indexes for table `payment_log`
--
ALTER TABLE `payment_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_settings`
--
ALTER TABLE `payment_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rest_api_keys`
--
ALTER TABLE `rest_api_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settings_id`);

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
ADD PRIMARY KEY (`sites_id`),
ADD KEY `users_id` (`users_id`);

--
-- Indexes for table `template_categories`
--
ALTER TABLE `template_categories`
  ADD PRIMARY KEY (`templates_categories_id`);

--
-- Indexes for table `template_to_category`
--
ALTER TABLE `template_to_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pages_id` (`pages_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
ADD PRIMARY KEY (`id`),
ADD KEY `package_id` (`package_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apps_settings`
--
ALTER TABLE `apps_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `blocks_categories`
--
ALTER TABLE `blocks_categories`
  MODIFY `blocks_categories_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `blocks`
--
ALTER TABLE `blocks`
  MODIFY `blocks_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `blocks_fav`
--
ALTER TABLE `blocks_fav`
  MODIFY `blocks_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_settings`
--
ALTER TABLE `core_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `components`
--
ALTER TABLE `components`
  MODIFY `components_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `components_categories`
--
ALTER TABLE `components_categories`
  MODIFY `components_categories_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `frames`
--
ALTER TABLE `frames`
  MODIFY `frames_id` int(11) NOT NULL AUTO_INCREMENT;
  
-- AUTO_INCREMENT for table `integrations`
--
ALTER TABLE `integrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `pages_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_log`
--
ALTER TABLE `payment_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_settings`
--
ALTER TABLE `payment_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `rest_api_keys`
--
ALTER TABLE `rest_api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `sites`
--
ALTER TABLE `sites`
  MODIFY `sites_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `template_categories`
--
ALTER TABLE `template_categories`
  MODIFY `templates_categories_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `template_to_category`
--
ALTER TABLE `template_to_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
