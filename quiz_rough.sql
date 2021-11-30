-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2021 at 05:53 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz_rough`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `getResults` ()  BEGIN

 

DROP TEMPORARY TABLE IF EXISTS tempResultsTable;

CREATE TEMPORARY TABLE  IF NOT EXISTS tempResultsTable (
    id int AUTO_INCREMENT PRIMARY KEY,
    userid int,
    username varchar(100),
    email varchar(100),
    categoryid int,
    categoryname varchar(100),
    totalResponse int,
    correct int,
    incorrect int,
    unattended int
     
);
 insert into tempResultsTable(userid,username,email,categoryid,categoryname,totalResponse) 
 
 SELECT
 		users.id as userid, users.name as username, 
        users.email as email , categories.id as categoryid, 
        categories.name as categoryname , count(results.id) as totalResponse 
		FROM users 
        join results on users.id=results.user_id 
        JOIN categories on categories.id=results.category_id GROUP by 				     		results.user_id,results.category_id;
        
 update   tempResultsTable set correct = (SELECT  count(results.id) as correct 
 			FROM `results` join questions on results.question_id=questions.id 
            LEFT join options on results.reponse_id=options.choice_id and 		    			results.question_id=options.question_id where options.correct_choice=1 				GROUP by results.user_id, questions.category_id) ;   
            
 SELECT * FROM tempResultsTable;
 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getResultsReport` ()  select ousr.id as userid,ousr.email, ocat.id as catid,ocat.name,

        count(DISTINCT a.question_id) + count(DISTINCT b.question_id)+  count(DISTINCT c.question_id) as total_count,
        count(DISTINCT a.question_id) as unattended_count,
        count(DISTINCT b.question_id) as failure_count,
        count(DISTINCT c.question_id) as success_count

        from
        results oexm
        inner JOIN users ousr ON oexm.user_id = ousr.id
        inner join questions oques ON oexm.question_id = oques.id
        inner join categories ocat ON oques.category_id = ocat.id
        left join
        (select
        DISTINCT exm.question_id, cat.id, exm.user_id
         from results exm
         inner join options opt on exm.question_id = opt.question_id and exm.reponse_id is null
         inner join users usr ON exm.user_id = usr.id
         inner join questions ques ON exm.question_id = ques.id
        inner join categories cat ON ques.category_id = cat.id
         group by exm.question_id, cat.id, exm.user_id
        ) a on a.question_id = oques.id  and oques.category_id = a.id and oexm.user_id=a.user_id

        left join
        (select  DISTINCT exm.question_id, cat.id, exm.user_id from results exm
         inner join options opt on exm.question_id = opt.question_id and exm.reponse_id = opt.choice_id and opt.correct_choice <> 1
         inner join users usr ON exm.user_id = usr.id
         inner join questions ques ON exm.question_id = ques.id
        inner join categories cat ON ques.category_id = cat.id
         group by exm.question_id, cat.id, exm.user_id
        ) b on b.question_id = oques.id  and oques.category_id = b.id and oexm.user_id=b.user_id

        left join
        (select exm.question_id, cat.id, exm.user_id from results exm
         inner join options opt on exm.question_id = opt.question_id and exm.reponse_id = opt.choice_id and  opt.correct_choice = 1
         inner join users usr ON exm.user_id = usr.id
         inner join questions ques ON exm.question_id = ques.id
        inner join categories cat ON ques.category_id = cat.id
         group by exm.question_id, cat.id, exm.user_id
        ) c on c.question_id = oques.id and oques.category_id = c.id and oexm.user_id=c.user_id

        group by ousr.id, ocat.id ,ousr.email,ocat.name order by ousr.id asc$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PHP', NULL, NULL, NULL),
