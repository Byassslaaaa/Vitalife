-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2025 at 08:29 AM
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
-- Database: `mainvita`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_requests`
--

CREATE TABLE `admin_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `spa_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `service_price` int(11) NOT NULL,
  `admin_fee` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `service_address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','expired') NOT NULL DEFAULT 'pending',
  `midtrans_transaction_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_services`
--

CREATE TABLE `booking_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('admin_notification_1', 'a:3:{s:15:\"conversation_id\";i:1;s:12:\"user_message\";s:3:\"asu\";s:9:\"timestamp\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2025-07-09 14:19:36.664655\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}}', 1752074376),
('weather_data', 'N;', 1752072584);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_conversations`
--

CREATE TABLE `chat_conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `category` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_conversations`
--

INSERT INTO `chat_conversations` (`id`, `user_id`, `status`, `category`, `created_at`, `updated_at`) VALUES
(1, 1, 'active', 'Facilities & Accommodations', '2025-07-09 07:18:53', '2025-07-09 07:19:13');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `message` text NOT NULL,
  `sender_type` varchar(255) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `conversation_id`, `user_id`, `admin_id`, `message`, `sender_type`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'fasilitas apa saja yang saya dapatkan', 'user', 1, '2025-07-09 07:19:13', '2025-07-09 07:19:47'),
(2, 1, NULL, NULL, 'Vitalife memiliki fasilitas lengkap termasuk spa, studio yoga, ruang konsultasi kesehatan, dan area event. Semua fasilitas dirancang untuk memberikan pengalaman wellness terbaik. Admin akan membantu Anda dengan detail fasilitas yang Anda butuhkan.', 'ai', 1, '2025-07-09 07:19:13', '2025-07-09 07:19:13'),
(3, 1, 1, NULL, 'fasilioyast apaa', 'user', 1, '2025-07-09 07:19:24', '2025-07-09 07:19:47'),
(4, 1, NULL, NULL, 'Untuk informasi fasilitas dan akomodasi Vitalife, admin akan membantu Anda dengan detail yang Anda butuhkan.', 'ai', 1, '2025-07-09 07:19:24', '2025-07-09 07:19:24'),
(5, 1, 1, NULL, 'asu', 'user', 1, '2025-07-09 07:19:36', '2025-07-09 07:19:47'),
(6, 1, NULL, NULL, 'Untuk informasi fasilitas dan akomodasi Vitalife, admin akan membantu Anda dengan detail yang Anda butuhkan.', 'ai', 1, '2025-07-09 07:19:36', '2025-07-09 07:19:36'),
(7, 1, NULL, 1, 'goblog', 'admin', 1, '2025-07-09 07:20:00', '2025-07-09 07:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `detail_page_templates`
--

CREATE TABLE `detail_page_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `config_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`config_data`)),
  `preview_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id_event` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `tanggal` date NOT NULL,
  `harga` int(11) NOT NULL,
  `alamat` text NOT NULL,
  `noHP` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gyms`
--

CREATE TABLE `gyms` (
  `id_gym` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`services`)),
  `is_open` tinyint(1) NOT NULL DEFAULT 1,
  `fasilitas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fasilitas`)),
  `description` text DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `maps` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gyms`
--

INSERT INTO `gyms` (`id_gym`, `nama`, `alamat`, `services`, `is_open`, `fasilitas`, `description`, `contact_person`, `contact_phone`, `image`, `maps`, `created_at`, `updated_at`) VALUES
(1, 'Andreago Training Camp', 'Jalan KH Wahid Hasyim 7 55262 Kota Yogyakarta Daerah Istimewa Yogyakarta', '[{\"name\":\"Personal Training\",\"description\":\"Layanan pelatihan pribadi yang dirancang khusus sesuai dengan kebutuhan dan tujuan kebugaran Anda. Personal trainer profesional akan mendampingi setiap sesi latihan untuk memastikan teknik yang tepat dan hasil yang optimal.\",\"image\":\"images\\/services\\/1752069837_service_0.jpg\"},{\"name\":\"Zumba Class\",\"description\":\"Kelas kebugaran dengan irama musik yang enerjik dan gerakan tari yang menyenangkan. Cocok untuk semua kalangan yang ingin membakar kalori sambil bersenang-senang dalam suasana grup yang penuh semangat.\",\"image\":\"images\\/services\\/1752069837_service_1.jpg\"},{\"name\":\"Yoga Session\",\"description\":\"Sesi yoga yang berfokus pada peningkatan fleksibilitas, keseimbangan, dan relaksasi pikiran. Dipandu oleh instruktur berpengalaman, cocok untuk semua level, dari pemula hingga lanjutan.\",\"image\":\"images\\/services\\/1752069837_service_2.jpg\"}]', 1, NULL, NULL, NULL, NULL, 'images/1752069837.jpg', NULL, '2025-07-09 07:03:57', '2025-07-09 07:03:57');

