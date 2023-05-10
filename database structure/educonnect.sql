-------------------------------------------------FOR A BETTER TEST USE THIS DATABASE STRUCTURE--------------------------------------------------
------------------------------------------------------------------------------------------------------------------------------------------------

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2023 at 12:52 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10


-- --------------------------------------------------------
-- Database: `educconnect`
-- --------------------------------------------------------

--
-- /////////////////////////////////////////////////////Table structure for table `classes`////////////////////////////////////////////////////
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `class_name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- INSERT THIS ROW TO 'classes' TABLE FOR TEST
--

INSERT INTO `classes` (`id`, `class_name`) VALUES
(1, 'class1'),
(2, 'class2'),
(3, 'class3'),
(4, 'class4'),
(5, 'class5'),
(6, 'class6'),
(7, 'class7');

--
-- /////////////////////////////////////////////////Table structure for table `surveillant_enseignant`////////////////////////////////////////
--

CREATE TABLE `surveillant_enseignant` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `who_is` varchar(25) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `username` varchar(25) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  CONSTRAINT `fk_class_id` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `surveillant_enseignant`
--

INSERT INTO `surveillant_enseignant` (`who_is`, `user_id`, `phone`, `first_name`, `last_name`, `username`, `email`, `password`, `class_id`) VALUES
('teacher', 1, '0635222417', 'Taha', 'Amrani', 'teacher1', 'teacher1@educconnect.com', 'tahataha', '1,2,3,5'),
('teacher', 2, '0635211478', 'Marouane', 'Jabri', 'teacher2', 'teacher2@educconnect.com', 'password2', '2,3,4,7'),
('teacher', 3, '0635211478', 'Mohamed', 'Qahtani', 'teacher3', 'teacher3@educconnect.com', 'password3', '1,2,5,6'),
('teacher', 4, '0635211478', 'Sara', 'Al Farhani', 'teacher4', 'teacher4@educconnect.com', 'password4', '1,4,6,7'),
('teacher', 5, '0635211478', 'Lamya', 'Husseini', 'teacher5', 'teacher5@educconnect.com', 'password5', '2,3,5,6'),
('teacher', 6, '0635211478', 'Omar', 'Al rachadi', 'teacher6', 'teacher6@educconnect.com', 'password6', '2,4,5'),
('teacher', 7, '0635211478', 'Asma', 'Salman', 'teacher7', 'teacher7@educconnect.com', 'password7', '1,7,6'),
('teacher', 8, '0635211478', 'Hind', 'Hamadani', 'teacher8', 'teacher8@educconnect.com', 'password8', '2,3,4,5'),
('teacher', 9, '0635211478', 'Noureddine', 'Zahid', 'teacher9', 'teacher9@educconnect.com', 'password9', '1,2,7'),
('teacher', 10, '0635211478', 'Ali', 'Ibnain', 'teacher10', 'teacher10@educconnect.com', 'password10', '3,5,6'),
('surveillant', 1, '0635211478', 'Abdljbar', 'Kanadi', 'surveillant1', 'surveillant1@educconnect.com', 'surveillant1', '1,2,3,4'),
('surveillant', 2, '0635211478', 'Hassan', 'Hamidi', 'surveillant2', 'surveillant2@educconnect.com', 'surveillant2', '5,6,7');

--
-- //////////////////////////////////////////////////////////Table structure for table `eleve`///////////////////////////////////////////////
--

CREATE TABLE `eleve` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `No` int(11) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `password` varchar(30) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `cne` varchar(20) NOT NULL UNIQUE KEY,
  `class_id` int(11) NOT NULL,
  `who_is` varchar(25) DEFAULT NULL,
   CONSTRAINT `fk_class_id` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- INSERT THESE ROWS TO 'eleve' TABLE FOR TEST
--

INSERT INTO `eleve` (`No`, `username`, `phone`, `email`, `password`, `first_name`, `last_name`, `cne`, `class_id`, `who_is`) VALUES
(1, 'student1', '0624657459', 'student1@educconnect.com', 'student1', 'Taha', 'Alami', 'G139654785', 1, 'éleve'),
(2, 'student2', '0632254828', 'student2@educconnect.com', 'student2', 'Imane', 'Bakir', 'G985518507', 1, 'éleve'),
(3, 'student3', '0632254654', 'student3@educconnect.com', 'student3', 'Sara', 'Bashar', 'G412551368', 1, 'éleve'),
(4, 'student4', '0632254828', 'student4@educconnect.com', 'student4', 'Leila', 'Burhan', 'G249701352', 1, 'éleve'),
(5, 'student5', '0639644828', 'student5@educconnect.com', 'student5', 'Ali', 'Darwish', 'G124752684', 1, 'éleve'),
(6, 'student6', '0632254826', 'student6@educconnect.com', 'student6', 'Maria', 'Dawoud', 'G874659397', 1, 'éleve'),
(7, 'student7', '0632254828', 'student7@educconnect.com', 'student7', 'Ahmed', 'Ebeid', 'G999038964', 1, 'éleve'),
(8, 'student8', '0632258521', 'student8@educconnect.com', 'student8', 'Linda', 'Fadel', 'G371216660', 1, 'éleve'),
(9, 'student9', '0632254828', 'student9@educconnect.com', 'student9', 'Lina', 'Faez', 'G858966440', 1, 'éleve'),
(10, 'student10', '0635914785', 'student10@educconnect.com', 'student10', 'Hind', 'Faheem', 'G181182252', 1, 'éleve');


--
-- ///////////////////////////////////////////////Table structure for table `calendrier_enseignant`////////////////////////////////////////////
--

CREATE TABLE `calendrier_enseignant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `day_of_week` varchar(20) DEFAULT NULL,
  `start_hour` time DEFAULT NULL,
  `end_hour` time DEFAULT NULL,
  `teaching_subject` varchar(30) DEFAULT NULL,
   CONSTRAINT `calendrier_enseignant_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `surveillant_enseignant` (`id`),
   CONSTRAINT `calendrier_enseignant_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- INSERT THESE ROWS TO 'calendrier_enseignant' TABLE FOR TEST
