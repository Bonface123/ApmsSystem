-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 04:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `patient_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `doctor` varchar(255) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `scheduled_by` enum('patient','doctor') NOT NULL,
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `username`, `doctor`, `appointment_date`, `reason`, `created_at`, `status`, `scheduled_by`, `approval_status`) VALUES
(8, 'Apostle', 'Kimani', '2024-11-14 08:47:00', 'treatment', '2024-11-13 00:47:47', 'pending', 'patient', 'approved'),
(9, 'Apostle', 'Kimani', '2024-11-14 00:00:00', 'Medical Checkup', '2024-11-13 02:50:28', 'pending', 'doctor', 'approved'),
(10, 'Apostle', 'Kimani', '2024-11-14 05:54:00', 'treatment', '2024-11-13 00:54:28', 'pending', 'patient', 'rejected'),
(11, 'Apostle', 'Kimani', '2024-11-27 00:00:00', 'Medical Checkup', '2024-11-13 03:15:52', 'pending', 'doctor', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billing_info`
--

CREATE TABLE `billing_info` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Pending','Paid') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `record_id` int(11) NOT NULL,
  `patient_username` varchar(255) NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `treatment` text DEFAULT NULL,
  `record_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`record_id`, `patient_username`, `diagnosis`, `treatment`, `record_notes`, `created_at`) VALUES
(8, 'Apostle', 'Anemia', '2 spoons of water', 'take alot of vegetables', '2024-11-13 02:46:50'),
(9, 'Apostle', 'Diabetes', 'Insulin injection', 'see the doctor regularly', '2024-11-13 03:07:24');

-- --------------------------------------------------------

--
-- Table structure for table `medical_terms`
--

CREATE TABLE `medical_terms` (
  `term` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_terms`
--

