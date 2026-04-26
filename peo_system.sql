-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2026 at 04:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peo_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `alumni_students`
--

CREATE TABLE `alumni_students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `batch_year` int(11) DEFAULT NULL,
  `programme` varchar(10) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `student_name` varchar(100) DEFAULT NULL,
  `student_email` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `company_name` varchar(150) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `employment_status` varchar(50) DEFAULT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `years_experience` int(11) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumni_students`
--

INSERT INTO `alumni_students` (`id`, `user_id`, `batch_year`, `programme`, `file_name`, `student_name`, `student_email`, `created_at`, `company_name`, `job_title`, `employment_status`, `industry`, `years_experience`, `profile_photo`) VALUES
(409, 7, NULL, NULL, NULL, NULL, NULL, '2026-01-11 08:24:51', NULL, NULL, NULL, NULL, NULL, NULL),
(410, 8, NULL, NULL, NULL, NULL, NULL, '2026-01-11 08:35:37', NULL, NULL, NULL, NULL, NULL, NULL),
(431, 9, NULL, NULL, NULL, NULL, NULL, '2026-01-11 08:40:33', NULL, NULL, NULL, NULL, NULL, NULL),
(672, NULL, 2021, 'BIT', NULL, 'Aisyah Balqis Binti Mohd Shamsidi', 'CI23001@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(673, NULL, 2021, 'BIT', NULL, 'Nur Aina Binti Ahmad', 'CI23002@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(674, NULL, 2021, 'BIT', NULL, 'Siti Nur Farhana Binti Ali', 'CI23003@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(675, NULL, 2021, 'BIT', NULL, 'Amirah Syafiqah Binti Zainal', 'CI23004@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(676, NULL, 2021, 'BIT', NULL, 'Nabila Huda Binti Roslan', 'CI23005@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(677, NULL, 2021, 'BIT', NULL, 'Nurul Izzah Binti Hamzah', 'AI23006@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(678, NULL, 2021, 'BIT', NULL, 'Athirah Binti Ismail', 'AI23007@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(679, NULL, 2021, 'BIT', NULL, 'Sofia Nabila Binti Hassan', 'AI23008@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(680, NULL, 2021, 'BIT', NULL, 'Puteri Balqis Binti Azman', 'AI23009@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(681, NULL, 2021, 'BIT', NULL, 'Nurin Alya Binti Khairul', 'AI23010@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(682, NULL, 2021, 'BIT', NULL, 'Muhammad Aiman Bin Zaki', 'DI23011@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(683, NULL, 2021, 'BIT', NULL, 'Muhammad Danish Bin Rahman', 'DI23012@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(684, NULL, 2021, 'BIT', NULL, 'Muhammad Amir Bin Faiz', 'DI23013@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(685, NULL, 2021, 'BIT', NULL, 'Muhammad Arif Bin Salleh', 'DI23014@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(686, NULL, 2021, 'BIT', NULL, 'Muhammad Irfan Bin Yusof', 'DI23015@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(687, NULL, 2021, 'BIT', NULL, 'Nur Syafiqah Binti Mahmud', 'CI23016@student.edu.my', '2026-01-13 14:55:39', 'ABC company', 'It clerk', 'Part-time', 'It ', 1, '69e9e8af6ae3a.jpg'),
(688, NULL, 2021, 'BIT', NULL, 'Siti Aisyah Binti Kamarul', 'CI23017@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(689, NULL, 2021, 'BIT', NULL, 'Hannah Sofea Binti Latif', 'AI23018@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(690, NULL, 2021, 'BIT', NULL, 'Zara Sofia Binti Mazlan', 'AI23019@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(691, NULL, 2021, 'BIT', NULL, 'Nur Dania Binti Fikri', 'DI23020@student.edu.my', '2026-01-13 14:55:39', NULL, NULL, NULL, NULL, NULL, NULL),
(692, NULL, 2021, 'BIW', NULL, 'Nur Aina Sofea Binti Razak', 'BIW21031@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(693, NULL, 2021, 'BIW', NULL, 'Muhammad Irfan Bin Salleh', 'BIW21032@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(694, NULL, 2021, 'BIW', NULL, 'Siti Nur Hidayah Binti Zainal', 'BIW21033@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(695, NULL, 2021, 'BIW', NULL, 'Aiman Faris Bin Hakim', 'BIW21034@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(696, NULL, 2021, 'BIW', NULL, 'Amirah Yasmin Binti Anuar', 'BIW21035@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(697, NULL, 2021, 'BIW', NULL, 'Daniel Lim Wei Sheng', 'BIW21036@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(698, NULL, 2021, 'BIW', NULL, 'Cheong Jia Hui', 'BIW21037@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(699, NULL, 2021, 'BIW', NULL, 'Loh Jun Kai', 'BIW21038@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(700, NULL, 2021, 'BIW', NULL, 'Tan Pei Ling', 'BIW21039@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(701, NULL, 2021, 'BIW', NULL, 'Ong Zi Hao', 'BIW21040@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(702, NULL, 2021, 'BIW', NULL, 'Arvind Kumar A/L Raj', 'BIW21041@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(703, NULL, 2021, 'BIW', NULL, 'Karthika Devi A/P Mani', 'BIW21042@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(704, NULL, 2021, 'BIW', NULL, 'Ramesh Kumar A/L Siva', 'BIW21043@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(705, NULL, 2021, 'BIW', NULL, 'Deepa Priya A/P Gopal', 'BIW21044@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(706, NULL, 2021, 'BIW', NULL, 'Sathish Kumar A/L Perumal', 'BIW21045@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(707, NULL, 2021, 'BIW', NULL, 'Andrew Paul Anak Lucas', 'BIW21046@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(708, NULL, 2021, 'BIW', NULL, 'Matthew John Anak Stephen', 'BIW21047@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(709, NULL, 2021, 'BIW', NULL, 'Rachel Anne Anak Thomas', 'BIW21048@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(710, NULL, 2021, 'BIW', NULL, 'Grace Olivia Anak Mark', 'BIW21049@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(711, NULL, 2021, 'BIW', NULL, 'Benjamin Luke Anak Aaron', 'BIW21050@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(712, NULL, 2021, 'BIW', NULL, 'Muhammad Syafiq Bin Kamal', 'BIW21051@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(713, NULL, 2021, 'BIW', NULL, 'Nur Izzati Binti Zulkarnain', 'BIW21052@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(714, NULL, 2021, 'BIW', NULL, 'Afiq Danish Bin Amir', 'BIW21053@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(715, NULL, 2021, 'BIW', NULL, 'Hannah Aina Binti Firdaus', 'BIW21054@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(716, NULL, 2021, 'BIW', NULL, 'Lee Jia Wen', 'BIW21055@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(717, NULL, 2021, 'BIW', NULL, 'Ng Wei Zhe', 'BIW21056@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(718, NULL, 2021, 'BIW', NULL, 'Chin Yong Xian', 'BIW21057@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(719, NULL, 2021, 'BIW', NULL, 'Suresh A/L Mahendran', 'BIW21058@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(720, NULL, 2021, 'BIW', NULL, 'Vigneshwaran A/L Subramaniam', 'BIW21059@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(721, NULL, 2021, 'BIW', NULL, 'Mary Elizabeth Anak Paul', 'BIW21060@student.edu.my', '2026-01-13 15:00:28', NULL, NULL, NULL, NULL, NULL, NULL),
(722, NULL, 2022, 'BIT', NULL, 'Aina Sofea Binti Ahmad', 'CI24001@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(723, NULL, 2022, 'BIT', NULL, 'Nur Izzati Binti Azman', 'CI24002@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(724, NULL, 2022, 'BIT', NULL, 'Siti Aisyah Binti Rahim', 'CI24003@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(725, NULL, 2022, 'BIT', NULL, 'Amirah Huda Binti Zainal', 'CI24004@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(726, NULL, 2022, 'BIT', NULL, 'Nabila Farhana Binti Rosli', 'CI24005@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(727, NULL, 2022, 'BIT', NULL, 'Puteri Balqis Binti Ismail', 'AI24006@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(728, NULL, 2022, 'BIT', NULL, 'Hannah Irdina Binti Latif', 'AI24007@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(729, NULL, 2022, 'BIT', NULL, 'Zara Alya Binti Mazlan', 'AI24008@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(730, NULL, 2022, 'BIT', NULL, 'Nur Dania Binti Fikri', 'AI24009@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(731, NULL, 2022, 'BIT', NULL, 'Sofia Nabila Binti Hassan', 'AI24010@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(732, NULL, 2022, 'BIT', NULL, 'Muhammad Aiman Bin Zaki', 'DI24011@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(733, NULL, 2022, 'BIT', NULL, 'Muhammad Danish Bin Rahman', 'DI24012@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(734, NULL, 2022, 'BIT', NULL, 'Muhammad Amir Bin Faiz', 'DI24013@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(735, NULL, 2022, 'BIT', NULL, 'Muhammad Arif Bin Salleh', 'DI24014@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(736, NULL, 2022, 'BIT', NULL, 'Muhammad Irfan Bin Yusof', 'DI24015@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(737, NULL, 2022, 'BIT', NULL, 'Nur Syafiqah Binti Mahmud', 'CI24016@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(738, NULL, 2022, 'BIT', NULL, 'Siti Nur Farhana Binti Ali', 'CI24017@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(739, NULL, 2022, 'BIT', NULL, 'Aisyah Balqis Binti Mohd Shamsidi', 'AI24018@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(740, NULL, 2022, 'BIT', NULL, 'Nur Alya Binti Khairul', 'AI24019@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(741, NULL, 2022, 'BIT', NULL, 'Athirah Binti Hamzah', 'DI24020@student.edu.my', '2026-01-13 15:00:51', NULL, NULL, NULL, NULL, NULL, NULL),
(742, NULL, 2022, 'BIW', NULL, 'Nur Athirah Binti Zulkifli', 'CI25001@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(743, NULL, 2022, 'BIW', NULL, 'Siti Nur Aisyah Binti Omar', 'CI25002@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(744, NULL, 2022, 'BIW', NULL, 'Aina Izzati Binti Mohd Noor', 'CI25003@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(745, NULL, 2022, 'BIW', NULL, 'Amirah Sofiya Binti Salleh', 'CI25004@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(746, NULL, 2022, 'BIW', NULL, 'Nabila Syahirah Binti Hamdan', 'CI25005@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(747, NULL, 2022, 'BIW', NULL, 'Puteri Alya Binti Azman', 'AI25006@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(748, NULL, 2022, 'BIW', NULL, 'Hannah Sofea Binti Latif', 'AI25007@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(749, NULL, 2022, 'BIW', NULL, 'Zara Sofia Binti Mazlan', 'AI25008@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(750, NULL, 2022, 'BIW', NULL, 'Nur Aina Binti Ahmad', 'AI25009@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(751, NULL, 2022, 'BIW', NULL, 'Sofia Balqis Binti Hassan', 'AI25010@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(752, NULL, 2022, 'BIW', NULL, 'Muhammad Haikal Bin Yusuf', 'DI25011@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(753, NULL, 2022, 'BIW', NULL, 'Muhammad Amir Bin Azman', 'DI25012@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(754, NULL, 2022, 'BIW', NULL, 'Muhammad Danish Bin Rahman', 'DI25013@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(755, NULL, 2022, 'BIW', NULL, 'Muhammad Arif Bin Salleh', 'DI25014@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(756, NULL, 2022, 'BIW', NULL, 'Muhammad Irfan Bin Zaki', 'DI25015@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(757, NULL, 2022, 'BIW', NULL, 'Nur Hidayah Binti Mahmud', 'CI25016@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(758, NULL, 2022, 'BIW', NULL, 'Siti Aisyah Binti Kamarul', 'CI25017@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(759, NULL, 2022, 'BIW', NULL, 'Aisyah Farhana Binti Zainal', 'AI25018@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(760, NULL, 2022, 'BIW', NULL, 'Nur Dania Binti Fikri', 'AI25019@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(761, NULL, 2022, 'BIW', NULL, 'Athirah Binti Ismail', 'DI25020@student.edu.my', '2026-01-13 15:01:07', NULL, NULL, NULL, NULL, NULL, NULL),
(762, NULL, 2023, 'BIT', NULL, 'Aina Syahirah Binti Ahmad', 'CI25001@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(763, NULL, 2023, 'BIT', NULL, 'Nur Farhana Binti Azman', 'CI25002@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(764, NULL, 2023, 'BIT', NULL, 'Siti Balqis Binti Rahman', 'CI25003@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(765, NULL, 2023, 'BIT', NULL, 'Amirah Izzati Binti Zainal', 'CI25004@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(766, NULL, 2023, 'BIT', NULL, 'Nabila Sofea Binti Rosli', 'CI25005@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(767, NULL, 2023, 'BIT', NULL, 'Puteri Alya Binti Ismail', 'AI25006@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(768, NULL, 2023, 'BIT', NULL, 'Hannah Nur Binti Latif', 'AI25007@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(769, NULL, 2023, 'BIT', NULL, 'Zara Nabila Binti Mazlan', 'AI25008@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(770, NULL, 2023, 'BIT', NULL, 'Nur Dania Binti Fikri', 'AI25009@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(771, NULL, 2023, 'BIT', NULL, 'Sofia Aisyah Binti Hassan', 'AI25010@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(772, NULL, 2023, 'BIT', NULL, 'Muhammad Aiman Bin Zaki', 'DI25011@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(773, NULL, 2023, 'BIT', NULL, 'Muhammad Danish Bin Rahman', 'DI25012@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(774, NULL, 2023, 'BIT', NULL, 'Muhammad Amir Bin Faiz', 'DI25013@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(775, NULL, 2023, 'BIT', NULL, 'Muhammad Arif Bin Salleh', 'DI25014@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(776, NULL, 2023, 'BIT', NULL, 'Muhammad Irfan Bin Yusof', 'DI25015@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(777, NULL, 2023, 'BIT', NULL, 'Nur Syafiqah Binti Mahmud', 'CI25016@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(778, NULL, 2023, 'BIT', NULL, 'Siti Nur Aisyah Binti Ali', 'CI25017@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(779, NULL, 2023, 'BIT', NULL, 'Aisyah Balqis Binti Mohd Noor', 'AI25018@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(780, NULL, 2023, 'BIT', NULL, 'Nur Alya Binti Khairul', 'AI25019@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(781, NULL, 2023, 'BIT', NULL, 'Athirah Binti Hamzah', 'DI25020@student.edu.my', '2026-01-13 15:01:33', NULL, NULL, NULL, NULL, NULL, NULL),
(782, NULL, 2023, 'BIW', NULL, 'Nur Irdina Binti Zulkifli', 'CI26001@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(783, NULL, 2023, 'BIW', NULL, 'Siti Aisyah Binti Omar', 'CI26002@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(784, NULL, 2023, 'BIW', NULL, 'Aina Izzati Binti Mohd Noor', 'CI26003@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(785, NULL, 2023, 'BIW', NULL, 'Amirah Sofiya Binti Salleh', 'CI26004@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(786, NULL, 2023, 'BIW', NULL, 'Nabila Syahirah Binti Hamdan', 'CI26005@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(787, NULL, 2023, 'BIW', NULL, 'Puteri Balqis Binti Azman', 'AI26006@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(788, NULL, 2023, 'BIW', NULL, 'Hannah Sofea Binti Latif', 'AI26007@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(789, NULL, 2023, 'BIW', NULL, 'Zara Sofia Binti Mazlan', 'AI26008@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(790, NULL, 2023, 'BIW', NULL, 'Nur Aina Binti Ahmad', 'AI26009@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(791, NULL, 2023, 'BIW', NULL, 'Sofia Balqis Binti Hassan', 'AI26010@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(792, NULL, 2023, 'BIW', NULL, 'Muhammad Haikal Bin Yusuf', 'DI26011@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(793, NULL, 2023, 'BIW', NULL, 'Muhammad Amir Bin Azman', 'DI26012@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(794, NULL, 2023, 'BIW', NULL, 'Muhammad Danish Bin Rahman', 'DI26013@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(795, NULL, 2023, 'BIW', NULL, 'Muhammad Arif Bin Salleh', 'DI26014@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(796, NULL, 2023, 'BIW', NULL, 'Muhammad Irfan Bin Zaki', 'DI26015@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(797, NULL, 2023, 'BIW', NULL, 'Nur Hidayah Binti Mahmud', 'CI26016@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(798, NULL, 2023, 'BIW', NULL, 'Siti Nur Aisyah Binti Kamarul', 'CI26017@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(799, NULL, 2023, 'BIW', NULL, 'Aisyah Farhana Binti Zainal', 'AI26018@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(800, NULL, 2023, 'BIW', NULL, 'Nur Dania Binti Fikri', 'AI26019@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(801, NULL, 2023, 'BIW', NULL, 'Athirah Binti Ismail', 'DI26020@student.edu.my', '2026-01-13 15:01:55', NULL, NULL, NULL, NULL, NULL, NULL),
(802, NULL, 2024, 'BIT', NULL, 'Aina Qistina Binti Ahmad', 'CI26001@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(803, NULL, 2024, 'BIT', NULL, 'Nur Izzah Binti Azman', 'CI26002@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(804, NULL, 2024, 'BIT', NULL, 'Siti Aisyah Binti Rahman', 'CI26003@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(805, NULL, 2024, 'BIT', NULL, 'Amirah Najwa Binti Zainal', 'CI26004@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(806, NULL, 2024, 'BIT', NULL, 'Nabila Hidayah Binti Rosli', 'CI26005@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(807, NULL, 2024, 'BIT', NULL, 'Puteri Alya Binti Ismail', 'AI26006@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(808, NULL, 2024, 'BIT', NULL, 'Hannah Sofea Binti Latif', 'AI26007@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(809, NULL, 2024, 'BIT', NULL, 'Zara Nabila Binti Mazlan', 'AI26008@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(810, NULL, 2024, 'BIT', NULL, 'Nur Dania Binti Fikri', 'AI26009@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(811, NULL, 2024, 'BIT', NULL, 'Sofia Balqis Binti Hassan', 'AI26010@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(812, NULL, 2024, 'BIT', NULL, 'Muhammad Aiman Bin Zaki', 'DI26011@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(813, NULL, 2024, 'BIT', NULL, 'Muhammad Danish Bin Rahman', 'DI26012@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(814, NULL, 2024, 'BIT', NULL, 'Muhammad Amir Bin Faiz', 'DI26013@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(815, NULL, 2024, 'BIT', NULL, 'Muhammad Arif Bin Salleh', 'DI26014@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(816, NULL, 2024, 'BIT', NULL, 'Muhammad Irfan Bin Yusof', 'DI26015@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(817, NULL, 2024, 'BIT', NULL, 'Nur Syafiqah Binti Mahmud', 'CI26016@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(818, NULL, 2024, 'BIT', NULL, 'Siti Nur Farhana Binti Ali', 'CI26017@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(819, NULL, 2024, 'BIT', NULL, 'Aisyah Balqis Binti Mohd Noor', 'AI26018@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(820, NULL, 2024, 'BIT', NULL, 'Nur Alya Binti Khairul', 'AI26019@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(821, NULL, 2024, 'BIT', NULL, 'Athirah Binti Hamzah', 'DI26020@student.edu.my', '2026-01-13 15:02:14', NULL, NULL, NULL, NULL, NULL, NULL),
(842, 10, NULL, NULL, NULL, NULL, NULL, '2026-01-13 15:03:23', NULL, NULL, NULL, NULL, NULL, NULL),
(843, 11, NULL, NULL, NULL, NULL, NULL, '2026-01-14 07:47:55', NULL, NULL, NULL, NULL, NULL, NULL),
(844, NULL, 2025, 'BIT', NULL, 'Aina Sofia Binti Ahmad', 'CI27001@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(845, NULL, 2025, 'BIT', NULL, 'Nur Aisyah Binti Zulkifli', 'CI27002@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(846, NULL, 2025, 'BIT', NULL, 'Siti Khadijah Binti Rahman', 'CI27003@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(847, NULL, 2025, 'BIT', NULL, 'Muhammad Aiman Bin Yusof', 'CI27004@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(848, NULL, 2025, 'BIT', NULL, 'Amirah Najwa Binti Roslan', 'CI27005@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(849, NULL, 2025, 'BIT', NULL, 'Tan Wei Jian', 'AI27006@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(850, NULL, 2025, 'BIT', NULL, 'Lim Jia Hui', 'AI27007@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(851, NULL, 2025, 'BIT', NULL, 'Ng Kok Leong', 'AI27008@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(852, NULL, 2025, 'BIT', NULL, 'Ong Zi Xuan', 'AI27009@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(853, NULL, 2025, 'BIT', NULL, 'Lee Wen Qi', 'AI27010@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(854, NULL, 2025, 'BIT', NULL, 'Arun Kumar Rajan', 'DI27011@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(855, NULL, 2025, 'BIT', NULL, 'Siva Ganesh Muthusamy', 'DI27012@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(856, NULL, 2025, 'BIT', NULL, 'Kavitha Devi Subramaniam', 'DI27013@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(857, NULL, 2025, 'BIT', NULL, 'Nithya Priya Ramanathan', 'DI27014@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(858, NULL, 2025, 'BIT', NULL, 'Vikneshwaran Arumugam', 'DI27015@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(859, NULL, 2025, 'BIT', NULL, 'Nur Izzati Binti Hamzah', 'CI27016@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(860, NULL, 2025, 'BIT', NULL, 'Puteri Alya Binti Ismail', 'AI27017@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(861, NULL, 2025, 'BIT', NULL, 'Muhammad Danish Bin Rahman', 'CI27018@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(862, NULL, 2025, 'BIT', NULL, 'Chan Mei Ling', 'AI27019@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(863, NULL, 2025, 'BIT', NULL, 'Prakash Anand', 'DI27020@student.edu.my', '2026-01-15 02:05:05', NULL, NULL, NULL, NULL, NULL, NULL),
(864, 12, NULL, NULL, NULL, NULL, NULL, '2026-01-15 02:11:48', NULL, NULL, NULL, NULL, NULL, NULL),
(865, 13, NULL, NULL, NULL, NULL, NULL, '2026-03-31 09:38:07', NULL, NULL, NULL, NULL, NULL, NULL),
(866, 14, NULL, NULL, NULL, NULL, NULL, '2026-03-31 09:39:02', NULL, NULL, NULL, NULL, NULL, NULL),
(867, 15, NULL, NULL, NULL, NULL, NULL, '2026-04-03 03:27:13', NULL, NULL, NULL, NULL, NULL, NULL),
(868, 16, NULL, NULL, NULL, NULL, NULL, '2026-04-11 16:17:51', NULL, NULL, NULL, NULL, NULL, NULL),
(869, 17, NULL, NULL, NULL, NULL, NULL, '2026-04-14 03:34:54', NULL, NULL, NULL, NULL, NULL, NULL),
(870, 18, NULL, NULL, NULL, NULL, NULL, '2026-04-14 03:53:54', NULL, NULL, NULL, NULL, NULL, NULL),
(871, 19, NULL, NULL, NULL, NULL, NULL, '2026-04-14 07:32:12', NULL, NULL, NULL, NULL, NULL, NULL),
(872, 20, NULL, NULL, NULL, NULL, NULL, '2026-04-18 04:21:09', NULL, NULL, NULL, NULL, NULL, NULL),
(873, 21, NULL, NULL, NULL, NULL, NULL, '2026-04-21 15:58:47', NULL, NULL, NULL, NULL, NULL, NULL),
(874, 22, NULL, NULL, NULL, NULL, NULL, '2026-04-22 07:26:04', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('pending','overdue','completed','alert','system') NOT NULL,
  `related_survey_id` int(11) DEFAULT NULL,
  `batch_year` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `title`, `message`, `type`, `related_survey_id`, `batch_year`, `is_read`, `created_at`) VALUES
(20, 8, 'Survey Assigned', 'PEO Survey for Batch 2021 is now available.', 'pending', 22, 2021, 0, '2026-04-13 08:02:01'),
(21, 8, 'Survey Assigned', 'PEO Survey for Batch 2022 is now available.', 'pending', 19, 2022, 0, '2026-04-13 08:07:05'),
(23, 8, 'Survey Assigned', 'PEO Survey for Batch 2025 is now available.', 'pending', 21, 2025, 1, '2026-04-13 08:07:59'),
(24, 8, 'Survey Overdue', 'Survey for Batch 2025 is overdue!', 'overdue', 21, 2025, 1, '2026-04-13 08:08:00'),
(25, 8, 'Low Response Rate', 'Overdue survey for Batch 2025 has response rate below 50%.', 'alert', 21, 2025, 1, '2026-04-13 08:08:00'),
(26, 20, 'Survey Overdue', 'Survey for Batch 2021 is overdue!', 'overdue', 22, 2021, 1, '2026-04-18 04:25:40'),
(27, 20, 'Low Response Rate', 'Overdue survey for Batch 2021 has response rate below 50%.', 'alert', 22, 2021, 0, '2026-04-18 04:25:40'),
(28, 20, 'Survey Assigned', 'PEO Survey for Batch 2021 is now available.', 'pending', 23, 2021, 0, '2026-04-18 04:26:39'),
(29, 6, 'Survey Assigned', 'PEO Survey for Batch 2022 is now available.', 'pending', 24, 2022, 1, '2026-04-21 06:17:17'),
(30, 22, 'Survey Assigned', 'PEO Survey for Batch 2023 is now available.', 'pending', 25, 2023, 1, '2026-04-22 07:27:01');

-- --------------------------------------------------------

--
-- Table structure for table `peo_plo_mapping`
--

CREATE TABLE `peo_plo_mapping` (
  `id` int(11) NOT NULL,
  `peo_code` varchar(10) DEFAULT NULL,
  `peo_description` text DEFAULT NULL,
  `plo_code` varchar(10) DEFAULT NULL,
  `plo_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peo_plo_mapping`
--

INSERT INTO `peo_plo_mapping` (`id`, `peo_code`, `peo_description`, `plo_code`, `plo_description`, `created_at`) VALUES
(24, NULL, NULL, 'PLO 1', 'Knowledge', '2026-04-07 13:35:22'),
(25, NULL, NULL, 'PLO 2', 'Team Working Skills', '2026-04-07 13:48:41'),
(37, NULL, NULL, 'PLO 3', 'Leadership Skills', '2026-04-14 03:18:26'),
(39, 'PEO 1', 'Apply fundamental knowledge, principles, and skills in the field of Computer Science / Information Technology to meet job specifications.', NULL, NULL, '2026-04-14 03:20:22'),
(40, 'PEO 2', 'Carry out responsibilities in solving problems analytically, critically, effectively, innovatively, and with market-oriented approaches.', NULL, NULL, '2026-04-14 03:24:14'),
(41, 'PEO 3', 'Function effectively as an individual or in groups to communicate information within organizations and the community.', NULL, NULL, '2026-04-14 03:24:26'),
(42, 'PEO 4', 'Practice moral values professionally and ethically within the community and demonstrate leadership qualities.', NULL, NULL, '2026-04-14 03:24:40'),
(43, 'PEO 5', 'Engage in continuous learning and professional development to adapt to technological advancements and evolving industry needs.', NULL, NULL, '2026-04-14 03:24:55'),
(44, NULL, NULL, 'PLO 4', 'Practical Skills', '2026-04-14 03:27:04'),
(45, NULL, NULL, 'PLO 5', 'Communication Skills', '2026-04-14 03:27:25'),
(46, NULL, NULL, 'PLO 6', 'Ethics and Morale', '2026-04-14 03:27:36'),
(47, 'PEO 1', 'Apply fundamental knowledge, principles, and skills in the field of Computer Science / Information Technology to meet job specifications.', 'PLO 1', 'Knowledge', '2026-04-14 03:28:06'),
(48, 'PEO 2', 'Carry out responsibilities in solving problems analytically, critically, effectively, innovatively, and with market-oriented approaches.', 'PLO 1', 'Knowledge', '2026-04-14 03:28:11'),
(49, 'PEO 2', 'Carry out responsibilities in solving problems analytically, critically, effectively, innovatively, and with market-oriented approaches.', 'PLO 2', 'Team Working Skills', '2026-04-14 03:28:11'),
(50, 'PEO 3', 'Function effectively as an individual or in groups to communicate information within organizations and the community.', 'PLO 2', 'Team Working Skills', '2026-04-14 03:28:19'),
(51, 'PEO 3', 'Function effectively as an individual or in groups to communicate information within organizations and the community.', 'PLO 3', 'Leadership Skills', '2026-04-14 03:28:19'),
(53, 'PEO 5', 'Engage in continuous learning and professional development to adapt to technological advancements and evolving industry needs.', 'PLO 5', 'Communication Skills', '2026-04-14 03:28:35'),
(54, 'PEO 5', 'Engage in continuous learning and professional development to adapt to technological advancements and evolving industry needs.', 'PLO 6', 'Ethics and Morale', '2026-04-14 03:28:35'),
(55, 'PEO 4', 'Practice moral values professionally and ethically within the community and demonstrate leadership qualities.', 'PLO 3', 'Leadership Skills', '2026-04-14 03:28:40'),
(56, 'PEO 4', 'Practice moral values professionally and ethically within the community and demonstrate leadership qualities.', 'PLO 4', 'Practical Skills', '2026-04-14 03:28:40');

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `survey_id` int(11) NOT NULL,
  `survey_title` varchar(255) NOT NULL,
  `survey_description` text DEFAULT NULL,
  `status` enum('active','inactive','closed') DEFAULT 'inactive',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surveys`
--

INSERT INTO `surveys` (`survey_id`, `survey_title`, `survey_description`, `status`, `created_by`, `created_at`) VALUES
(1, 'Graduate Outcome Survey', NULL, 'inactive', NULL, '2025-12-16 15:20:18'),
(15, 'Graduate Batch of 2020 to 2025', NULL, 'inactive', NULL, '2026-01-06 14:25:16'),
(17, 'Batch Survey of 2021', NULL, 'inactive', NULL, '2026-01-06 15:59:39'),
(19, 'Survey 2022 Batch BIW', NULL, 'inactive', NULL, '2026-01-11 07:51:03'),
(21, 'survey 2025', NULL, 'inactive', NULL, '2026-01-15 02:12:12'),
(22, 'Undergraduate Survey of 2021', NULL, 'inactive', NULL, '2026-04-13 08:00:02'),
(23, 'Graduate Outcome Survey 2021', NULL, 'inactive', NULL, '2026-04-18 04:19:36'),
(24, '2022 Programme Evaluation Survey', NULL, 'inactive', NULL, '2026-04-21 06:16:48'),
(25, 'Survey 2023', NULL, 'inactive', NULL, '2026-04-22 07:26:38');

-- --------------------------------------------------------

--
-- Table structure for table `survey_answers`
--

CREATE TABLE `survey_answers` (
  `answer_id` int(11) NOT NULL,
  `response_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_text` text DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_answers`
--

INSERT INTO `survey_answers` (`answer_id`, `response_id`, `question_id`, `answer_text`, `score`, `created_at`) VALUES
(5, 1, 55, 'Test', 4, '2026-04-11 09:07:15'),
(6, 2, 55, 'Test', 3, '2026-04-11 09:07:15'),
(7, 3, 55, 'Test', 3, '2026-04-11 09:07:15'),
(8, 1, 57, 'Test', 4, '2026-04-11 09:07:15'),
(9, 2, 57, 'Test', 4, '2026-04-11 09:07:15'),
(10, 6, 55, 'specializing in Web Development', NULL, '2026-04-14 03:30:52'),
(11, 6, 57, 'Engage in continuous learning and professional development to adapt to technological advancements and evolving industry needs.', NULL, '2026-04-14 03:31:06'),
(12, 6, 58, 'Dissatisfied', NULL, '2026-04-14 03:31:10'),
(13, 6, 59, '[\"Problem-solving\",\"Communication\"]', NULL, '2026-04-14 03:31:12'),
(14, 7, 55, 'IT/Programming', NULL, '2026-04-14 03:35:03'),
(15, 7, 57, 'Carry out responsibilities in solving problems analytically, critically and effectively', NULL, '2026-04-14 03:35:28'),
(16, 7, 58, 'Dissatisfied', NULL, '2026-04-14 03:35:32'),
(17, 7, 59, '[\"Problem-solving\",\"Teamwork\",\"Communication\"]', NULL, '2026-04-14 03:35:35'),
(18, 7, 60, '3–6 months', NULL, '2026-04-14 03:35:39'),
(19, 7, 61, '5', 5, '2026-04-14 03:35:42'),
(20, 8, 55, 'IT/Programming', NULL, '2026-04-14 03:54:00'),
(21, 8, 57, 'communicate information within organizations', NULL, '2026-04-14 03:54:33'),
(22, 8, 58, 'Very satisfied', NULL, '2026-04-14 03:54:36'),
(23, 8, 59, '[\"Problem-solving\",\"Teamwork\"]', NULL, '2026-04-14 03:54:42'),
(24, 8, 60, '6–13 months', NULL, '2026-04-14 03:54:46'),
(25, 8, 61, '5', 5, '2026-04-14 03:54:50'),
(26, 9, 55, 'specializing in Web Development', NULL, '2026-04-14 07:32:21'),
(27, 9, 57, 'Practice moral values professionally', NULL, '2026-04-14 07:32:44'),
(28, 9, 58, 'Very satisfied', NULL, '2026-04-14 07:32:46'),
(29, 9, 59, '[\"Problem-solving\",\"Teamwork\",\"Communication\"]', NULL, '2026-04-14 07:32:48'),
(30, 9, 60, '6–13 months', NULL, '2026-04-14 07:32:50'),
(35, 9, 61, '3', 3, '2026-04-14 07:34:04'),
(38, 9, 62, '{\"Communicating ideas clearly\":\"High\",\"Collaborating with others in projects\":\"Moderate\",\"Interacting professionally with others\":\"Very High\"}', NULL, '2026-04-14 07:47:51'),
(39, 9, 63, '5', 5, '2026-04-14 07:47:58'),
(40, 10, 55, 'Data Processing Assistant', NULL, '2026-04-18 04:23:04'),
(41, 10, 57, 'The programme prepared me well by building my IT knowledge and practical skills. It also helped me apply what I learned in my current role, though more industry exposure would be helpful.', NULL, '2026-04-18 04:23:48'),
(42, 10, 58, 'Very satisfied', NULL, '2026-04-18 04:23:52'),
(43, 10, 59, '[\"Problem-solving\",\"Teamwork\",\"Communication\"]', NULL, '2026-04-18 04:23:58'),
(44, 10, 60, '3–6 months', NULL, '2026-04-18 04:24:03'),
(45, 10, 61, '4', 4, '2026-04-18 04:24:09'),
(46, 10, 62, '{\"Communicating ideas clearly\":\"Moderate\",\"Collaborating with others in projects\":\"Very High\",\"Interacting professionally with others\":\"High\"}', NULL, '2026-04-18 04:24:17'),
(47, 10, 63, '5', 5, '2026-04-18 04:24:24'),
(48, 10, 70, 'Agree', NULL, '2026-04-18 04:25:04'),
(49, 11, 55, 'Data Entry Assistant', NULL, '2026-04-18 04:27:13'),
(50, 11, 57, 'The programme prepared me well by combining theory and practical skills.', NULL, '2026-04-18 04:27:39'),
(51, 11, 58, 'Dissatisfied', NULL, '2026-04-18 04:27:42'),
(52, 11, 59, '[\"Teamwork\",\"Communication\"]', NULL, '2026-04-18 04:27:47'),
(53, 11, 60, '6–13 months', NULL, '2026-04-18 04:27:51'),
(54, 11, 61, '5', 5, '2026-04-18 04:28:01'),
(55, 11, 62, '{\"Communicating ideas clearly\":\"High\",\"Collaborating with others in projects\":\"Very High\",\"Interacting professionally with others\":\"High\"}', NULL, '2026-04-18 04:28:10'),
(56, 11, 63, '5', 5, '2026-04-18 04:28:14'),
(57, 11, 70, 'Neutral', NULL, '2026-04-18 04:28:19'),
(58, 12, 55, 'Software Developer', NULL, '2026-04-21 06:19:59'),
(59, 12, 57, 'The programme prepared me well in terms of fundamental knowledge and technical skills, especially in areas such as problem-solving, programming, and system development. It also helped improve my communication and teamwork abilities through group projects.', NULL, '2026-04-21 06:20:28'),
(60, 12, 58, 'Very satisfied', NULL, '2026-04-21 06:20:32'),
(61, 12, 59, '[\"Problem-solving\",\"Communication\"]', NULL, '2026-04-21 06:20:34'),
(62, 12, 60, 'Less than 3 months', NULL, '2026-04-21 06:20:36'),
(63, 12, 61, '3', 3, '2026-04-21 06:20:39'),
(64, 12, 62, '{\"Communicating ideas clearly\":\"Moderate\",\"Collaborating with others in projects\":\"High\",\"Interacting professionally with others\":\"Very High\"}', NULL, '2026-04-21 06:20:44'),
(65, 12, 63, '5', 5, '2026-04-21 06:20:46'),
(66, 12, 70, 'Neutral', NULL, '2026-04-21 06:20:49'),
(67, 13, 55, 'IT/Programming', NULL, '2026-04-21 15:59:25'),
(68, 13, 57, 'aeeeeee', NULL, '2026-04-21 15:59:28'),
(69, 13, 58, 'Very satisfied', NULL, '2026-04-21 15:59:31'),
(70, 13, 59, '[\"Problem-solving\",\"Teamwork\"]', NULL, '2026-04-21 15:59:34'),
(71, 13, 60, 'More than 1 year', NULL, '2026-04-21 16:47:12'),
(72, 13, 61, '3', 3, '2026-04-21 16:47:15'),
(73, 14, 55, 'It clerk', NULL, '2026-04-22 07:27:12'),
(74, 14, 57, 'KMQ', NULL, '2026-04-22 07:27:15'),
(75, 14, 58, 'Very satisfied', NULL, '2026-04-22 07:27:17'),
(76, 14, 59, '[\"Teamwork\"]', NULL, '2026-04-22 07:27:20'),
(77, 14, 63, '3', 3, '2026-04-22 07:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `survey_assignments`
--

CREATE TABLE `survey_assignments` (
  `assignment_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `batch_year` varchar(10) NOT NULL,
  `due_date` date NOT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_date` date DEFAULT NULL,
  `email_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_assignments`
--

INSERT INTO `survey_assignments` (`assignment_id`, `survey_id`, `batch_year`, `due_date`, `status`, `created_at`, `assigned_date`, `email_message`) VALUES
(3, 22, '2021', '2026-04-17', 'active', '2026-04-13 08:02:01', '2026-01-01', NULL),
(4, 19, '2022', '2026-05-09', 'active', '2026-04-13 08:07:04', '2026-04-01', NULL),
(6, 21, '2025', '2026-04-12', 'active', '2026-04-13 08:07:59', '2026-03-06', NULL),
(7, 23, '2021', '2026-07-15', 'active', '2026-04-18 04:26:39', '2026-02-11', NULL),
(8, 24, '2022', '2026-05-30', 'active', '2026-04-21 06:17:17', '2026-04-21', NULL),
(9, 25, '2023', '2026-05-08', 'active', '2026-04-22 07:27:01', '2026-04-22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `survey_questions`
--

CREATE TABLE `survey_questions` (
  `question_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `peo_code` varchar(20) DEFAULT NULL,
  `peo_id` varchar(10) DEFAULT NULL,
  `alumni_id` int(11) DEFAULT NULL,
  `question_text` text NOT NULL,
  `question_type` varchar(50) NOT NULL,
  `options` text DEFAULT NULL,
  `answer_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `question_config` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_questions`
--

INSERT INTO `survey_questions` (`question_id`, `survey_id`, `peo_code`, `peo_id`, `alumni_id`, `question_text`, `question_type`, `options`, `answer_value`, `created_at`, `question_config`) VALUES
(55, 1, NULL, 'PEO 1', NULL, 'What is your current job title?', 'short', NULL, 'ry', '2025-12-31 15:08:44', NULL),
(57, 1, NULL, 'PEO 2', NULL, 'In your opinion, how well did the programme prepare you to meet industry expectations?', 'paragraph', NULL, 'fgh', '2025-12-31 15:44:57', NULL),
(58, 1, NULL, 'PEO 3', NULL, 'How satisfied are you with the overall quality of the programme?', 'mcq', NULL, 'Very satisfied', '2025-12-31 16:50:49', '{\"options\":[\"Very satisfied\",\"Dissatisfied\"]}'),
(59, 1, NULL, 'PEO 4', NULL, 'Which skills did you gain from the programme?', 'checkbox', NULL, '[\"Problem-solving\",\"Teamwork\"]', '2025-12-31 16:51:57', '{\"options\":[\"Problem-solving\",\"Teamwork\",\"Communication\"]}'),
(60, 1, NULL, 'PEO 5', NULL, 'How long did it take you to secure your first job after graduation?', 'dropdown', NULL, '6–13 months', '2025-12-31 16:52:57', '{\r\n  \"options\": [\r\n    {\"text\": \"Less than 3 months\", \"score\": 5},\r\n    {\"text\": \"3–6 months\", \"score\": 4},\r\n    {\"text\": \"6–13 months\", \"score\": 3},\r\n    {\"text\": \"More than 1 year\", \"score\": 2}\r\n  ]\r\n}'),
(61, 1, NULL, 'PEO 2', NULL, 'Rate how relevant the courses were to your current job.', 'scale', NULL, '5', '2025-12-31 17:33:47', '{\"scale_max\":\"5\",\"label_min\":\"Not relevant\",\"label_max\":\"Very relevant\"}'),
(62, 1, NULL, 'PEO 3', NULL, 'How well did the programme help you develop the following abilities?', 'grid', NULL, '{\"Teaching Quality\":\"Good\",\"Course Content\":\"Excellent\",\"Facilities\":\"Good\"}', '2025-12-31 17:38:23', '{\"rows\":[\"Communicating ideas clearly\",\"Teaching Quality\",\"Provided strong technical knowledge\"],\"columns\":[\"Very low\",\"Low\",\"Moderate\",\"High\",\"Very high\"]}'),
(63, 1, NULL, 'PEO 3', NULL, 'Rate your overall satisfaction with the programme.', 'rating', NULL, '5', '2025-12-31 17:39:13', '{\"scale_max\":\"5\"}'),
(70, 1, NULL, 'PEO 4', NULL, 'The programme prepared me well for my professional career.', 'mcq', NULL, NULL, '2026-01-13 15:22:35', '{\"options\":[\"Strongly Disagree\",\"Disagree\",\"Neutral\",\"Agree\"]}');

-- --------------------------------------------------------

--
-- Table structure for table `survey_question_map`
--

CREATE TABLE `survey_question_map` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_question_map`
--

INSERT INTO `survey_question_map` (`id`, `survey_id`, `question_id`, `created_at`) VALUES
(37, 15, 55, '2026-01-06 14:25:16'),
(38, 15, 58, '2026-01-06 14:25:16'),
(39, 15, 59, '2026-01-06 14:25:16'),
(40, 15, 61, '2026-01-06 14:25:16'),
(41, 15, 63, '2026-01-06 14:25:16'),
(50, 17, 55, '2026-01-06 15:59:39'),
(51, 17, 57, '2026-01-06 15:59:40'),
(52, 17, 58, '2026-01-06 15:59:40'),
(53, 17, 59, '2026-01-06 15:59:40'),
(54, 17, 60, '2026-01-06 15:59:40'),
(59, 19, 55, '2026-01-11 07:51:03'),
(60, 19, 57, '2026-01-11 07:51:03'),
(61, 19, 58, '2026-01-11 07:51:03'),
(62, 19, 59, '2026-01-11 07:51:03'),
(63, 19, 60, '2026-01-11 07:51:03'),
(64, 19, 61, '2026-01-11 07:51:03'),
(65, 19, 62, '2026-01-11 07:51:03'),
(66, 19, 63, '2026-01-11 07:51:03'),
(72, 21, 55, '2026-01-15 02:12:12'),
(73, 21, 58, '2026-01-15 02:12:12'),
(74, 21, 57, '2026-01-15 02:12:12'),
(75, 21, 59, '2026-01-15 02:12:12'),
(76, 22, 55, '2026-04-13 08:00:02'),
(77, 22, 57, '2026-04-13 08:00:02'),
(78, 22, 58, '2026-04-13 08:00:02'),
(79, 22, 59, '2026-04-13 08:00:02'),
(80, 22, 60, '2026-04-13 08:00:02'),
(81, 22, 61, '2026-04-13 08:00:02'),
(82, 22, 62, '2026-04-13 08:00:02'),
(83, 22, 63, '2026-04-13 08:00:02'),
(84, 22, 70, '2026-04-13 08:00:02'),
(85, 23, 55, '2026-04-18 04:19:36'),
(86, 23, 57, '2026-04-18 04:19:36'),
(87, 23, 58, '2026-04-18 04:19:36'),
(88, 23, 59, '2026-04-18 04:19:36'),
(89, 23, 60, '2026-04-18 04:19:36'),
(90, 23, 61, '2026-04-18 04:19:36'),
(91, 23, 62, '2026-04-18 04:19:36'),
(92, 23, 70, '2026-04-18 04:19:36'),
(93, 23, 63, '2026-04-18 04:19:36'),
(94, 24, 55, '2026-04-21 06:16:48'),
(95, 24, 57, '2026-04-21 06:16:48'),
(96, 24, 58, '2026-04-21 06:16:48'),
(97, 24, 59, '2026-04-21 06:16:48'),
(98, 24, 60, '2026-04-21 06:16:48'),
(99, 24, 61, '2026-04-21 06:16:48'),
(100, 24, 62, '2026-04-21 06:16:48'),
(101, 24, 63, '2026-04-21 06:16:48'),
(102, 24, 70, '2026-04-21 06:16:48'),
(103, 25, 55, '2026-04-22 07:26:38'),
(104, 25, 57, '2026-04-22 07:26:38'),
(105, 25, 58, '2026-04-22 07:26:38'),
(106, 25, 59, '2026-04-22 07:26:38'),
(107, 25, 63, '2026-04-22 07:26:38');

-- --------------------------------------------------------

--
-- Table structure for table `survey_responses`
--

CREATE TABLE `survey_responses` (
  `response_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `alumni_id` int(11) NOT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_responses`
--

INSERT INTO `survey_responses` (`response_id`, `survey_id`, `alumni_id`, `submitted_at`, `created_at`) VALUES
(1, 19, 752, '2026-03-31 18:10:09', '2026-03-31 10:10:09'),
(2, 19, 744, '2026-03-31 22:34:06', '2026-03-31 14:34:06'),
(3, 21, 862, '2026-04-03 11:50:33', '2026-04-03 03:50:33'),
(4, 19, 757, '2026-04-11 23:40:10', '2026-04-11 15:40:10'),
(5, 21, 855, '2026-04-12 00:17:54', '2026-04-11 16:17:54'),
(6, 21, 848, '2026-04-14 11:31:12', '2026-04-14 03:30:46'),
(7, 19, 755, '2026-04-14 11:34:58', '2026-04-14 03:34:58'),
(8, 19, 760, '2026-04-14 11:53:56', '2026-04-14 03:53:56'),
(9, 19, 750, '2026-04-14 15:47:58', '2026-04-14 07:32:14'),
(10, 22, 687, '2026-04-18 12:25:04', '2026-04-18 04:21:55'),
(11, 23, 687, '2026-04-18 12:28:19', '2026-04-18 04:26:53'),
(12, 24, 760, '2026-04-21 14:20:49', '2026-04-21 06:19:14'),
(13, 23, 678, '2026-04-21 23:59:22', '2026-04-21 15:59:22'),
(14, 25, 801, '2026-04-22 15:27:25', '2026-04-22 07:27:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `matric_no` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','alumni') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `survey_responses` tinyint(4) DEFAULT 1,
  `system_alerts` tinyint(4) DEFAULT 1,
  `weekly_summaries` tinyint(4) DEFAULT 1,
  `low_response` tinyint(4) DEFAULT 1,
  `language` varchar(50) DEFAULT 'English',
  `timezone` varchar(100) DEFAULT 'Asia/Kuala_Lumpur',
  `auto_assign` tinyint(4) DEFAULT 0,
  `email_reminders` tinyint(4) DEFAULT 0,
  `export_format` varchar(20) DEFAULT 'PDF',
  `full_name` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `matric_no`, `password`, `role`, `created_at`, `survey_responses`, `system_alerts`, `weekly_summaries`, `low_response`, `language`, `timezone`, `auto_assign`, `email_reminders`, `export_format`, `full_name`, `department`) VALUES
(1, 'priyangaravi83', 'priyangaravi83@gmail.com', NULL, '$2y$12$VXr3oW2nbQj2gMTw6qBK9.zesAz9OtVFDy7Y1JXbnHERjZvZFab3C', 'alumni', '2025-12-08 06:01:48', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(2, 'rupinigunalan', 'rupinigunalan@gmail.com', NULL, '$2y$12$kKuixHebTToK/l6QOOBYMO7HzN6Bdsb9HJFgHkzdsaDb4wD/9pTnm', 'alumni', '2025-12-08 06:02:09', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(3, 'sumitha12', 'sumitha12@gmail.com', NULL, '$2y$12$XUHjZi/hoSb2nC5F.Z5Bv.VSoOYaYZUqawKFLbfZlDOaYY5775K6.', 'alumni', '2025-12-08 06:08:26', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(4, 'Aarani13', 'Aarani13@gmail.com', NULL, '$2y$12$2pTW1F0Pqoc19WXkNAtrZuzQLG1B3wP36ew9m7gVUlzZPjG1vJvEK', 'alumni', '2025-12-08 06:18:07', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(5, 'mukesh27', 'mukesh27@gmail.com', NULL, '$2y$12$E7VG53tblN2uMf1MNazzRu0hJSXs3Qr6H80Ilnoz6YAyQqQMUgaTi', 'alumni', '2025-12-08 07:27:25', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(6, 'adminpeo', 'adminpeo@gmail.com', NULL, '$2y$12$t6MndxjwMmRUDXWKm.Gev.PRgcZZnnoH545urOgOqXhGiUxK.agS6', 'admin', '2025-12-15 06:57:40', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(7, 'ci230001', 'ci230001@student.uthm.edu.my', 'ci230001', '$2y$12$okrO/zS92sqETr5s68v1l.zgRMA099.VAlx4yEc7KgnhSO9Fm1qGy', 'alumni', '2026-01-05 18:21:25', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(8, '', 'CI25003@student.edu.my', NULL, '$2y$12$5Gzgygv0nMOHtChY4Uhif.2JRS6FJ5bjZkCO9DeCAnXaF26aJLi6S', 'alumni', '2026-01-11 07:49:20', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(9, '', 'CI26001@student.edu.my', NULL, '$2y$12$AvcpvGLutMJP1P3Z1KZiOuci4qQ7dWm3XFCD6jHd4hR22sjPISZ8u', 'alumni', '2026-01-11 08:40:28', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(10, '', 'DI27013@student.edu.my', NULL, '$2y$12$gHDrFtkaJ.2lUXeG9vUZC.u8QZC3Al7kjlLDXVUg8ojj7WQYTBa0y', 'alumni', '2026-01-13 15:03:19', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(11, '', 'AI23018@student.edu.my', NULL, '$2y$12$bteXsNzw.oXWehjDYPQZFeHMR4/RymNnVXbFA5BkVNJVUMo7aayjO', 'alumni', '2026-01-14 07:46:39', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(12, '', 'CI27005@student.edu.my', NULL, '$2y$12$Mm3cpaMTLFe8ArwGCdX/VujxaR36AVTSJ99he2AkV4MGBWgG9ktwq', 'alumni', '2026-01-15 02:11:42', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(13, '', 'CI25016@student.edu.my', NULL, '$2y$12$EFfjXz18crx2n14wiDBUPeoRn/fJlXFoUUe9Uhtrwe3tde.YvjVWq', 'alumni', '2026-03-31 09:38:02', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(14, '', 'DI25011@student.edu.my', NULL, '$2y$12$AnBiwUn73JJ1.2TZDlsUVuOFqQUM7pcPR81MBE1OpVZ.s7i620u3i', 'alumni', '2026-03-31 09:38:58', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(15, '', 'AI27019@student.edu.my', NULL, '$2y$12$L.R6.hYih9whgiHPhCxkiuHv62rhksN28E/aVcAeVlmEuxAkjN6e2', 'alumni', '2026-04-03 03:27:07', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(16, '', 'DI27012@student.edu.my', NULL, '$2y$12$VsvQTrfOvICIH8NHsJg4Nu0H..JxjZ71.TNEwghK5BMSHcajBaHdi', 'alumni', '2026-04-11 16:17:41', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(17, '', 'DI25014@student.edu.my', NULL, '$2y$12$1/6N7Ojb4MBVRaRljlW1fOH9Zk6C3/RMYhKiAyYvwOU.x.wChWvd6', 'alumni', '2026-04-14 03:34:48', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(18, '', 'AI25019@student.edu.my', NULL, '$2y$12$a2UArq7YzMQJdH9origp8uJr0rcIgbQAEuj01hld5NpuITfHI5736', 'alumni', '2026-04-14 03:53:48', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(19, '', 'AI25009@student.edu.my', NULL, '$2y$12$vdCMg.VIoGptjBNfbSScmO3MR9Te3VVYcvp80iYtiAykI/N9YTMMu', 'alumni', '2026-04-14 07:32:08', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(20, '', 'CI23016@student.edu.my', NULL, '$2y$12$sPzcAE1IIZMxmkA5w1Zocum3fupLD/A9XQVcAvoL0iHw1JFVe.AP.', 'alumni', '2026-04-18 04:21:03', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(21, '', 'AI23007@student.edu.my', NULL, '$2y$12$I1nv4zUPo2qYRr.38HInMe96RhXPKGqIUMvcvC9f1LyYKQR8poL/G', 'alumni', '2026-04-21 15:58:26', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL),
(22, '', 'DI26020@student.edu.my', NULL, '$2y$12$QA7H1rNeiMIbMskzAGnl9OWoMQtUQgaSNKtHLzjitGMT73mOznJ12', 'alumni', '2026-04-22 07:25:52', 1, 1, 1, 1, 'English', 'Asia/Kuala_Lumpur', 0, 0, 'PDF', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alumni_students`
--
ALTER TABLE `alumni_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_alumni_user` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `related_survey_id` (`related_survey_id`);

--
-- Indexes for table `peo_plo_mapping`
--
ALTER TABLE `peo_plo_mapping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`survey_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `survey_answers`
--
ALTER TABLE `survey_answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `response_id` (`response_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `survey_assignments`
--
ALTER TABLE `survey_assignments`
  ADD PRIMARY KEY (`assignment_id`);

--
-- Indexes for table `survey_questions`
--
ALTER TABLE `survey_questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `survey_id` (`survey_id`);

--
-- Indexes for table `survey_question_map`
--
ALTER TABLE `survey_question_map`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_id` (`survey_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `survey_responses`
--
ALTER TABLE `survey_responses`
  ADD PRIMARY KEY (`response_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alumni_students`
--
ALTER TABLE `alumni_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=875;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `peo_plo_mapping`
--
ALTER TABLE `peo_plo_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `survey_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `survey_answers`
--
ALTER TABLE `survey_answers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `survey_assignments`
--
ALTER TABLE `survey_assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `survey_questions`
--
ALTER TABLE `survey_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `survey_question_map`
--
ALTER TABLE `survey_question_map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `survey_responses`
--
ALTER TABLE `survey_responses`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alumni_students`
--
ALTER TABLE `alumni_students`
  ADD CONSTRAINT `fk_alumni_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `surveys`
--
ALTER TABLE `surveys`
  ADD CONSTRAINT `surveys_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `survey_answers`
--
ALTER TABLE `survey_answers`
  ADD CONSTRAINT `survey_answers_ibfk_1` FOREIGN KEY (`response_id`) REFERENCES `survey_responses` (`response_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `survey_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `survey_questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `survey_questions`
--
ALTER TABLE `survey_questions`
  ADD CONSTRAINT `survey_questions_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`survey_id`);

--
-- Constraints for table `survey_question_map`
--
ALTER TABLE `survey_question_map`
  ADD CONSTRAINT `survey_question_map_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`survey_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `survey_question_map_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `survey_questions` (`question_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
