-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 30, 2025 at 12:04 PM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u555781181_Deep42025`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `username`) VALUES
(5, 'admin', 'admin@gmail.com', '$2y$10$RtO6fjwYf/kCdkk4vB4rjOU1ZzE4zEBDO7cXGYcgW4vV4wjyVovTe', '2025-04-26 22:01:00', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `dob` date DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_temp_password` tinyint(1) DEFAULT 0,
  `verified` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `name`, `email`, `phone`, `dob`, `password`, `is_temp_password`, `verified`, `created_at`) VALUES
(23, 'Pooja', 'pooja@gmail.com', '9666576889', '2007-04-05', '$2y$10$YDBtZPwhtfyBBs0DgZmyH.rx7vLt0WsLw4F3eDEEpM.VyNJGPCfZC', 0, 0, '2025-04-29 12:59:58'),
(24, 'Charan', 'charan@gmail.com', '8618203345', '2007-04-01', '$2y$10$MvwMqi2R/GnydfSrbwCP3uaRIAVCGhoUIoJdIrom4.qzL7dFdjHc2', 0, 0, '2025-04-30 07:53:54');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `verified` tinyint(4) DEFAULT 0,
  `type` enum('profile_photo','aadhar','pan','resume','education','experience','relieving') NOT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp(),
  `original_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `candidate_id`, `document_path`, `verified`, `type`, `uploaded_at`, `original_name`) VALUES
(41, 23, '../uploads/documents/doc_68113e2089644.pdf', 0, 'resume', '2025-04-29 21:01:20', 'SYAM RESUME.pdf'),
(42, 23, '../uploads/documents/doc_68113e2089afc.pdf', 0, 'education', '2025-04-29 21:01:20', 'PROJECT REPORT.pdf'),
(43, 23, '../uploads/documents/doc_68113e208a9a3.pdf', 0, 'experience', '2025-04-29 21:01:20', 'Exp_Letter_ITS45036.pdf'),
(44, 23, '../uploads/documents/doc_68113e208b0b5.pdf', 0, 'relieving', '2025-04-29 21:01:20', 'Relieving_Letter_ITS45036.pdf'),
(45, 23, '../uploads/documents/doc_68113f5ec346f.pdf', 0, 'pan', '2025-04-29 21:06:38', 'Result_2525190049.pdf'),
(46, 23, '../uploads/documents/doc_681141032819e.png', 0, 'aadhar', '2025-04-29 21:13:39', 'test-icon.png'),
(49, 23, '../uploads/documents/doc_6811e1e08c16d.jpg', 0, 'profile_photo', '2025-04-30 08:40:00', '1000001618.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `offer_letters`
--

CREATE TABLE `offer_letters` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `ctc` decimal(10,2) DEFAULT NULL,
  `monthly_inhand` decimal(10,2) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `accepted` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offer_letters`
--