-- --------------------------------------------------------

--
-- Table structure for table `gym_bookings`
--

CREATE TABLE `gym_bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_code` varchar(255) NOT NULL,
  `gym_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_price` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `payment_token` varchar(255) DEFAULT NULL,
  `booking_date` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gym_bookings`
--

INSERT INTO `gym_bookings` (`id`, `booking_code`, `gym_id`, `customer_name`, `customer_email`, `customer_phone`, `service_id`, `service_name`, `service_price`, `status`, `payment_status`, `payment_token`, `booking_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'GYM-3HXTSQVL', 1, 'Irsyad Winarko', 'test@test.com', '08122222222', 1, 'Personal Training', 120000.00, 'pending', 'pending', '94ef462c-f369-4437-bd55-3859edb6fbb0', NULL, NULL, '2025-07-09 07:16:49', '2025-07-09 07:16:49'),
(2, 'GYM-BQBDNOQG', 1, 'Irsyad Winarko', 'test@test.com', '08122222222', 1, 'Personal Training', 120000.00, 'pending', 'pending', 'f5542f85-3ce5-464c-b02c-08e03d6e5661', NULL, NULL, '2025-07-09 09:36:00', '2025-07-09 09:36:01'),
(3, 'GYM-JD5ETOBT', 1, 'Irsyad Winarko', 'test@test.com', '08122222222', 1, 'Personal Training', 120000.00, 'pending', 'pending', '8e6ce99e-93d0-49bf-aecf-e831b3abd117', NULL, NULL, '2025-07-09 09:36:01', '2025-07-09 09:36:01'),
(4, 'GYM-USJRJU9Z', 1, 'Irsyad Winarko', 'test@test.com', '08122222222', 1, 'Personal Training', 120000.00, 'pending', 'pending', 'e52718b3-8714-4aa1-87e4-681d38fb2d14', NULL, NULL, '2025-07-09 09:50:27', '2025-07-09 09:50:27'),
(5, 'GYM-OZJX1GDB', 1, 'Irsyad Winarko', 'test@test.com', '08122222222', 1, 'Personal Training', 120000.00, 'pending', 'pending', 'acf17c4a-a3cc-418a-9882-082531c18cb6', NULL, NULL, '2025-07-09 09:51:14', '2025-07-09 09:51:14');

-- --------------------------------------------------------

--
-- Table structure for table `gym_details`
--