(2, 'JAVASCRIPT ', NULL, NULL, NULL),
(3, 'JAVA', NULL, NULL, NULL),
(4, 'MATHS ', NULL, NULL, NULL),
(5, 'English', '2021-09-08 17:20:01', '2021-09-08 17:20:01', NULL),
(6, 'dddd', '2021-09-08 17:36:44', '2021-09-08 17:36:53', '2021-09-08 17:36:53'),
(7, 'GK', '2021-09-08 19:24:24', '2021-09-08 19:24:47', NULL),
(8, 'Chemistry ⚗', '2021-09-14 02:02:14', '2021-09-14 02:02:14', NULL),
(9, 'aaaa', '2021-09-19 23:51:16', '2021-09-20 01:38:03', '2021-09-20 01:38:03'),
(10, 'bbbb', '2021-09-19 23:51:32', '2021-09-20 01:38:03', '2021-09-20 01:38:03'),
(11, 'dadadsadsa', '2021-09-20 01:43:12', '2021-09-20 01:44:51', '2021-09-20 01:44:51'),
(12, 'dsfsdfdssdfsdfa', '2021-09-20 01:43:23', '2021-09-20 01:43:52', '2021-09-20 01:43:52'),
(13, 'vvvvvvvvvvvv', '2021-09-20 01:43:40', '2021-09-20 01:43:52', '2021-09-20 01:43:52');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(9, '2014_10_12_000000_create_users_table', 1),
(10, '2014_10_12_100000_create_password_resets_table', 1),
(11, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(12, '2019_08_19_000000_create_failed_jobs_table', 1),
(13, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(14, '2021_08_28_073207_create_sessions_table', 1),
(15, '2021_08_28_104538_create_questions_table', 1),
(16, '2021_08_28_104609_create_options_table', 1),
(19, '2021_09_07_142440_create_roles_table', 2),
(20, '2021_09_06_111728_role_id_to_users_table', 3),
(21, '2021_09_08_060003_create_categories_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int(10) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `choice_id` int(10) UNSIGNED NOT NULL,
  `choice_text` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correct_choice` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `question_id`, `choice_id`, `choice_text`, `correct_choice`, `created_at`, `updated_at`, `category_id`) VALUES
(1, 1, 1, 'Object Oriented', 1, '2020-09-30 01:28:24', '2020-09-30 01:28:24', 1),
(2, 1, 2, '<pre>Function and Procedure Oriented</pre>', 0, '2020-09-30 01:28:24', '2021-09-15 23:27:03', 1),
(3, 1, 3, 'Project oriented', 0, '2020-09-30 01:28:24', '2020-09-30 01:28:24', 1),
(4, 1, 4, 'Platform oriented', 0, '2020-09-30 01:28:24', '2020-09-30 01:28:24', 1),
(5, 1, 5, 'OS oriented', 0, '2020-09-30 01:28:24', '2020-09-30 01:28:24', 1),
(7, 2, 1, 'Taylor', 0, '2020-09-30 01:28:24', '2020-09-30 01:28:24', 1),
(8, 2, 2, 'Peter', 0, '2020-09-30 01:28:24', '2020-09-30 01:28:24', 1),
(9, 2, 3, 'Charles', 0, '2020-09-30 01:28:24', '2020-09-30 01:28:24', 1),
(10, 2, 4, 'Rasmus', 1, '2020-09-30 01:28:24', '2020-09-30 01:28:24', 1),
(11, 3, 1, 'PHP: Hypertext Preprocessor', 1, '2021-08-24 14:00:00', '2021-08-18 14:00:00', 1),
(12, 3, 2, 'Personal Hypertext Processor', 0, '2021-08-11 14:00:00', '2021-08-15 14:00:00', 1),
(13, 3, 3, 'Private Home Page', 0, '2021-08-11 14:00:00', '2021-08-08 14:00:00', 1),
(14, 4, 1, 'Object Oriented', 1, '2021-08-24 14:00:00', '2021-08-18 14:00:00', 1),
(15, 4, 2, 'Personal Hypertext Processor', 0, '2021-08-11 14:00:00', '2021-08-15 14:00:00', 1),
(16, 4, 3, 'Nuetral ', 0, '2021-08-11 14:00:00', '2021-08-08 14:00:00', 1),
(17, 5, 1, '<pre>&lt;?php .. ?&gt;</pre>', 1, '2021-08-11 14:00:00', '2021-09-14 01:26:48', 1),
(18, 5, 2, '<pre>&lt;?php &gt; .. &lt;/?&gt;</pre>', 0, '2021-08-24 14:00:00', '2021-09-14 01:27:30', 1),
(19, 5, 3, '<pre>&lt;php?&gt;...&lt;/php?&gt;</pre>', 0, '2021-08-11 14:00:00', '2021-09-14 01:32:35', 1),
(20, 5, 4, '<pre>&lt;script&gt;..&lt;/script&gt;</pre>', 0, '2021-08-11 14:00:00', '2021-09-14 01:29:59', 1),
(21, 6, 1, 'Yes', 1, '2021-08-17 14:00:00', '2021-08-08 14:00:00', 1),
(22, 6, 2, 'No', 0, '2021-08-24 14:00:00', '2021-08-10 14:00:00', 1),
(23, 7, 1, 'const', 0, '2021-08-11 14:00:00', '2021-08-15 14:00:00', 1),
(24, 7, 2, 'constant', 0, '2021-08-11 14:00:00', '2021-08-08 14:00:00', 1),
(25, 7, 3, 'define', 1, '2021-08-17 14:00:00', '2021-08-08 14:00:00', 1),
(26, 7, 4, ' #pragma', 0, '2021-08-24 14:00:00', '2021-08-10 14:00:00', 1),
(27, 8, 1, 'True', 1, '2021-08-17 14:00:00', '2021-08-08 14:00:00', 1),
(28, 8, 2, 'False', 0, '2021-08-24 14:00:00', '2021-08-10 14:00:00', 1),
(29, 9, 1, '// commented code to end of line', 0, '2021-08-24 14:00:00', '2021-08-10 14:00:00', 1),
(30, 9, 2, '/* commented code here */', 0, '2021-08-17 14:00:00', '2021-08-08 14:00:00', 1),
(31, 9, 3, '# commented code to end of line', 0, '2021-08-24 14:00:00', '2021-08-10 14:00:00', 1),
(32, 9, 4, 'all of the above ', 1, '2021-08-24 14:00:00', '2021-08-10 14:00:00', 1),
(33, 13, 1, '<pre>js</pre>', 0, NULL, '2021-09-14 01:19:19', 2),
(34, 13, 2, '<pre>&lt;sct&gt;</pre>', 0, NULL, '2021-09-14 01:21:38', 2),
(35, 13, 3, '<pre>&lt;javascript&gt;</pre>', 0, NULL, '2021-09-14 01:22:31', 2),
(36, 13, 4, '<pre>&lt;jsceipts&gt;</pre>', 0, NULL, '2021-09-14 01:23:46', 2),
(37, 13, 5, '&lt;script&gt;', 1, NULL, '2021-09-12 14:32:00', 2),
(38, 24, 1, 'Sanjith', 1, '2021-09-09 16:18:11', '2021-09-09 16:18:11', 7),
(39, 2, 5, 'Sanjith', 0, '2021-09-09 16:19:59', '2021-09-09 16:19:59', 1),
(40, 23, 1, '3.14', 1, '2021-09-09 16:29:51', '2021-09-09 16:29:51', 4),
(42, 24, 2, 'Devna', 1, '2021-09-09 18:28:03', '2021-09-09 18:28:03', 7),
(43, 25, 1, 'New Delhi', 1, '2021-09-09 18:36:04', '2021-09-09 18:36:04', 7),
(44, 25, 2, 'Mumbai', 0, '2021-09-09 18:36:20', '2021-09-09 18:36:20', 7),
(45, 25, 3, 'Tamil Nadu', 0, '2021-09-09 18:36:38', '2021-09-09 18:36:38', 7),
(46, 15, 1, 'document.getElementById(\"demo\").innerHTML = \"Hello World!\";', 1, NULL, NULL, 2),
(47, 15, 2, '#demo.innerHTML = \"Hello World!\";', 0, NULL, NULL, 2),
(48, 15, 3, 'document.getElement(\"p\").innerHTML = \"Hello World!\";', 0, NULL, NULL, 2),
(49, 16, 1, 'The &lt;body&gt; section', 0, NULL, NULL, 2),
(50, 16, 2, 'Both the &lt;head&gt; section and the &lt;body&gt; section are correct', 1, NULL, NULL, 2),
(51, 16, 3, 'The &lt;head&gt; section', 0, NULL, NULL, 2),
(52, 23, 2, '2.14', 0, '2021-09-12 16:05:14', '2021-09-12 16:05:14', 4),
(53, 26, 1, 'Victoria', 0, '2021-09-12 21:08:28', '2021-09-12 21:11:58', 7),
(54, 26, 2, 'New South Wales', 0, '2021-09-12 21:09:28', '2021-09-12 21:12:41', 7),
(55, 26, 3, 'Canberra', 1, '2021-09-12 21:09:29', '2021-09-12 21:09:29', 7),
(56, 27, 1, 'Tokyo', 1, '2021-09-13 01:33:19', '2021-09-13 01:33:19', 7),
(57, 27, 2, 'Kiyot', 0, '2021-09-13 01:33:49', '2021-09-13 01:33:49', 7),
(58, 27, 3, 'Hiroshima', 0, '2021-09-13 01:34:38', '2021-09-13 01:34:38', 7),
(59, 28, 1, '0', 1, '2021-09-13 02:28:49', '2021-09-13 02:28:49', 3),
(60, 28, 2, '1', 0, '2021-09-13 02:29:12', '2021-09-13 02:29:12', 3),
(61, 28, 3, 'Garbage value', 0, '2021-09-13 02:29:49', '2021-09-13 02:29:49', 3),
(62, 29, 1, 'I = 0', 0, '2021-09-13 02:30:38', '2021-09-13 02:30:38', 3),
(63, 29, 2, 'I = 1', 1, '2021-09-13 02:31:03', '2021-09-13 02:31:03', 3),
(64, 29, 3, 'I = 2', 0, '2021-09-13 02:31:34', '2021-09-13 02:31:34', 3),
(65, 30, 1, 'one class inheriting from more super classes', 1, '2021-09-13 02:32:55', '2021-09-13 02:32:55', 3),
(66, 30, 2, 'more classes inheriting from one super class', 0, '2021-09-13 02:33:20', '2021-09-13 02:33:20', 3),
(67, 30, 3, 'more classes inheriting from more super classes', 0, '2021-09-13 02:33:48', '2021-09-13 02:33:48', 3),
(68, 30, 4, 'None of the above', 0, '2021-09-13 02:34:17', '2021-09-13 02:34:17', 3),
(73, 34, 1, '<pre>Mn</pre>', 1, '2021-09-14 02:14:08', '2021-09-14 02:14:08', 8),
(74, 34, 2, '<pre>Mz</pre>', 0, '2021-09-14 02:14:46', '2021-09-14 02:18:47', 8),
(75, 34, 3, '<pre>Ma</pre>', 0, '2021-09-14 02:15:29', '2021-09-14 02:15:29', 8),
(76, 35, 1, '<pre>7</pre>', 0, '2021-09-15 20:20:23', '2021-09-15 20:20:23', 8),
(77, 35, 2, '<pre>3</pre>', 0, '2021-09-15 20:21:18', '2021-09-15 20:21:18', 8),
(78, 35, 3, '<pre>4</pre>', 1, '2021-09-15 20:22:33', '2021-09-15 20:22:33', 8),
(79, 35, 4, '<pre>5</pre>', 0, '2021-09-15 20:23:15', '2021-09-15 20:23:15', 8),
(80, 35, 5, '<pre>6</pre>', 0, '2021-09-15 20:24:15', '2021-09-15 20:24:15', 8),
(81, 24, 3, '<pre>Drishya</pre>', 1, '2021-09-15 22:19:31', '2021-09-15 22:19:31', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('devna@gmail.com', '$2y$10$7kAv3eT1CbZEerqylu/78ef.XPWXdI53.uj9qLbei1VfCTYM9fVXe', '2021-09-10 22:19:50');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `question_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question_text`, `created_at`, `updated_at`, `category_id`) VALUES
(1, 'PHP is a -------scripting language', '2020-09-29 15:28:24', '2021-09-08 16:32:20', 1),
(2, 'Father of PHP', '2020-09-29 15:28:24', '2020-09-29 15:28:24', 1),
(3, 'What does PHP stands for?', '2020-09-29 15:28:24', '2020-09-29 15:28:24', 1),
(4, 'PHP is multithreaded?', '2020-09-29 15:28:24', '2020-09-29 15:28:24', 1),
(5, 'PHP server scripts are surrounded by delimiters, which?', '2020-09-29 15:28:24', '2020-09-29 15:28:24', 1),
(6, 'Is PHP serverside?', '2020-09-29 15:28:24', '2020-09-29 15:28:24', 1),
(7, 'Which of the following is used to declare a constant', '2020-09-29 15:28:24', '2020-09-29 15:28:24', 1),
(8, '<pre>$var = \'false\'; if ($var) { echo \'true\'; } else { echo \'false\'; }</pre>', '2020-09-29 15:28:24', '2021-09-14 01:34:42', 1),
(9, 'Which of the following is the way to create comments in PHP?', '2020-09-29 15:28:24', '2020-09-29 15:28:24', 1),
(10, 'PHP server scripts are surrounded by delimiters, which?', '2020-09-29 15:28:24', '2020-09-29 15:28:24', 1),
(11, ' What will be the value of $var?', '2020-09-29 15:28:24', '2020-09-29 15:28:24', 1),
(12, 'How do we access the value of \'d\' later?', '2020-09-29 15:28:24', '2020-09-29 15:28:24', 1),
(13, 'Inside which HTML element do we put the JavaScript?', NULL, NULL, 2),
(15, 'What is the correct JavaScript syntax to change the content of the HTML element below?\r\n<p id=\"demo\">This is a demonstration.</p>\r\n', NULL, NULL, 2),
(16, 'Where is the correct place to insert a JavaScript?', NULL, NULL, 2),
(23, 'What is the value of PI', '2021-09-08 03:05:55', '2021-09-08 03:05:55', 4),
(24, 'Who am I?', '2021-09-08 19:25:37', '2021-09-08 19:25:37', 7),
(25, 'Capital of India?', '2021-09-09 18:32:21', '2021-09-09 18:32:21', 7),
(26, 'Capital of Australia', '2021-09-12 21:07:22', '2021-09-12 21:07:22', 7),
(27, 'Capital of Japan', '2021-09-13 01:32:20', '2021-09-13 01:32:20', 7),
(28, 'The default value of a static integer variable of a class in Java is,', '2021-09-13 02:22:44', '2021-09-13 02:22:44', 3),
(29, '<pre>public class testincr { \r\n      public static void main(String args[])\r\n      { \r\n        int i = 0;\r\n        i = i++ + i; \r\n        System.out.println(“I = ” +i); \r\n} }</pre><div><br></div>', '2021-09-13 02:23:23', '2021-09-14 01:51:45', 3),
(30, 'Multiple inheritance means,', '2021-09-13 02:27:44', '2021-09-13 02:27:44', 3),
(31, '<div>In which country <strong>Tajmahal</strong> is located</div>', '2021-09-14 00:11:47', '2021-09-14 00:11:47', 7),
(34, '<pre>The chemical symbol for manganese is</pre>', '2021-09-14 02:07:29', '2021-09-14 02:07:29', 8),
(35, '<div>The number 0.005436 has how many significant figures?</div>', '2021-09-14 02:08:10', '2021-09-14 02:08:10', 8);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `reponse_id` int(10) UNSIGNED DEFAULT 0,
  `response_text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `user_id`, `question_id`, `reponse_id`, `response_text`, `created_at`, `updated_at`, `category_id`) VALUES
(1350, 18, 13, 5, NULL, '2021-09-29 16:29:15', '2021-09-29 16:29:15', 2),
(1351, 18, 15, NULL, NULL, '2021-09-29 16:29:15', '2021-09-29 16:29:15', 2),
(1352, 18, 16, 3, NULL, '2021-09-29 16:29:15', '2021-09-29 16:29:15', 2),
(1353, 18, 1, 1, NULL, '2021-09-29 17:04:16', '2021-09-29 17:04:16', 1),
(1354, 18, 2, 4, NULL, '2021-09-29 17:04:16', '2021-09-29 17:04:16', 1),
(1355, 18, 3, 1, NULL, '2021-09-29 17:04:16', '2021-09-29 17:04:16', 1),
(1356, 18, 4, 1, NULL, '2021-09-29 17:04:16', '2021-09-29 17:04:16', 1),
(1357, 18, 5, 1, NULL, '2021-09-29 17:04:16', '2021-09-29 17:04:16', 1),
(1358, 18, 6, NULL, NULL, '2021-09-29 17:04:16', '2021-09-29 17:04:16', 1),
(1359, 18, 7, NULL, NULL, '2021-09-29 17:04:16', '2021-09-29 17:04:16', 1),
(1360, 18, 8, NULL, NULL, '2021-09-29 17:04:16', '2021-09-29 17:04:16', 1),
(1361, 18, 9, 3, NULL, '2021-09-29 17:04:16', '2021-09-29 17:04:16', 1),
(1362, 18, 28, 1, NULL, '2021-09-30 02:29:48', '2021-09-30 02:29:48', 3),
(1363, 18, 29, NULL, NULL, '2021-09-30 02:29:48', '2021-09-30 02:29:48', 3),
(1364, 18, 30, 3, NULL, '2021-09-30 02:29:48', '2021-09-30 02:29:48', 3),
(1365, 3, 28, 1, NULL, '2021-10-01 22:28:17', '2021-10-01 22:28:17', 3),
(1366, 3, 29, 2, NULL, '2021-10-01 22:28:17', '2021-10-01 22:28:17', 3),
(1367, 3, 30, 2, NULL, '2021-10-01 22:28:17', '2021-10-01 22:28:17', 3),
(1371, 11, 13, 5, NULL, '2021-10-04 00:44:04', '2021-10-04 00:44:04', 2),
(1372, 11, 15, 1, NULL, '2021-10-04 00:44:04', '2021-10-04 00:44:04', 2),
(1373, 11, 16, 2, NULL, '2021-10-04 00:44:04', '2021-10-04 00:44:04', 2),
(1374, 10, 1, 1, NULL, '2021-10-10 00:27:56', '2021-10-10 00:27:56', 1),
(1375, 10, 2, 4, NULL, '2021-10-10 00:27:56', '2021-10-10 00:27:56', 1),
(1376, 10, 3, 1, NULL, '2021-10-10 00:27:56', '2021-10-10 00:27:56', 1),
(1377, 10, 4, 1, NULL, '2021-10-10 00:27:56', '2021-10-10 00:27:56', 1),
(1378, 10, 5, 1, NULL, '2021-10-10 00:27:56', '2021-10-10 00:27:56', 1),
(1379, 10, 6, 1, NULL, '2021-10-10 00:27:56', '2021-10-10 00:27:56', 1),
(1380, 10, 7, 3, NULL, '2021-10-10 00:27:56', '2021-10-10 00:27:56', 1),
(1381, 10, 8, 2, NULL, '2021-10-10 00:27:56', '2021-10-10 00:27:56', 1),
(1382, 10, 9, 4, NULL, '2021-10-10 00:27:56', '2021-10-10 00:27:56', 1),
(1384, 10, 23, 1, NULL, '2021-10-11 00:31:54', '2021-10-11 00:31:54', 4),
(1385, 9, 24, 2, NULL, '2021-10-11 00:34:35', '2021-10-11 00:34:35', 7),
(1386, 9, 25, 1, NULL, '2021-10-11 00:34:35', '2021-10-11 00:34:35', 7),
(1387, 9, 26, 3, NULL, '2021-10-11 00:34:35', '2021-10-11 00:34:35', 7),
(1388, 9, 27, 1, NULL, '2021-10-11 00:34:35', '2021-10-11 00:34:35', 7);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', NULL, NULL, NULL),
(2, 'User', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` int(11) NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(4, 1),
(3, 2),
(7, 1),
(7, 2),
(1, 1),
(2, 2),
(8, 2),
(4, 2),
(10, 1),
(9, 2),
(11, 1),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('fqzkkHe1GjBHDeoBQXtlpGi16fcQciOtsk5nDcjx', 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 Edg/94.0.992.38', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoicGp2eTFnYVFGSlVpenVwTDhJMkRjZVNZRm1IdUR1RkxWRFh1Zzk1OSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vdXNlcnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMDtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEwJGthZ1dKNW45UDlEbHZJR2ZnWkRWUU9ON2xxdGZTNERUQko3TEZLUjBpMjE1cnVadll2d3ZpIjtzOjIxOiJwYXNzd29yZF9oYXNoX3NhbmN0dW0iO3M6NjA6IiQyeSQxMCRrYWdXSjVuOVA5RGx2SUdmZ1pEVlFPTjdscXRmUzREVEJKN0xGS1IwaTIxNXJ1WnZZdnd2aSI7fQ==', 1633951970),
('qfVUCCFCmHxFEcyM5lxoSjJDrq013eJKovPyyDvq', 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 Edg/94.0.992.38', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoicFdrcGFYdmF3cFFTYkJMeDlxN2t4VmU4UkhObUNzdEZNNmdwTkhWYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9yZXN1bHRzL2luY29ycmVjdHMvaWQ/MT0iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMDtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEwJGthZ1dKNW45UDlEbHZJR2ZnWkRWUU9ON2xxdGZTNERUQko3TEZLUjBpMjE1cnVadll2d3ZpIjtzOjIxOiJwYXNzd29yZF9oYXNoX3NhbmN0dW0iO3M6NjA6IiQyeSQxMCRrYWdXSjVuOVA5RGx2SUdmZ1pEVlFPTjdscXRmUzREVEJKN0xGS1IwaTIxNXJ1WnZZdnd2aSI7fQ==', 1633940383),
('SrCT3MXb6MJ2RX0NDpWcCCgidZAwVIQ6630IXPmO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVjA0R3FwektZWUNZSmFMNHB3dm1MZDdCNnVkOFBMY0x5TGNHZXc3SSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1633952096),
('TO0yxzToufpH4YH0celJssEAlKVDecTvdD0VJ6OC', 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 Edg/94.0.992.38', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiWFZrdWFibGF6dXI4ZTlCM29UczllMVJzVjZXamJaaVpRTURNOTNHQSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vdXNlcnMvY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTA7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMCRrYWdXSjVuOVA5RGx2SUdmZ1pEVlFPTjdscXRmUzREVEJKN0xGS1IwaTIxNXJ1WnZZdnd2aSI7czoyMToicGFzc3dvcmRfaGFzaF9zYW5jdHVtIjtzOjYwOiIkMnkkMTAka2FnV0o1bjlQOURsdklHZmdaRFZRT043bHF0ZlM0RFRCSjdMRktSMGkyMTVydVp2WXZ3dmkiO30=', 1634012986),
('uqfF93RGFB7FEGYVP9fMuCa2ub16IQo63eOpAUvK', 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 Edg/94.0.992.38', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiSmR0Nm1yaWhzVXdNZzFYUTltMnJJN21lZzE4RTBXT2RHZ0ZBQW5WaSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMDtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEwJGthZ1dKNW45UDlEbHZJR2ZnWkRWUU9ON2xxdGZTNERUQko3TEZLUjBpMjE1cnVadll2d3ZpIjtzOjIxOiJwYXNzd29yZF9oYXNoX3NhbmN0dW0iO3M6NjA6IiQyeSQxMCRrYWdXSjVuOVA5RGx2SUdmZ1pEVlFPTjdscXRmUzREVEJKN0xGS1IwaTIxNXJ1WnZZdnd2aSI7fQ==', 1634000143);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`, `role_id`) VALUES
(3, 'Drishya', 'drish@gmail.com', NULL, '$2y$10$4gBRWQU2top.q9xN4kMk/.CU4IHZZ2Ws35jqoBTCkw4vC7cD0pFiC', NULL, NULL, NULL, NULL, NULL, '2021-09-03 01:38:35', '2021-09-03 01:38:35', 2),
(9, 'Devna', 'san007@jamescookps.vic.edu.au', NULL, '$2y$10$hMabK0d5Qr13e.TVWOk70.3hZ6IvzZQ6ZCYe/BJa2P/Y0fhj6Pqom', NULL, NULL, NULL, NULL, 'profile-photos/xdOaHwWbYYTHSYEbvMO75LfcG9hs8zr6wn3K4yiZ.jpg', '2021-09-12 21:05:25', '2021-09-13 14:21:16', 0),
(10, 'Admin', 'admin@gmail.com', NULL, '$2y$10$kagWJ5n9P9DlvIGfgZDVQON7lqtfS4DTBJ7LFKR0i215ruZvYvwvi', NULL, NULL, NULL, NULL, NULL, '2021-09-13 03:01:57', '2021-09-13 03:01:57', 1),
(11, 'Devna', 'sanjithdevna@gmail.com', NULL, '$2y$10$c5MIeq.t7AzYhNZ1MxoFtuJA/748XCqhYMQaxfErvu3M86beBe4OK', NULL, NULL, NULL, NULL, NULL, '2021-09-13 14:17:57', '2021-09-13 14:17:57', 0),
(18, 'Test', 'test@gmail.com', NULL, '$2y$10$voflSj3WUPUR/6TwwSgLZeblFukQeCjAxoNLDtkvysFnU5/kX9.xu', NULL, NULL, NULL, NULL, NULL, '2021-09-28 21:28:52', '2021-09-28 21:28:52', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `options_question_id_foreign` (`question_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1389;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