INSERT INTO `offer_letters` (`id`, `candidate_id`, `file_path`, `ctc`, `monthly_inhand`, `joining_date`, `accepted`) VALUES
(13, 23, '../uploads/offer_letters/681126840b0b3_Exp_Letter_ITS45031.pdf', 500000.00, 40000.00, '2025-05-07', NULL),
(14, 24, '../uploads/offer_letters/6811d8a29c2bd_Relieving_Letter_ITS45031.pdf', 1200000.00, 80000.00, '2025-05-05', 0);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text DEFAULT NULL,
  `type` enum('mcq','coding') DEFAULT NULL,
  `options` text DEFAULT NULL,
  `answer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `type`, `options`, `answer`) VALUES
(2, 'print factorial of 5', 'coding', '[\"\",\"\",\"\",\"\"]', '120'),
(3, 'Prime Minister', 'mcq', '[\"Modi\",\"Ambani\",\"Gandhi\",\"Nehru\"]', 'Modi'),
(4, 'Pushpa', 'mcq', '[\"Smuggler\",\"Dancer\",\"Actor\",\"All of the Above\"]', 'All of the Above'),
(5, 'Dragon', 'mcq', '[\"Anupama\",\"Kayadhu\",\"Pradeep\",\"All of the Above\"]', 'All of the Above'),
(6, 'Ferrari', 'mcq', '[\"Red\",\"Blue\",\"Orange\",\"Black\"]', 'Red'),
(7, 'Mercedes', 'mcq', '[\"Car\",\"Plane\",\"Auto\",\"Cycle\"]', 'Car'),
(8, 'Mars', 'mcq', '[\"Planet\",\"Car\",\"Bike\",\"Home\"]', 'Planet'),
(9, 'Sun', 'mcq', '[\"Biggest Star\",\"Smallest Star\",\"Lighter\",\"Heavier\"]', 'Biggest Star'),
(10, 'Factorial of 45', 'coding', '[\"321\",\"342\",\"875\",\"574\"]', '342'),
(11, 'print prime numbers upto 10', 'mcq', '[\"upto 10\",\"equal to 10\",\"lower than 10\",\"below 10\"]', 'upto 10'),
(12, 'Check prime number 7', 'mcq', '[\"7\",\"6\",\"5\",\"4\"]', '7'),
(14, 'How do you perform an inorder traversal of a binary tree?', 'coding', '[\"\",\"\",\"\",\"\"]', 'void inorderTraversal(TreeNode root) { if (root == null) return; inorderTraversal(root.left); System.out.print(root.val + \" \"); inorderTraversal(root.right); }'),
(15, 'How do you find the lowest common ancestor (LCA) of two nodes in a binary search tree?', 'coding', '[\"\",\"\",\"\",\"\"]', 'TreeNode lowestCommonAncestor(TreeNode root, TreeNode p, TreeNode q) { if (root.val > p.val && root.val > q.val) return lowestCommonAncestor(root.left, p, q); else if (root.val < p.val && root.val < q.val) return lowestCommonAncestor(root.right, p, q); el'),
(16, 'What is the process of sorting an array using a heap (heap sort)?', 'coding', '[\"\",\"\",\"\",\"\"]', 'void heapSort(int[] arr) { PriorityQueue<Integer> heap = new PriorityQueue<>(); for (int num : arr) heap.offer(num);  // Build min-heap for (int i = 0; i < arr.length; i++) arr[i] = heap.poll();  // Extract min }'),
(17, 'How can you sort an array of strings based on their length?\r\n', 'coding', '[\"\",\"\",\"\",\"\"]', 'Arrays.sort(arr, (a, b) -> a.length() - b.length());'),
(18, 'How do you calculate the factorial of a number using recursion?', 'coding', '[\"\",\"\",\"\",\"\"]', 'int factorial(int n) { if (n == 0 || n == 1) return 1; return n * factorial(n - 1); }'),
(19, 'How would you solve the N-th Fibonacci number using recursion?', 'coding', '[\"\",\"\",\"\",\"\"]', 'int fibonacci(int n) { if (n <= 1) return n; return fibonacci(n - 1) + fibonacci(n - 2); }'),
(20, 'How do you solve the coin change problem with dynamic programming?', 'coding', '[\"\",\"\",\"\",\"\"]', 'int coinChange(int[] coins, int amount) { int[] dp = new int[amount + 1]; Arrays.fill(dp, amount + 1); dp[0] = 0; for (int coin : coins) { for (int i = coin; i <= amount; i++) { dp[i] = Math.min(dp[i], dp[i - coin] + 1); } } return dp[amount] > amount ? -'),
(21, 'How do you solve the N-Queens problem using backtracking?', 'coding', '[\"\",\"\",\"\",\"\"]', 'We can use backtracking to solve the N-Queens problem. The method tries placing queens row by row and undoes if it leads to a conflict.'),
(22, 'Count the number of unique characters in a given String', 'coding', '[\"\",\"\",\"\",\"\"]', '// C++ program of the above approach #include <bits/stdc++.h> using namespace std;  // Program to count the number of // unique characters in a string int cntDistinct(string str) { 	// Set to store unique characters 	// in the given string 	unordered_set<'),
(23, 'Count the Number of matching characters in a pair of strings\r\n', 'coding', '[\"\",\"\",\"\",\"\"]', '// C++ code to count number of matching // characters in a pair of strings  #include <bits/stdc++.h> using namespace std;  // Function to count the matching characters void count(string str1, string str2) { 	int c = 0, j = 0;  	// Traverse the string 1 ch'),
(24, 'Count of number of given string in 2D character array\r\n', 'coding', '[\"\",\"\",\"\",\"\"]', 'count: 3'),
(25, 'Chandra Babu Naidu', 'mcq', '[\"CM\",\"MLA\",\"MP\",\"Corporator\"]', 'CM'),
(26, 'Jagan', 'mcq', '[\"Ex CM\",\"Fraud\",\"Defence\",\"Air Force\"]', 'Ex CM'),
(27, 'Adi', 'mcq', '[\"First\",\"Second\",\"Third\",\"Fourth\"]', 'First'),
(28, 'Venkatesh', 'mcq', '[\"7 hills\",\"6 hills\",\"8 hills\",\"4 hills\"]', '7 hills'),
(29, 'Kalki', 'mcq', '[\"Prabhas\",\"Kamal Haasan\",\"Deepika\",\"All of the Above\"]', 'All of the Above'),
(30, 'Rambabu', 'mcq', '[\"Babu\",\"Ram\",\"Babu Ram\",\"All of the above\"]', 'All of the Above'),
(31, 'Anasuya', 'mcq', '[\"Pushpa\",\"Darling\",\"Mehbooba\",\"Salaar\"]', 'Pushpa'),
(32, 'Who is President of India', 'mcq', '[\"Droupati Murmu\",\"Narendra Modi\",\"Nirmala Sitaraman\",\"Amit Shah\"]', '1'),
(33, 'Release date of HIT 3', 'mcq', '[\"May 1\",\"May 2\",\"April 30\",\"April 29\"]', 'May 1');

-- --------------------------------------------------------

--
-- Table structure for table `test_attempts`
--

CREATE TABLE `test_attempts` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_results`
--

