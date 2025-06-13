-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 05:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `name`, `surname`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Dren', 'Nitaj', 'drennitaj', 'contact@email.com', '$2y$10$KPJPGmTQqTP4iIV7aYyMhey2nNo9CQj1PIE9cfQKNiu4mSpy8rhK2', '2024-07-03 20:18:41'),
(2, 'Admin', 'Admin', 'adminadmin', 'contact@gmail.com', '$2a$12$J7yRsExGmT8V8M2mZdURTOZ6akWpM3Hiy8IlAJRQNehf/nSKeO5jq', '2024-07-29 13:17:25');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `author_name` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `category_id`, `author_name`, `price`, `stock_quantity`, `description`, `cover_image_url`, `created_at`) VALUES
(1, 'Harry Potter and the Sorcerer\'s Stone', 4, 'J.K. Rowling', 11.00, 139, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'harry_potter_1.jpg', '2024-08-04 17:35:21'),
(2, 'Harry Potter and the Chamber of Secrets', 4, 'J.K. Rowling', 11.00, 19, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'harry_potter_2.jpg', '2024-08-04 17:45:05'),
(3, 'Harry Potter and the Prisoner of Azkaban', 4, 'J.K. Rowling', 11.00, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'harry_potter_3.jpg', '2024-08-04 17:45:58'),
(4, 'Harry Potter and the Goblet of Fire', 4, 'J.K. Rowling', 11.00, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'harry_potter_4.jpg', '2024-08-04 17:46:57'),
(5, 'Harry Potter and the Order of the Phoenix', 4, 'J.K. Rowling', 11.00, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'harry_potter_5.jpg', '2024-08-04 17:48:22'),
(6, 'Harry Potter and the Half-Blood Prince', 4, 'J.K. Rowling', 11.00, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'harry_potter_6.jpg', '2024-08-04 17:49:41'),
(7, 'Harry Potter and the Deathly Hallows', 4, 'J.K. Rowling', 11.00, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'harry_potter_7.jpg', '2024-08-04 17:50:51'),
(8, 'Harry Potter and the Cursed Child', 4, 'J.K. Rowling', 11.00, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'harry_potter_8.jpg', '2024-08-04 17:52:45'),
(9, 'Charlotte\'s Web', 4, 'E.B. White', 10.40, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'charlottes_web.jpg', '2024-08-04 17:55:21'),
(10, 'Hamlet', 7, 'William Shakespeare', 3.90, 1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'hamlet.jpg', '2024-08-04 17:57:22'),
(11, 'Death of a Salesman', 7, 'Arthur Miller', 11.65, 19, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'death_of_a_salesman.jpg', '2024-08-04 17:59:27'),
(12, 'The Republic', 9, 'Plato', 14.20, 17, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'the_republic.jpg', '2024-08-04 18:06:21'),
(13, 'Why We Sleep', 9, 'Matthew Walker', 14.00, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'why_we_sleep.jpg', '2024-08-04 18:09:07'),
(14, 'Pride and Prejudice', 1, 'Jane Austen', 3.90, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'pride_and_prejudice.jpg', '2024-08-04 18:12:58'),
(15, 'The Underground Railroad', 1, 'Colson Whitehead', 11.99, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'the_underground_railroad.jpg', '2024-08-04 18:16:09'),
(16, 'Persepolis Ⅰ', 5, 'Marjane Satrapi', 7.20, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'persepolis_1.jpg', '2024-08-04 18:21:41'),
(17, 'Persepolis Ⅱ', 5, 'Marjane Satrapi', 7.00, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'persepolis_2.jpg', '2024-08-04 18:22:20'),
(18, 'Batman: The Dark Knight Returns', 5, 'Frank Miller', 15.89, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'batman_the_dark_knight_returns.jpg', '2024-08-04 18:26:23'),
(19, 'The Diary of a Young Girl', 2, 'Anne Frank', 18.40, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'the_diary_of_anne_frank.jpg', '2024-08-05 14:30:47'),
(20, 'Born a Crime', 2, 'Trevor Noah', 14.00, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'born_a_crime.jpg', '2024-08-05 14:32:11'),
(21, 'Illiad', 6, 'Homer', 12.90, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'iliad.jpg', '2024-08-05 14:35:19'),
(22, 'Odyssey', 6, 'Homer', 13.00, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'odyssey.jpg', '2024-08-05 14:37:23'),
(23, 'The Tao Te Ching', 8, 'Lao Tzu', 25.90, 18, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'tao_te_ching.jpg', '2024-08-05 14:40:54'),
(24, 'The Power of Now: A Guide to Spiritual Enlightenment', 8, 'Eckhart Tolle', 16.90, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'the_power_of_now.jpg', '2024-08-05 14:42:48'),
(25, 'Anne of Green Gables', 3, 'Lucy Maud Montgomery', 3.90, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'anne_of_green_gables.jpg', '2024-08-05 14:46:00'),
(26, 'Children of Blood and Bone', 3, 'Tomi Adeyemi', 19.40, 21, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'children_of_blood_and_bone.jpg', '2024-08-05 14:50:46'),
(33, 'The Waste Land', 6, 'T. S. Eliot', 5.20, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'the_waste_land.jpg', '2024-11-16 11:54:19'),
(34, 'Madame Bovary', 7, 'Gustave Flaubert', 3.90, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'madame_bovary.jpg', '2024-11-16 12:00:57'),
(35, 'The Night Circus', 7, 'Erin Morgenstern', 13.99, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'the_night_circus.jpg', '2024-11-16 13:14:36'),
(36, 'The Brothers Karamazov', 1, 'Fyodor Dostoyevsky', 5.20, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'the_brothers_karamazov.jpg', '2024-11-16 16:06:32'),
(37, 'Kosovo A Short History', 9, ' Noel Malcolm', 27.00, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'kosovo_a_short_story.jpg', '2024-11-16 16:17:26'),
(38, 'Christmas Carol', 1, 'Charles Dickens', 5.20, 20, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'a_christmas_carol.jpg', '2024-11-16 16:30:55');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_item_id`, `cart_id`, `book_id`, `added_time`) VALUES
(49, 3, 2, '2024-09-12 10:15:23'),
(156, 4, 1, '2024-11-09 13:26:19'),
(159, 5, 2, '2024-11-16 13:39:02'),
(183, 1, 1, '2025-06-12 17:32:33');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(1, 'Fiction'),
(2, 'Non-Fiction'),
(3, 'Young Adult'),
(4, 'Children\'s'),
(5, 'Graphic Novels & Comics'),
(6, 'Poetry'),
(7, 'Drama & Plays'),
(8, 'Religious & Spiritual'),
(9, 'Educational & Academic'),
(10, 'Additional Categories');

-- --------------------------------------------------------

--
-- Table structure for table `deleted_cart_items`
--

CREATE TABLE `deleted_cart_items` (
  `deleted_cart_item_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `cart_item_id` int(11) DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deleted_cart_items`
--

INSERT INTO `deleted_cart_items` (`deleted_cart_item_id`, `user_id`, `book_id`, `cart_item_id`, `deleted_at`) VALUES
(1, 1, 20, NULL, '2024-09-03 14:45:06'),
(2, 1, 20, NULL, '2024-09-03 14:48:21'),
(3, 1, 20, NULL, '2024-09-03 14:51:50'),
(4, 1, 4, NULL, '2024-09-03 14:53:33'),
(5, 1, 4, NULL, '2024-09-05 08:01:41'),
(6, 1, 4, NULL, '2024-09-05 08:03:59'),
(7, 1, 10, NULL, '2024-09-05 08:10:05'),
(8, 1, 18, NULL, '2024-09-05 17:50:07'),
(9, 1, 10, NULL, '2024-09-05 17:54:34'),
(10, 1, 10, NULL, '2024-09-05 18:02:13'),
(11, 1, 25, NULL, '2024-09-08 13:46:48'),
(12, 1, 26, NULL, '2024-09-08 15:07:59'),
(13, 1, 26, NULL, '2024-09-08 15:10:26'),
(14, 1, 4, NULL, '2024-09-08 15:46:13'),
(15, 1, 10, NULL, '2024-09-08 15:46:17'),
(16, 1, 10, NULL, '2024-09-10 08:30:53'),
(17, 1, 10, NULL, '2024-09-10 09:10:51'),
(18, 1, 10, NULL, '2024-09-10 09:14:00'),
(19, 1, 11, NULL, '2024-09-12 10:32:43'),
(20, 1, 10, NULL, '2024-09-12 11:00:08'),
(21, 1, 8, NULL, '2024-09-17 14:52:46'),
(22, 1, 2, NULL, '2024-10-12 12:39:05'),
(23, 1, 2, NULL, '2024-10-19 12:08:10'),
(24, 1, 1, NULL, '2024-10-25 18:39:34'),
(25, 1, 2, NULL, '2024-11-16 11:59:13'),
(26, 8, 1, NULL, '2024-11-16 13:39:09'),
(27, 1, 2, NULL, '2024-11-16 13:43:36'),
(28, 1, 1, NULL, '2025-05-18 13:21:10'),
(29, 1, 10, NULL, '2025-05-22 20:05:48'),
(30, 1, 6, NULL, '2025-05-22 20:07:21'),
(31, 1, 1, NULL, '2025-05-22 20:19:52'),
(32, 1, 1, NULL, '2025-05-24 18:37:14'),
(33, 1, 1, NULL, '2025-05-24 18:41:39'),
(34, 1, 1, NULL, '2025-05-24 18:46:34'),
(35, 1, 1, NULL, '2025-05-25 13:01:46'),
(36, 1, 1, NULL, '2025-06-09 15:59:42'),
(37, 9, 1, NULL, '2025-06-09 16:11:33'),
(38, 1, 1, NULL, '2025-06-09 16:34:47');

-- --------------------------------------------------------

--
-- Table structure for table `deleted_wishlist_items`
--

CREATE TABLE `deleted_wishlist_items` (
  `deleted_wishlist_item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `wishlist_item_id` int(11) NOT NULL,
  `removed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deleted_wishlist_items`