INSERT INTO `medical_terms` (`term`, `description`) VALUES
('Acid Reflux', 'A condition in which stomach acid or bile irritates the food pipe lining, causing heartburn and other symptoms.'),
('Alzheimer\'s Disease', 'A progressive disease that destroys memory and other important mental functions, leading to dementia.'),
('Anemia', 'A condition in which you don\'t have enough red blood cells to carry adequate oxygen to your body\'s tissues.'),
('Appendicitis', 'An inflammation of the appendix, often causing pain in the lower right abdomen, and requires surgical removal.'),
('Arthritis', 'Inflammation of one or more joints, causing pain and stiffness that can worsen with age.'),
('Asthma', 'A condition in which your airways narrow and swell, producing extra mucus, making it difficult to breathe.'),
('Bronchitis', 'Inflammation of the bronchial tubes, leading to coughing, production of mucus, and difficulty breathing.'),
('Cancer', 'A group of diseases involving abnormal cell growth with the potential to spread to other parts of the body.'),
('Celiac Disease', 'An autoimmune disorder where the ingestion of gluten damages the small intestine lining, leading to malabsorption of nutrients.'),
('Cholera', 'A bacterial infection that leads to severe diarrhea and dehydration, often contracted from contaminated water sources.'),
('Chronic Fatigue Syndrome', 'A complex disorder characterized by persistent, unexplained fatigue that doesn\'t improve with rest.'),
('Chronic Obstructive Pulmonary Disease (COPD)', 'A group of lung diseases that block airflow and make it difficult to breathe, often caused by smoking.'),
('Conjunctivitis', 'An eye condition characterized by inflammation of the conjunctiva, commonly known as \"pink eye\".'),
('Diabetes', 'A group of diseases that affect how the body processes blood sugar (glucose).'),
('Ebola Virus Disease', 'A viral hemorrhagic fever that causes severe bleeding, organ failure, and is often fatal.'),
('Epilepsy', 'A neurological disorder marked by recurrent, unprovoked seizures.'),
('Gallbladder Disease', 'A group of conditions affecting the gallbladder, including inflammation, stones, or infection.'),
('Gallstones', 'Solid particles that form in the gallbladder and can block bile ducts, leading to pain, nausea, or other complications.'),
('Gastritis', 'Inflammation of the stomach lining, often causing nausea, vomiting, and stomach pain.'),
('Gout', 'A form of arthritis caused by uric acid crystals forming in the joints, leading to pain, redness, and swelling.'),
('Hepatitis', 'An inflammation of the liver, often caused by viral infections, that can lead to liver damage and other complications.'),
('Hepatitis C', 'A viral infection that causes liver inflammation, often leading to chronic liver disease or liver cancer.'),
('Hernia', 'A condition where an organ or tissue pushes through a weak spot in the muscle or tissue around it, often in the abdomen.'),
('HIV/AIDS', 'Human Immunodeficiency Virus (HIV) attacks the immune system, potentially leading to Acquired Immunodeficiency Syndrome (AIDS).'),
('Hypertension', 'A condition where the blood pressure in the arteries is persistently elevated.'),
('Influenza', 'A contagious respiratory illness caused by influenza viruses, leading to fever, cough, and muscle aches.'),
('Kidney Disease', 'A condition in which the kidneys are damaged and cannot filter blood properly, potentially leading to kidney failure.'),
('Lupus', 'An autoimmune disease where the body’s immune system attacks its own tissues, causing inflammation in various body parts.'),
('Malaria', 'A mosquito-borne infectious disease that causes fever, chills, and flu-like symptoms, and can be fatal if untreated.'),
('Meningitis', 'An infection that causes inflammation of the membranes surrounding the brain and spinal cord, often leading to severe illness.'),
('Multiple Sclerosis', 'An autoimmune disease that affects the central nervous system, causing a range of neurological symptoms.'),
('Osteoporosis', 'A condition where bones become weak and brittle, increasing the risk of fractures, especially in older adults.'),
('Parkinson\'s Disease', 'A progressive nervous system disorder that affects movement, causing tremors, stiffness, and slowness of movement.'),
('Pneumonia', 'An infection that inflames the air sacs in one or both lungs, often causing cough, fever, and difficulty breathing.'),
('Psoriasis', 'A chronic autoimmune condition that causes the rapid buildup of skin cells, resulting in scales and red patches.'),
('Sickle Cell Anemia', 'A genetic blood disorder that causes red blood cells to become misshapen, leading to anemia and potential organ damage.'),
('Stroke', 'A medical emergency in which poor blood flow to the brain results in cell death, causing permanent damage.'),
('Thyroid Disorders', 'Conditions that affect the thyroid gland, leading to either overproduction or underproduction of thyroid hormones.'),
('Tinnitus', 'The ringing, buzzing, or hissing sound in the ears that is commonly a symptom of ear infections or hearing loss.'),
('Tonsillitis', 'Inflammation of the tonsils, often causing a sore throat, difficulty swallowing, and swollen glands.'),
('Tuberculosis', 'A potentially serious infectious disease that mainly affects the lungs but can also affect other parts of the body.');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `patient_username` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime NOT NULL,
  `status` enum('pending','paid','failed') DEFAULT 'pending',
  `insurance_claim` tinyint(1) DEFAULT 0,
  `insurance_provider` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `patient` varchar(255) NOT NULL,
  `medication` varchar(255) NOT NULL,
  `dosage` varchar(255) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `prescribed_by` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `doctor` varchar(255) DEFAULT NULL,
  `prescription_date` datetime DEFAULT NULL,
  `prescription_by` varchar(255) DEFAULT NULL,
  `ai_suggestions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `patient`, `medication`, `dosage`, `duration`, `prescribed_by`, `created_at`, `doctor`, `prescription_date`, `prescription_by`, `ai_suggestions`) VALUES
(20, 'Apostle', 'Aspirin ', '375mg', '3', '', '2024-11-13 02:45:14', 'Kimani', '2024-11-13 05:45:14', 'Kimani', '[{\"medication\":\"Aspirin (elderly) (female-specific dosages)\",\"dosage\":\"375mg\",\"duration\":\"3 days\"},{\"medication\":\"Acetaminophen (elderly) (female-specific dosages)\",\"dosage\":\"375mg\",\"duration\":\"3 days\"},{\"medication\":\"Lactaid\",\"dosage\":\"1 tablet\",\"duration\":\"as needed\"}]'),
(21, 'Apostle', 'paracetamol ', '400mg', '5', '', '2024-11-13 03:14:32', 'Kimani', '2024-11-13 06:14:32', 'Kimani', '[{\"medication\":\"Paracetamol (low weight) (male-specific dosages)\",\"dosage\":\"400mg\",\"duration\":\"5 days\"},{\"medication\":\"Ibuprofen (low weight) (male-specific dosages)\",\"dosage\":\"160mg\",\"duration\":\"3 days\"},{\"medication\":\"Cetirizine\",\"dosage\":\"10mg\",\"duration\":\"7 days\"},{\"medication\":\"Loratadine\",\"dosage\":\"10mg\",\"duration\":\"7 days\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('patient','doctor','finance') NOT NULL,
  `age` int(11) DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `age`, `medical_history`, `created_at`, `is_admin`) VALUES
(5, 'admin', 'admin@example.com', '$2y$10$vy0/Shh/bJG3FSm32vINY.XVWBDui.yRZMcr.OcDIlyUs1AxAQ5q6', '', NULL, NULL, '2024-11-11 09:00:33', 0),
(6, 'Onduso Bonface', 'ondusobonface9@gmail.com', '$2y$10$OD.ugMRdAWHT./MfUUQqguLBQqimh/CTccR8YzUN4U9Pv9eG3AuWu', '', NULL, NULL, '2024-11-11 12:29:15', 0),
(8, 'bonface', 'ondusobonface@gmail.com', '$2y$10$HHVe.vvILxDmy66F8trvGOxebgRMcxXFCzhzbBzb14S6ZI35/HNdu', '', NULL, NULL, '2024-11-11 12:36:43', 0),
(10, 'john', 'john@gmail.com', '$2y$10$1OtqYOwb6YmCvMocdSkEx./PmPR7CrsumawTMajd3De9Zn7IPWsSu', 'finance', NULL, NULL, '2024-11-11 12:39:50', 0),
(11, 'Daniel', 'danie@gmail.com', '$2y$10$HdJt2GRZRrwm897WXzNS2OwPdyxRFOOkRC7pTXmVIHgi55AzpY8xa', 'finance', NULL, NULL, '2024-11-12 11:54:47', 0),
(12, 'Peter', 'peter321@gmail.com', '$2y$10$84NhmdyeTR1VTpJk6Bzl0.qyUTkZo/ShbyUEt2hhAf.ovCPe7vgPi', 'finance', NULL, NULL, '2024-11-12 12:00:21', 0),
(13, 'Apostle', 'Apostle@gmail.com', '$2y$10$w9AsMjt/zWPHmWTEbEnuDu8QzKAigDgiNAgSn5L/gVzcLtj8iff72', 'patient', 12, 'malaria', '2024-11-13 02:28:52', 0),
(14, 'Kimani', 'kimani@gmail.com', '$2y$10$WiWhzF/GcshUQqh2pVaG1OkW7NP8T0dxFZA2QW/2txGbQKuChdm4q', 'doctor', NULL, NULL, '2024-11-13 02:30:10', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `billing_info`
--
ALTER TABLE `billing_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `patient_username` (`patient_username`);

--
-- Indexes for table `medical_terms`
--
ALTER TABLE `medical_terms`
  ADD PRIMARY KEY (`term`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient` (`patient`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billing_info`
--
ALTER TABLE `billing_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD CONSTRAINT `auth_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `medical_records_ibfk_1` FOREIGN KEY (`patient_username`) REFERENCES `users` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`patient`) REFERENCES `users` (`username`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