CREATE TABLE `gym_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gym_id` bigint(20) UNSIGNED NOT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `contact_person_name` varchar(255) DEFAULT NULL,
  `contact_person_phone` varchar(255) DEFAULT NULL,
  `location_maps` text DEFAULT NULL,
  `additional_services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_services`)),
  `about_gym` text DEFAULT NULL,
  `opening_hours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`opening_hours`)),
  `facilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`facilities`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gym_services`
--

CREATE TABLE `gym_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gym_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gym_services`
--

INSERT INTO `gym_services` (`id`, `gym_id`, `name`, `description`, `price`, `duration`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Personal Training', 'Layanan pelatihan pribadi yang dirancang khusus sesuai dengan kebutuhan dan tujuan kebugaran Anda. Personal trainer profesional akan mendampingi setiap sesi latihan untuk memastikan teknik yang tepat dan hasil yang optimal.', 120000.00, '1 bulan', 'images/services/1752070562.jpg', 1, '2025-07-09 07:16:02', '2025-07-09 07:16:02');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2023_05_10_add_google_id_to_users_table', 1),
(5, '2024_01_15_000003_create_detail_page_templates_table', 1),
(6, '2024_05_15_create_bookings_table', 1),
(7, '2024_05_20_create_chat_conversations_table', 1),
(8, '2024_05_20_create_chat_messages_table', 1),
(9, '2024_06_23_142128_create_yogas_table', 1),
(10, '2024_06_23_142129_create_yoga_detail_configs_table', 1),
(11, '2024_06_23_142142_create_spesialis_table', 1),
(12, '2024_06_29_183228_add_role_to_users_table', 1),
(13, '2024_07_07_031835_create_admins_table', 1),
(14, '2024_07_08_154226_create_notifications_table', 1),
(15, '2024_07_13_070625_create_feedback_table', 1),
(16, '2024_07_18_182836_create_vouchers_table', 1),
(17, '2024_07_20_045113_create_admin_requests_table', 1),
(18, '2024_07_23_142135_create_events_table', 1),
(19, '2025_01_01_000001_create_spas_table', 1),
(20, '2025_01_01_000002_create_spa_details_table', 1),
(21, '2025_01_01_000003_create_spa_services_table', 1),
(22, '2025_01_01_000004_create_spa_bookings_table', 1),
(23, '2025_01_01_000005_add_additional_services_to_spa_details', 1),
(24, '2025_01_01_000006_add_category_to_spa_services_table', 1),
(25, '2025_05_01_000000_create_yoga_bookings_table', 1),
(26, '2025_05_07_063629_create_payments_table', 1),
(27, '2025_05_07_165447_create_personal_access_tokens_table', 1),
(28, '2025_05_20_162233_create_yoga_booking_services_table', 1),
(29, '2025_06_14_134252_create_gyms_table', 1),
(30, '2025_06_14_134255_create_gym_details_table', 1),
(31, '2025_06_15_000001_add_is_open_to_gyms_table', 1),
(32, '2025_06_23_154234_create_booking_services_table', 1),
(33, '2025_07_14_134225_create_gym_services_table', 1),
(34, '2025_07_16_134253_create_gym_bookings_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('sIuaHUgUT9ID7DC0tmW7cxuPnmwugIqq0eDSyTg0', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMUVVYWcyOEI0ekVvQjdJY0R6Q0hLdmpmN2dwcWVyZ2RLdlFLM0dOWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9neW0vMS9zZXJ2aWNlcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MjU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9neW0iO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1752079957);

-- --------------------------------------------------------

--
-- Table structure for table `spas`
--

CREATE TABLE `spas` (
  `id_spa` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `noHP` varchar(255) NOT NULL,
  `waktuBuka` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`waktuBuka`)),
  `maps` text DEFAULT NULL,
  `services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`services`)),
  `image` varchar(255) NOT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spa_bookings`
--

CREATE TABLE `spa_bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_code` varchar(255) NOT NULL,
  `spa_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_price` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `payment_token` varchar(255) DEFAULT NULL,
  `booking_date` datetime DEFAULT NULL,
  `booking_time` time DEFAULT NULL,
  `therapist_preference` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spa_details`
--

CREATE TABLE `spa_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `spa_id` bigint(20) UNSIGNED NOT NULL,
  `hero_title` varchar(255) DEFAULT NULL,
  `hero_subtitle` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `about_spa` longtext DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `facilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`facilities`)),
  `additional_services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_services`)),
  `custom_css` longtext DEFAULT NULL,
  `show_facilities` tinyint(1) NOT NULL DEFAULT 1,
  `show_opening_hours` tinyint(1) NOT NULL DEFAULT 1,
  `show_booking_policy` tinyint(1) NOT NULL DEFAULT 1,
  `show_location_map` tinyint(1) NOT NULL DEFAULT 1,
  `booking_policy_title` varchar(255) DEFAULT NULL,
  `booking_policy_subtitle` varchar(255) DEFAULT NULL,
  `contact_person_name` varchar(255) DEFAULT NULL,
  `contact_person_phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spa_services`
--

CREATE TABLE `spa_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `spa_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spesialis`
--

CREATE TABLE `spesialis` (
  `id_spesialis` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `spesialisasi` varchar(255) NOT NULL,
  `tempatTugas` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `noHP` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `google_id` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `role`, `google_id`, `password`, `image`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@test.com', '2025-07-09 06:55:54', 'admin', NULL, '$2y$12$xhcK9IAw1wWOwFuKNqjZReT/WG4ustUmN13VSPeesmBLMKL8szdne', NULL, 'AIukUlUlOp', '2025-07-09 06:55:54', '2025-07-09 06:55:54');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `discount_percentage` int(11) NOT NULL DEFAULT 0,
  `usage_count` int(11) NOT NULL DEFAULT 0,
  `usage_limit` int(11) DEFAULT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `expired_at` timestamp NULL DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yogas`