--

INSERT INTO `deleted_wishlist_items` (`deleted_wishlist_item_id`, `user_id`, `book_id`, `wishlist_item_id`, `removed_at`) VALUES
(1, 1, 10, 0, '2024-09-05 07:46:23'),
(2, 1, 10, 0, '2024-09-05 08:01:33'),
(3, 1, 10, 0, '2024-09-05 08:09:58'),
(4, 1, 10, 0, '2024-09-05 08:10:44'),
(5, 1, 23, 0, '2024-09-05 08:10:50'),
(6, 1, 1, 0, '2024-09-05 08:23:01'),
(7, 1, 1, 0, '2024-09-05 08:24:06'),
(8, 1, 1, 0, '2024-09-05 08:24:23'),
(9, 1, 10, 0, '2024-09-05 18:07:34'),
(10, 1, 10, 0, '2024-09-06 13:51:11'),
(11, 1, 26, 0, '2024-09-08 15:08:00'),
(12, 2, 10, 0, '2024-09-10 09:10:20'),
(13, 2, 10, 0, '2024-09-10 09:10:27'),
(14, 1, 10, 0, '2024-09-10 09:12:50'),
(15, 1, 9, 0, '2024-10-26 11:49:11'),
(16, 1, 2, 0, '2025-03-30 18:19:35'),
(17, 1, 23, 0, '2025-03-30 18:19:49'),
(18, 1, 21, 0, '2025-05-24 14:45:59'),
(19, 1, 1, 0, '2025-06-09 15:59:31'),
(20, 1, 25, 0, '2025-06-11 15:31:36');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `purchase_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('processed','completed','failed','declined','refunded') DEFAULT 'processed',
  `delivery_method` varchar(20) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `cardholder_name` varchar(255) DEFAULT NULL,
  `encrypted_card_number` varchar(255) DEFAULT NULL,
  `encrypted_expiry_date` varchar(10) DEFAULT NULL,
  `encrypted_cvv` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`purchase_id`, `user_id`, `purchase_date`, `total_amount`, `status`, `delivery_method`, `shipping_address`, `cardholder_name`, `encrypted_card_number`, `encrypted_expiry_date`, `encrypted_cvv`) VALUES
(1, 1, '2025-06-10 20:54:16', 11.00, 'refunded', 'standard', 'Kosove', 'Filan Fisteku', 'MTIzNDU2Nzg5MDEyMw==', 'MDcvMjU=', 'MDAwMA=='),
(2, 1, '2025-06-11 12:24:58', 8.89, 'completed', 'express', 'Prishtine', 'Filan Fisteku', 'MTIzNDU2Nzg5MDEyMzQ1', 'MDgvMjU=', 'MDAwMQ=='),
(3, 1, '2025-06-12 11:46:09', 51.80, 'completed', 'standard', 'Prishtine', 'Filan Fisteku', 'MTIzNDU2Nzg5MDEyMzQ1', 'MTIvMjU=', 'MDAwMg=='),
(6, 1, '2025-06-12 12:32:44', 0.00, 'failed', 'express', 'Prishtine', 'Filan Fisteku', 'NjQ5ODQ4NTE1NTQxNDU=', 'MDEvMjU=', 'MDAwMw=='),
(7, 1, '2025-06-12 17:34:16', 0.00, 'failed', 'standard', 'Kosove', 'Filan Fisteku', 'NTQ5NDk2NDQxODY0NTg0', 'MDEvMjU=', 'MDAwNA=='),
(8, 1, '2025-06-12 17:37:02', 47.59, 'refunded', 'express', 'Prishtine', 'Filan Fisteku', 'MTMxNTE4NDgxNTQxMjE=', 'MDgvMjU=', 'MDAwNQ==');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `purchase_item_id` int(11) NOT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`purchase_item_id`, `purchase_id`, `book_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 11.00),
(2, 2, 10, 1, 3.90),
(3, 3, 23, 2, 25.90),
(4, 6, 36, 1, 5.20),
(5, 7, 1, 1, 11.00),
(6, 8, 12, 3, 14.20);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `book_id`, `user_id`, `comment`, `review_date`) VALUES
(12, 2, 3, 'Darker and more mysterious than its predecessor, this installment delves deeper into the wizarding world’s lore with an engaging plot and a twisty mystery.', '2024-09-13 20:18:06'),
(16, 4, 8, 'An enchanting story full of magic, friendship, and adventure. A must-read for all ages!', '2024-11-16 13:38:04'),
(22, 3, 1, 'Darker and more mysterious than its predecessor, this installment delves deeper into the wizarding world’s lore with an engaging plot and a twisty mystery.', '2025-06-11 19:26:32'),
(23, 3, 2, 'Darker and more mysterious than its predecessor, this installment delves deeper into the wizarding world’s lore with an engaging plot and a twisty mystery.', '2025-06-11 19:26:52'),
(24, 1, 1, 'An enchanting story full of magic, friendship, and adventure. A must-read for all ages!', '2025-06-12 17:29:42');

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shopping_cart`
--

INSERT INTO `shopping_cart` (`cart_id`, `user_id`, `created_at`) VALUES
(1, 1, '2024-08-18 09:02:35'),
(2, 2, '2024-08-18 11:18:10'),
(3, NULL, '2024-08-25 13:20:56'),
(4, 3, '2024-11-09 13:26:19'),
(5, 8, '2024-11-16 13:38:58'),
(6, 9, '2025-06-09 16:11:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `surname`, `username`, `email`, `password`, `phone_number`, `address`, `created_at`, `last_login`) VALUES
(1, 'Dren', 'Nitaj', 'drennitaj1', 'contact@email.com', '$2y$10$ZmbiKsMJKIiLvxyS4W6RdOBtGtv83ckqh.4N4aWAuM/QEQBDWz542', '044123456', 'Kosovë / Prishtinë / Rruga C', '2024-07-03 19:23:58', '2025-06-12 20:06:43'),
(2, 'Dren', 'Nitaj', 'drennitaj2', 'contact@gmail.com', '$2y$10$L9A.ekF7pBTHWE67uVYsi.Crlol.oXJd.9mgib7b/qUbL.RQofnB.', '049123456', 'Kosovë / Prishtinë / Rruga B', '2024-07-03 19:37:02', '2025-06-11 22:47:09'),
(3, 'User', 'User', 'user1', 'user@email.com', '$2y$10$0GZsEeH6A3ZfbonxseHjIOKcjrGEinh8UA5e9iMpg8O7ayygKcEem', '', '', '2024-08-20 13:43:39', '2024-11-09 14:26:09'),
(4, 'User', 'User', 'user2', 'user@gmail.com', '$2y$10$nzc5krR2m4Jr8FOTVsM.BOW5bM1RqeA/CsIuJSWD2kkl/0vMSlxtS', '', '', '2024-08-30 18:12:07', NULL),
(5, 'User', 'User', 'user3', 'user@hotmail.com', '$2y$10$mfL1kBp3YiAXSAUlpbjmU.u8aLj/JhnNDxegzu7yxEWoDpQV/heme', '', '', '2024-08-30 18:15:37', NULL),
(6, 'User', 'User', 'user4', 'test@email.com', '$2y$10$K1y78OHJfhvOqyRnaHwmLOvxaEZBNdmu7a.L0Y6FYGwEWGba14vDy', '', '', '2024-11-16 10:16:16', NULL),
(7, 'User', 'User', 'user5', 'test@hotmail.com', '$2y$10$P9YXMYvmvRRzwhCyL3f5sOgR/GFP1l2e6CtKvvh6Jf5Gnhsgev2v6', '', '', '2024-11-16 10:18:30', NULL),
(8, 'User', 'User', 'user6', 'test@gmail.com', '$2y$10$iJQZX2DZnDsYoh61BvZ6FOFhtZjWCeD5rfH02p87ZHZic8EN0Dms2', '', '', '2024-11-16 13:37:39', '2024-11-16 14:37:39'),
(9, 'Festa', 'Rexhepi', 'festa', 'festa@gmail.com', '$2y$10$Mu74l.TjtdDPQ.MiRSLgO.UxE/YWbURyZgwpYYc7WyMuH/1V.My3u', '', '', '2025-06-09 16:02:36', '2025-06-09 18:02:36');

-- --------------------------------------------------------

--
-- Table structure for table `website_reviews`
--

CREATE TABLE `website_reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`wishlist_id`, `user_id`, `created_at`) VALUES
(1, 1, '2024-08-18 11:41:43'),
(2, 2, '2024-08-24 12:06:24'),
(3, 8, '2024-11-16 13:39:18'),
(4, 9, '2025-06-09 16:04:15');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_items`
--

CREATE TABLE `wishlist_items` (
  `wishlist_item_id` int(11) NOT NULL,
  `wishlist_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist_items`
--

INSERT INTO `wishlist_items` (`wishlist_item_id`, `wishlist_id`, `book_id`) VALUES
(76, 1, 10),
(77, 1, 13),
(79, 1, 22),
(82, 3, 3),
(84, 4, 6),
(85, 4, 3),
(86, 4, 7),
(87, 4, 19),
(88, 1, 37),
(89, 1, 12),
(90, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `deleted_cart_items`
--
ALTER TABLE `deleted_cart_items`
  ADD PRIMARY KEY (`deleted_cart_item_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `cart_item_id` (`cart_item_id`);