--

INSERT INTO `calendrier_enseignant` (`teacher_id`, `class_id`, `day_of_week`, `start_hour`, `end_hour`, `teaching_subject`) VALUES
(1, 5, 'Monday', '10:30:00', '12:30:00', 'Mathematics'),
(1, 1, 'Tuesday', '14:30:00', '18:30:00', 'Mathematics'),
(1, 2, 'Wednesday', '08:30:00', '10:30:00', 'Mathematics'),
(1, 3, 'Thursday', '14:30:00', '18:30:00', 'Mathematics'),
(1, 5, 'Friday', '08:30:00', '10:30:00', 'Mathematics'),
(1, 1, 'Saturday', '10:30:00', '13:30:00', 'Mathematics');

--


--///////////////////////////////////////////////////Table structure for table `absence`//////////////////////////////////////////////////////

CREATE TABLE `absence` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `student_id` int(20) DEFAULT NULL,
  `Prénom` varchar(30) DEFAULT NULL,
  `Nom` varchar(30) DEFAULT NULL,
  `Cne` varchar(30) DEFAULT NULL,
  `Classe` varchar(30) DEFAULT NULL,
  `Nombre_heures` int(20) DEFAULT NULL,
  `Mois` varchar(30) DEFAULT NULL,
  CONSTRAINT `fk_class_id` FOREIGN KEY (`student_id`) REFERENCES `eleve` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- INSERT THESE ROWS TO 'absence' TABLE FOR TEST
--

INSERT INTO `absence` (`student_id`, `Prénom`, `Nom`, `Cne`, `Classe`, `Nombre_heures`, `Mois`) VALUES
(1, 'Taha', 'Aalami', 'G139654785', 'class1', 11, 'May'),
(2, 'Imane', 'Bakir', 'G985518507', 'class1', 7, 'May'),
(3, 'Sara', 'Bashar', 'G412551368', 'class1', 6, 'May'),
(4, 'Leila', 'Burhan', 'G249701352', 'class1', 5, 'May'),
(5, 'Ali', 'Darwish', 'G124752684', 'class1', 6, 'May'),
(6, 'Maria', 'Dawoud', 'G874659397', 'class1', 0, 'May'),
(7, 'Ahmed', 'Ebeid', 'G999038964', 'class1', 0, 'May'),
(8, 'Linda', 'Fadel', 'G371216660', 'class1', 4, 'May'),
(9, 'Lina', 'Faez', 'G858966440', 'class1', 0, 'May'),
(10, 'Hind', 'Faheem', 'G181182252', 'class1', 6, 'May');


--
-- ////////////////////////////////////////////Table structure for table `absence_details`/////////////////////////////////////////////////////
--

CREATE TABLE `absence_details` 
(
  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `absence_hours` int(11) DEFAULT NULL,
  `month` varchar(20) DEFAULT NULL,
  `absence_day` varchar(30) DEFAULT NULL,
  CONSTRAINT `absence_details_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `eleve` (`id`),
  CONSTRAINT `fk_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- INSERT THESE ROWS TO 'absence_details' TABLE FOR TEST
--

INSERT INTO `absence_details` (`teacher_id`, `student_id`, `class_id`, `absence_hours`, `month`, `absence_day`) VALUES
(1, 1, 1, 0, 'May', '09/05/2023'),
(1, 2, 1, 4, 'May', '09/05/2023'),
(1, 3, 1, 0, 'May', '09/05/2023'),
(1, 4, 1, 0, 'May', '09/05/2023'),
(1, 5, 1, 4, 'May', '09/05/2023'),
(1, 6, 1, 0, 'May', '09/05/2023'),
(1, 7, 1, 0, 'May', '09/05/2023'),
(1, 8, 1, 4, 'May', '09/05/2023'),
(1, 9, 1, 0, 'May', '09/05/2023'),
(1, 10, 1, 4, 'May', '09/05/2023');


--
-- ///////////////////////////////////////////////////Table structure for table `absence_verification`////////////////////////////////////////
--

CREATE TABLE `absence_verification` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `data` longblob NOT NULL,
  `verification_date` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--

--
-- ////////////////////////////////////////////////Table structure for table `feuilles_entree`///////////////////////////////////////////////////
--

CREATE TABLE `feuilles_entree` (
  `id` int(11)  NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `student_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `html_content` text DEFAULT NULL,
  `date` varchar(30) DEFAULT NULL,
  `statu` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