--

CREATE TABLE `yogas` (
  `id_yoga` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `noHP` varchar(255) NOT NULL,
  `waktuBuka` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`waktuBuka`)),
  `image` varchar(255) DEFAULT NULL,
  `maps` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yoga_bookings`
--

CREATE TABLE `yoga_bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_code` varchar(255) NOT NULL,
  `yoga_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `class_type` varchar(255) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_token` varchar(255) DEFAULT NULL,
  `payment_details` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yoga_booking_services`
--

CREATE TABLE `yoga_booking_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yoga_detail_configs`
--

CREATE TABLE `yoga_detail_configs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `yoga_id` bigint(20) UNSIGNED NOT NULL,
  `hero_title` varchar(255) DEFAULT NULL,
  `hero_subtitle` varchar(500) DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `facilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`facilities`)),
  `booking_policy_title` varchar(255) DEFAULT 'BOOKING POLICY',
  `booking_policy_subtitle` varchar(255) DEFAULT 'YOUR WELLNESS PLANS',
  `show_opening_hours` tinyint(1) NOT NULL DEFAULT 1,
  `show_location_map` tinyint(1) NOT NULL DEFAULT 1,
  `show_facilities` tinyint(1) NOT NULL DEFAULT 1,
  `show_booking_policy` tinyint(1) NOT NULL DEFAULT 1,
  `show_class_types` tinyint(1) NOT NULL DEFAULT 1,
  `custom_css` text DEFAULT NULL,
  `theme_color` varchar(7) NOT NULL DEFAULT '#10B981',
  `layout_style` enum('default','modern','minimal') NOT NULL DEFAULT 'default',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `admin_requests`