--
-- Indexes for table `deleted_wishlist_items`
--
ALTER TABLE `deleted_wishlist_items`
  ADD PRIMARY KEY (`deleted_wishlist_item_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `wishlist_item_id` (`wishlist_item_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`purchase_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`purchase_item_id`),
  ADD KEY `purchase_id` (`purchase_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `website_reviews`
--
ALTER TABLE `website_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD PRIMARY KEY (`wishlist_item_id`),
  ADD KEY `wishlist_id` (`wishlist_id`),
  ADD KEY `book_id` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `deleted_cart_items`
--
ALTER TABLE `deleted_cart_items`
  MODIFY `deleted_cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `deleted_wishlist_items`
--
ALTER TABLE `deleted_wishlist_items`
  MODIFY `deleted_wishlist_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `purchase_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `website_reviews`
--
ALTER TABLE `website_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  MODIFY `wishlist_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart` (`cart_id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `deleted_cart_items`
--
ALTER TABLE `deleted_cart_items`
  ADD CONSTRAINT `deleted_cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `deleted_cart_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `deleted_wishlist_items`
--
ALTER TABLE `deleted_wishlist_items`
  ADD CONSTRAINT `deleted_wishlist_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `deleted_wishlist_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`purchase_id`),
  ADD CONSTRAINT `purchase_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `shopping_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `website_reviews`
--
ALTER TABLE `website_reviews`
  ADD CONSTRAINT `website_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD CONSTRAINT `wishlist_items_ibfk_1` FOREIGN KEY (`wishlist_id`) REFERENCES `wishlists` (`wishlist_id`),
  ADD CONSTRAINT `wishlist_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