CREATE TABLE `test_results` (
  `id` int(11) NOT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `attempt_id` int(11) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `test_results`
--

INSERT INTO `test_results` (`id`, `candidate_id`, `question_id`, `answer`, `score`, `submitted_at`, `attempt_id`, `is_correct`) VALUES
(123, 23, 14, 'asdfg', NULL, '2025-04-30 06:31:40', NULL, NULL),
(124, 23, 16, 'fdsg', NULL, '2025-04-30 06:31:40', NULL, NULL),
(125, 23, 9, 'Biggest Star', NULL, '2025-04-30 06:31:40', NULL, NULL),
(126, 23, 26, 'Ex CM', NULL, '2025-04-30 06:31:40', NULL, NULL),
(127, 23, 11, 'upto 10', NULL, '2025-04-30 06:31:40', NULL, NULL),
(128, 23, 27, 'First', NULL, '2025-04-30 06:31:40', NULL, NULL),
(129, 23, 15, 'fsdghcn', NULL, '2025-04-30 06:31:40', NULL, NULL),
(130, 23, 33, 'May 1', NULL, '2025-04-30 06:31:40', NULL, NULL),
(131, 23, 6, 'Orange', NULL, '2025-04-30 06:31:40', NULL, NULL),
(132, 23, 24, 'ergdfbv', NULL, '2025-04-30 06:31:40', NULL, NULL),
(133, 23, 19, 'wredfg', NULL, '2025-04-30 06:31:40', NULL, NULL),
(134, 23, 3, 'Modi', NULL, '2025-04-30 06:31:40', NULL, NULL),
(135, 23, 21, 'edfsgv', NULL, '2025-04-30 06:31:40', NULL, NULL),
(136, 23, 12, '7', NULL, '2025-04-30 06:31:40', NULL, NULL),
(137, 23, 2, '3qerwthj', NULL, '2025-04-30 06:31:40', NULL, NULL),
(138, 23, 30, 'Babu', NULL, '2025-04-30 06:31:40', NULL, NULL),
(139, 23, 29, 'Prabhas', NULL, '2025-04-30 06:31:40', NULL, NULL),
(140, 23, 32, 'Droupati Murmu', NULL, '2025-04-30 06:31:40', NULL, NULL),
(141, 23, 4, 'Smuggler', NULL, '2025-04-30 06:31:40', NULL, NULL),
(142, 23, 5, 'Anupama', NULL, '2025-04-30 06:31:40', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offer_letters`
--
ALTER TABLE `offer_letters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_attempts`
--
ALTER TABLE `test_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `offer_letters`
--
ALTER TABLE `offer_letters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `test_attempts`
--
ALTER TABLE `test_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_results`
--
ALTER TABLE `test_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