--
ALTER TABLE `admin_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_requests_user_id_foreign` (`user_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_order_id_unique` (`order_id`),
  ADD KEY `bookings_spa_id_booking_date_index` (`spa_id`,`booking_date`),
  ADD KEY `bookings_payment_status_index` (`payment_status`),
  ADD KEY `bookings_order_id_index` (`order_id`);

--
-- Indexes for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_services_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_conversations_user_id_foreign` (`user_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_conversation_id_foreign` (`conversation_id`),
  ADD KEY `chat_messages_user_id_foreign` (`user_id`),
  ADD KEY `chat_messages_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `detail_page_templates`
--
ALTER TABLE `detail_page_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_page_templates_type_is_active_index` (`type`,`is_active`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id_event`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gyms`
--
ALTER TABLE `gyms`
  ADD PRIMARY KEY (`id_gym`);

--
-- Indexes for table `gym_bookings`
--
ALTER TABLE `gym_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gym_bookings_booking_code_unique` (`booking_code`),
  ADD KEY `gym_bookings_gym_id_foreign` (`gym_id`),
  ADD KEY `gym_bookings_service_id_foreign` (`service_id`);

--
-- Indexes for table `gym_details`
--
ALTER TABLE `gym_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gym_details_gym_id_index` (`gym_id`);

--
-- Indexes for table `gym_services`
--
ALTER TABLE `gym_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gym_services_gym_id_index` (`gym_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `spas`
--
ALTER TABLE `spas`
  ADD PRIMARY KEY (`id_spa`);

--
-- Indexes for table `spa_bookings`
--
ALTER TABLE `spa_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `spa_bookings_booking_code_unique` (`booking_code`),
  ADD KEY `spa_bookings_spa_id_foreign` (`spa_id`),
  ADD KEY `spa_bookings_service_id_foreign` (`service_id`);

--
-- Indexes for table `spa_details`
--
ALTER TABLE `spa_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `spa_details_spa_id_unique` (`spa_id`);

--
-- Indexes for table `spa_services`
--
ALTER TABLE `spa_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spa_services_spa_id_index` (`spa_id`);

--
-- Indexes for table `spesialis`
--
ALTER TABLE `spesialis`
  ADD PRIMARY KEY (`id_spesialis`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vouchers_code_unique` (`code`);

--
-- Indexes for table `yogas`
--
ALTER TABLE `yogas`
  ADD PRIMARY KEY (`id_yoga`);

--
-- Indexes for table `yoga_bookings`
--
ALTER TABLE `yoga_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `yoga_bookings_booking_code_unique` (`booking_code`),
  ADD KEY `yoga_bookings_yoga_id_foreign` (`yoga_id`);

--
-- Indexes for table `yoga_booking_services`
--
ALTER TABLE `yoga_booking_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `yoga_booking_services_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `yoga_detail_configs`
--
ALTER TABLE `yoga_detail_configs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `yoga_detail_configs_yoga_id_unique` (`yoga_id`),
  ADD KEY `yoga_detail_configs_yoga_id_index` (`yoga_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_requests`
--
ALTER TABLE `admin_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_services`
--
ALTER TABLE `booking_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `detail_page_templates`
--
ALTER TABLE `detail_page_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id_event` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gyms`
--
ALTER TABLE `gyms`
  MODIFY `id_gym` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gym_bookings`
--
ALTER TABLE `gym_bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gym_details`
--
ALTER TABLE `gym_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gym_services`
--
ALTER TABLE `gym_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spas`
--
ALTER TABLE `spas`
  MODIFY `id_spa` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spa_bookings`
--
ALTER TABLE `spa_bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spa_details`
--
ALTER TABLE `spa_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spa_services`
--
ALTER TABLE `spa_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spesialis`
--
ALTER TABLE `spesialis`
  MODIFY `id_spesialis` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `yogas`
--
ALTER TABLE `yogas`
  MODIFY `id_yoga` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `yoga_bookings`
--
ALTER TABLE `yoga_bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `yoga_booking_services`
--
ALTER TABLE `yoga_booking_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `yoga_detail_configs`
--
ALTER TABLE `yoga_detail_configs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_requests`
--
ALTER TABLE `admin_requests`
  ADD CONSTRAINT `admin_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD CONSTRAINT `booking_services_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD CONSTRAINT `chat_conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `gym_bookings`
--
ALTER TABLE `gym_bookings`
  ADD CONSTRAINT `gym_bookings_gym_id_foreign` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`id_gym`) ON DELETE CASCADE,
  ADD CONSTRAINT `gym_bookings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `gym_services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gym_details`
--
ALTER TABLE `gym_details`
  ADD CONSTRAINT `gym_details_gym_id_foreign` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`id_gym`) ON DELETE CASCADE;

--
-- Constraints for table `gym_services`
--
ALTER TABLE `gym_services`
  ADD CONSTRAINT `gym_services_gym_id_foreign` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`id_gym`) ON DELETE CASCADE;

--
-- Constraints for table `spa_bookings`
--
ALTER TABLE `spa_bookings`
  ADD CONSTRAINT `spa_bookings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `spa_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `spa_bookings_spa_id_foreign` FOREIGN KEY (`spa_id`) REFERENCES `spas` (`id_spa`) ON DELETE CASCADE;

--
-- Constraints for table `spa_details`
--
ALTER TABLE `spa_details`
  ADD CONSTRAINT `spa_details_spa_id_foreign` FOREIGN KEY (`spa_id`) REFERENCES `spas` (`id_spa`) ON DELETE CASCADE;

--
-- Constraints for table `spa_services`
--
ALTER TABLE `spa_services`
  ADD CONSTRAINT `spa_services_spa_id_foreign` FOREIGN KEY (`spa_id`) REFERENCES `spas` (`id_spa`) ON DELETE CASCADE;

--
-- Constraints for table `yoga_bookings`
--
ALTER TABLE `yoga_bookings`
  ADD CONSTRAINT `yoga_bookings_yoga_id_foreign` FOREIGN KEY (`yoga_id`) REFERENCES `yogas` (`id_yoga`) ON DELETE CASCADE;

--
-- Constraints for table `yoga_booking_services`
--
ALTER TABLE `yoga_booking_services`
  ADD CONSTRAINT `yoga_booking_services_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `yoga_bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `yoga_detail_configs`
--
ALTER TABLE `yoga_detail_configs`
  ADD CONSTRAINT `yoga_detail_configs_yoga_id_foreign` FOREIGN KEY (`yoga_id`) REFERENCES `yogas` (`id_yoga`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
