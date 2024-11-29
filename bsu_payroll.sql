/*
 Navicat Premium Data Transfer

 Source Server         : PersonalProjectDB
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : bsu_payroll

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 30/11/2024 07:38:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for adding_employee
-- ----------------------------
DROP TABLE IF EXISTS `adding_employee`;
CREATE TABLE `adding_employee`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `middle_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `rate_id` int NOT NULL,
  `contact` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `department_id` int NOT NULL,
  `date_hired` date NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `face_samples` longblob NOT NULL,
  `face_descriptors` longblob NOT NULL,
  `password_changed` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `birthdate` date NOT NULL,
  `gender` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nationality` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `emergency_contact_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `emergency_contact_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `profile_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `employee_stats` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employee_no`(`employee_no` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 48 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of adding_employee
-- ----------------------------
INSERT INTO `adding_employee` VALUES (39, 'EMP-3475', 'Mariano', 'Janine Kaye', 'Binatero', 'gajultos.garry123@gmail.com', 16, '09420542776', 5, '2024-11-13', 'upper bigte', 0x3330, '', 1, '$2y$10$HlBeBNdC7QSiJdbBq/sSI.eOD3V567uBvuUmHsOpjfKu4zipXe1Ba', '2024-11-28', '', '', '23', '3', '../uploads/Dinuguan.jpg', 'Part Time');
INSERT INTO `adding_employee` VALUES (41, 'EMP-6601', 'Yu', 'King Mark', 'rodriguez', 'kingking2931@gmail.com', 23, '09420542776', 2, '2024-11-15', 'blk 16', 0x3330, '', 1, '$2y$10$oz1ZZr/loobrcKmANSZxTOdCgEr7FmEk4dqMPafZzakLJpip5.nO6', '0000-00-00', '', '', '', '', NULL, 'Part Time');
INSERT INTO `adding_employee` VALUES (45, 'EMP-9237', 'Gajultos', 'Garry', 'Dela Torre', 'test1@gmail.com', 18, '23', 4, '2024-12-12', 'ee', '', '', 0, '', '0000-00-00', '', '', '', '', NULL, 'Part Time');

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password_decode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (1, 'admin', '$2y$10$vtj3lvgROA.ecVm2oI2YnOrlhFzn1jNE/sMk72HTTH.ymfaBol9jW', '123123');
INSERT INTO `admin` VALUES (3, 'kingpogi', '$2y$10$vtj3lvgROA.ecVm2oI2YnOrlhFzn1jNE/sMk72HTTH.ymfaBol9jW', '123123');

-- ----------------------------
-- Table structure for attendance
-- ----------------------------
DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `clock_in` datetime NOT NULL,
  `clock_out` datetime NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `worked_time` time NOT NULL,
  `overtime` time NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `employee_no`(`employee_no` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of attendance
-- ----------------------------
INSERT INTO `attendance` VALUES (4, 'EMP-2358', '2024-11-15', '2024-11-15 08:00:00', '2024-11-15 17:19:58', 'Present', '08:00:00', '00:00:00');
INSERT INTO `attendance` VALUES (5, 'EMP-3475', '2024-11-15', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Absent', '00:00:00', '00:00:00');
INSERT INTO `attendance` VALUES (6, 'EMP-6601', '2024-11-15', '2024-11-15 08:00:00', '2024-11-15 17:53:26', 'Late', '08:00:00', '00:00:00');
INSERT INTO `attendance` VALUES (7, 'EMP-6601', '2024-11-15', '2024-11-16 08:00:00', '2024-11-16 17:53:26', 'Late', '08:00:00', '00:00:00');

-- ----------------------------
-- Table structure for attendance_report
-- ----------------------------
DROP TABLE IF EXISTS `attendance_report`;
CREATE TABLE `attendance_report`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `employee_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('Present','Late','Absent') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `time_in` time NULL DEFAULT NULL,
  `time_out` time NULL DEFAULT NULL,
  `actual_time` time NULL DEFAULT NULL,
  `worked_time` time NOT NULL,
  `overtime` time NOT NULL,
  `is_paid` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employee_no`(`employee_no` ASC, `date` ASC) USING BTREE,
  CONSTRAINT `attendance_report_ibfk_1` FOREIGN KEY (`employee_no`) REFERENCES `adding_employee` (`employee_no`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of attendance_report
-- ----------------------------
INSERT INTO `attendance_report` VALUES (3, 'EMP-6601', 'King Mark Yu', 'Present', '2024-11-15', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 0);
INSERT INTO `attendance_report` VALUES (4, 'EMP-6601', 'King Mark Yu', 'Present', '2024-11-16', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 0);
INSERT INTO `attendance_report` VALUES (5, 'EMP-6601', 'King Mark Yu', 'Absent', '2024-11-23', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 0);
INSERT INTO `attendance_report` VALUES (6, 'EMP-6601', 'King Mark Yu', 'Late', '2024-11-18', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 0);
INSERT INTO `attendance_report` VALUES (7, 'EMP-3475', 'Janine Mariano', 'Late', '2024-11-15', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 1);
INSERT INTO `attendance_report` VALUES (8, 'EMP-3475', 'Janine Mariano', 'Late', '2024-11-16', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 1);
INSERT INTO `attendance_report` VALUES (9, 'EMP-3475', 'Janine Mariano', 'Present', '2024-11-17', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 1);
INSERT INTO `attendance_report` VALUES (10, 'EMP-3475', 'Janine Mariano', 'Present', '2024-11-18', '08:00:00', '19:53:26', '08:00:00', '00:00:00', '00:00:00', 1);
INSERT INTO `attendance_report` VALUES (11, 'EMP-3475', 'Janine Mariano', 'Present', '2024-11-23', '08:00:00', '18:00:00', '08:00:00', '00:00:00', '00:00:00', 1);

-- ----------------------------
-- Table structure for cash_advance
-- ----------------------------
DROP TABLE IF EXISTS `cash_advance`;
CREATE TABLE `cash_advance`  (
  `cash_advance_id` int NOT NULL AUTO_INCREMENT,
  `employee_no` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `requested_amount` decimal(10, 2) NOT NULL,
  `paid_amount` decimal(11, 2) NULL DEFAULT NULL,
  `remaining_balance` decimal(11, 2) NULL DEFAULT NULL,
  `status` enum('Pending','Approved','Partially Paid','Paid','Declined') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp,
  `payment_date` timestamp NULL DEFAULT NULL,
  `months` int NULL DEFAULT NULL,
  `id` int NULL DEFAULT NULL,
  `monthly_payment` decimal(11, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`cash_advance_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cash_advance
-- ----------------------------
INSERT INTO `cash_advance` VALUES (12, 'EMP-3475', 3000.00, NULL, 0.00, 'Paid', '2024-11-29 12:24:35', NULL, 4, 39, 750.00);

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department`  (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`department_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES (2, 'Project Management Department');
INSERT INTO `department` VALUES (4, 'Construction Design Department');
INSERT INTO `department` VALUES (5, 'Human Resources & Administration Department');
INSERT INTO `department` VALUES (6, 'Finance & Accounting Department');
INSERT INTO `department` VALUES (8, 'Operations Department');

-- ----------------------------
-- Table structure for payroll
-- ----------------------------
DROP TABLE IF EXISTS `payroll`;
CREATE TABLE `payroll`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rate_per_hour` decimal(10, 2) NULL DEFAULT NULL,
  `basic_per_day` decimal(10, 2) NULL DEFAULT NULL,
  `number_of_days` int NULL DEFAULT NULL,
  `total_hours` decimal(10, 2) NULL DEFAULT NULL,
  `gross_pay` decimal(10, 2) NULL DEFAULT NULL,
  `sss` decimal(10, 2) NULL DEFAULT NULL,
  `philhealth` decimal(10, 2) NULL DEFAULT NULL,
  `pagibig` decimal(10, 2) NULL DEFAULT NULL,
  `total_deductions` decimal(10, 2) NULL DEFAULT NULL,
  `cash_advance` decimal(10, 2) NULL DEFAULT NULL,
  `cash_advance_pay` decimal(10, 2) NULL DEFAULT NULL,
  `net_pay` decimal(10, 2) NULL DEFAULT NULL,
  `payment_date` date NULL DEFAULT NULL,
  `total_overtime_hours` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 109 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of payroll
-- ----------------------------
INSERT INTO `payroll` VALUES (59, 'EMP-3475', 99.00, 792.00, 4, 32.00, 3.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.00, 885.00, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (60, 'EMP-3475', 99.00, 792.00, 4, 32.00, 3.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.00, 885.00, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (61, 'EMP-3475', 99.00, 792.00, 4, 32.00, 3.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, 884.67, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (62, 'EMP-3475', 99.00, 792.00, 4, 32.00, 3.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, 884.67, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (63, 'EMP-3475', 99.00, 792.00, 4, 32.00, 3.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.00, 885.00, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (64, 'EMP-3475', 99.00, 792.00, 4, 32.00, 3.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.00, 885.00, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (65, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.00, -2283.00, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (66, 'EMP-3475', 99.00, 792.00, 4, 32.00, 3.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, 884.67, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (67, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (68, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (69, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (70, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (71, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (72, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (73, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (74, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (75, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (76, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (77, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (78, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (79, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (80, 'EMP-3475', 99.00, 792.00, 4, 32.00, 3.00, 500.00, 200.00, 250.00, 950.00, 0.00, 0.00, 2504.11, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (81, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (82, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (83, 'EMP-3475', 99.00, 792.00, 0, 0.00, 0.00, 500.00, 200.00, 250.00, 950.00, 4000.00, 1333.33, -2283.33, '2024-11-19', NULL);
INSERT INTO `payroll` VALUES (84, 'EMP-3475', 750.00, 6000.00, 2, 16.00, 13.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 750.00, 12667.50, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (85, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 250.00, -750.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (86, 'EMP-3475', 750.00, 6000.00, 5, 40.00, 31.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 1150.00, 28667.50, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (87, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 750.00, -750.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (88, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 100.00, -850.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (89, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 100.00, -850.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (90, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 750.00, -750.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (91, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 2240.00, -2990.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (92, 'EMP-3475', 750.00, 6000.00, 5, 40.00, 31.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 2900.00, 27767.50, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (93, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 100.00, -850.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (94, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 2800.00, -3550.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (95, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 2900.00, -3650.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (96, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 750.00, -750.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (97, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 750.00, -750.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (98, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 750.00, -750.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (99, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 2800.00, -2800.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (100, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 3000.00, -3000.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (101, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 0.00, 0.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (102, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 100.00, -100.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (103, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 0.00, 0.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (104, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 3000.00, -3000.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (105, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 2500.00, -2500.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (106, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 2500.00, -2500.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (107, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 500.00, -500.00, '2024-11-29', NULL);
INSERT INTO `payroll` VALUES (108, 'EMP-3475', 750.00, 6000.00, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 3000.00, 3000.00, -3000.00, '2024-11-29', NULL);

-- ----------------------------
-- Table structure for rate_position
-- ----------------------------
DROP TABLE IF EXISTS `rate_position`;
CREATE TABLE `rate_position`  (
  `rate_id` int NOT NULL AUTO_INCREMENT,
  `rate_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `rate_per_hour` decimal(11, 2) NULL DEFAULT NULL,
  `rate_per_day` decimal(11, 2) NULL DEFAULT NULL,
  `ot_per_hour` decimal(10, 2) NULL DEFAULT NULL,
  `department_id` int NULL DEFAULT NULL,
  PRIMARY KEY (`rate_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rate_position
-- ----------------------------
INSERT INTO `rate_position` VALUES (10, 'Chief Financial Officer (CFO)', NULL, NULL, NULL, 6);
INSERT INTO `rate_position` VALUES (11, 'Accountant', NULL, NULL, NULL, 6);
INSERT INTO `rate_position` VALUES (12, 'Accounts Payable/Receivable Specialist', NULL, NULL, NULL, 6);
INSERT INTO `rate_position` VALUES (13, 'Financial Analyst', NULL, NULL, NULL, 6);
INSERT INTO `rate_position` VALUES (14, 'Tax Specialist', NULL, NULL, NULL, 6);
INSERT INTO `rate_position` VALUES (15, 'HR Manager', NULL, NULL, NULL, 5);
INSERT INTO `rate_position` VALUES (16, 'Recruitment Officer', NULL, NULL, NULL, 5);
INSERT INTO `rate_position` VALUES (17, 'Payroll Administrator', NULL, NULL, NULL, 5);
INSERT INTO `rate_position` VALUES (18, 'Admin Manager', NULL, NULL, NULL, 5);
INSERT INTO `rate_position` VALUES (19, 'Training & Development Officer', NULL, NULL, NULL, 5);
INSERT INTO `rate_position` VALUES (20, 'Lead Architect', NULL, NULL, NULL, 4);
INSERT INTO `rate_position` VALUES (21, 'Civil Engineer', NULL, NULL, NULL, 4);
INSERT INTO `rate_position` VALUES (22, 'Structural Engineer', NULL, NULL, NULL, 4);
INSERT INTO `rate_position` VALUES (23, 'MEP (Mechanical, Electrical, Plumbing) Engineer', NULL, NULL, NULL, 4);
INSERT INTO `rate_position` VALUES (24, 'Draftsman', NULL, NULL, NULL, 4);
INSERT INTO `rate_position` VALUES (25, 'Operations Manager', NULL, NULL, NULL, 8);
INSERT INTO `rate_position` VALUES (26, 'Field Engineer', NULL, NULL, NULL, 8);
INSERT INTO `rate_position` VALUES (27, 'Safety Officer', NULL, NULL, NULL, 8);
INSERT INTO `rate_position` VALUES (28, 'Quality Control Officer', NULL, NULL, NULL, 8);
INSERT INTO `rate_position` VALUES (29, 'Logistics Coordinator Department', NULL, NULL, NULL, 8);
INSERT INTO `rate_position` VALUES (30, 'Project Manager', NULL, NULL, NULL, 2);
INSERT INTO `rate_position` VALUES (31, 'Construction Supervisor', NULL, NULL, NULL, 2);
INSERT INTO `rate_position` VALUES (32, 'Cost Estimator', NULL, NULL, NULL, 2);
INSERT INTO `rate_position` VALUES (33, 'Project Coordinator', NULL, NULL, NULL, 2);
INSERT INTO `rate_position` VALUES (34, 'Scheduler', NULL, NULL, NULL, 2);

-- ----------------------------
-- Table structure for under_position
-- ----------------------------
DROP TABLE IF EXISTS `under_position`;
CREATE TABLE `under_position`  (
  `rate_id` int NOT NULL AUTO_INCREMENT,
  `rate_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `rate_per_hour` decimal(11, 2) NULL DEFAULT NULL,
  `rate_per_day` decimal(11, 2) NULL DEFAULT NULL,
  `ot_per_hour` decimal(11, 2) NULL DEFAULT NULL,
  `position_id` int NULL DEFAULT NULL,
  PRIMARY KEY (`rate_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 91 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of under_position
-- ----------------------------
INSERT INTO `under_position` VALUES (14, 'Senior Position', 80.00, 800.00, NULL, 1);
INSERT INTO `under_position` VALUES (15, 'Junior Position', 70.00, 700.00, NULL, 1);
INSERT INTO `under_position` VALUES (16, 'Senior CFO', 750.00, 6000.00, NULL, 10);
INSERT INTO `under_position` VALUES (17, 'CFO', 500.00, 4000.00, NULL, 10);
INSERT INTO `under_position` VALUES (18, 'Assistant CFO', 300.00, 2400.00, NULL, 10);
INSERT INTO `under_position` VALUES (19, 'Senior Accountant', 250.00, 2000.00, NULL, 11);
INSERT INTO `under_position` VALUES (20, 'Accountant', 175.00, 1400.00, NULL, 11);
INSERT INTO `under_position` VALUES (21, 'Junior Accountant', 125.00, 1000.00, NULL, 11);
INSERT INTO `under_position` VALUES (22, 'Senior Accounts Specialist', 250.00, 2000.00, NULL, 12);
INSERT INTO `under_position` VALUES (23, 'Accounts Specialist', 175.00, 1400.00, NULL, 12);
INSERT INTO `under_position` VALUES (24, 'Junior Accounts Specialist', 125.00, 1000.00, NULL, 12);
INSERT INTO `under_position` VALUES (25, 'Senior Financial Analyst', 275.00, 2200.00, NULL, 13);
INSERT INTO `under_position` VALUES (26, 'Financial Analyst', 200.00, 1600.00, NULL, 13);
INSERT INTO `under_position` VALUES (27, 'Junior Financial Analyst', 125.00, 1000.00, NULL, 13);
INSERT INTO `under_position` VALUES (28, 'Senior Tax Specialist', 300.00, 2400.00, NULL, 14);
INSERT INTO `under_position` VALUES (29, 'Tax Specialist', 225.00, 1800.00, NULL, 14);
INSERT INTO `under_position` VALUES (30, 'Junior Tax Specialist', 150.00, 1200.00, NULL, 14);
INSERT INTO `under_position` VALUES (31, 'Senior HR Manager', 350.00, 2800.00, NULL, 15);
INSERT INTO `under_position` VALUES (32, 'HR Manager', 250.00, 2000.00, NULL, 15);
INSERT INTO `under_position` VALUES (33, 'HR Assistant', 120.00, 1000.00, NULL, 15);
INSERT INTO `under_position` VALUES (34, 'Senior Recruitment Officer', 250.00, 2000.00, NULL, 16);
INSERT INTO `under_position` VALUES (35, 'Recruitment Officer', 200.00, 1600.00, NULL, 16);
INSERT INTO `under_position` VALUES (36, 'Junior Recruitment Officer', 125.00, 1000.00, NULL, 16);
INSERT INTO `under_position` VALUES (37, 'Senior Payroll Administrator', 250.00, 2000.00, NULL, 17);
INSERT INTO `under_position` VALUES (38, 'Payroll Administrator', 175.00, 1400.00, NULL, 17);
INSERT INTO `under_position` VALUES (39, 'Payroll Assistant', 125.00, 1000.00, NULL, 17);
INSERT INTO `under_position` VALUES (40, 'Senior Admin Manager', 300.00, 2400.00, NULL, 18);
INSERT INTO `under_position` VALUES (41, 'Admin Manager', 200.00, 1600.00, NULL, 18);
INSERT INTO `under_position` VALUES (42, 'Admin Assistant', 100.00, 800.00, NULL, 18);
INSERT INTO `under_position` VALUES (43, 'Senior Training Officer', 250.00, 2000.00, NULL, 19);
INSERT INTO `under_position` VALUES (44, 'Training Officer', 200.00, 1600.00, NULL, 19);
INSERT INTO `under_position` VALUES (45, 'Junior Training Officer', 125.00, 1000.00, NULL, 19);
INSERT INTO `under_position` VALUES (46, 'Senior Architect', 350.00, 2800.00, NULL, 20);
INSERT INTO `under_position` VALUES (47, 'Architect', 250.00, 2000.00, NULL, 20);
INSERT INTO `under_position` VALUES (48, 'Junior Architect', 175.00, 1400.00, NULL, 20);
INSERT INTO `under_position` VALUES (49, 'Senior Civil Engineer', 325.00, 2600.00, NULL, 21);
INSERT INTO `under_position` VALUES (50, 'Civil Engineer', 250.00, 2000.00, NULL, 21);
INSERT INTO `under_position` VALUES (51, 'Junior Civil Engineer', 175.00, 1400.00, NULL, 21);
INSERT INTO `under_position` VALUES (52, 'Senior Structural Engineer', 350.00, 2800.00, NULL, 22);
INSERT INTO `under_position` VALUES (53, 'Structural Engineer', 275.00, 2200.00, NULL, 22);
INSERT INTO `under_position` VALUES (54, 'Junior Structural Engineer', 175.00, 1400.00, NULL, 22);
INSERT INTO `under_position` VALUES (55, 'Senior MEP Engineer', 350.00, 2800.00, NULL, 23);
INSERT INTO `under_position` VALUES (56, 'MEP Engineer', 250.00, 2000.00, NULL, 23);
INSERT INTO `under_position` VALUES (57, 'Junior MEP Engineer', 175.00, 1400.00, NULL, 23);
INSERT INTO `under_position` VALUES (58, 'Senior Draftsman', 225.00, 1800.00, NULL, 24);
INSERT INTO `under_position` VALUES (59, 'Draftsman', 150.00, 1200.00, NULL, 24);
INSERT INTO `under_position` VALUES (60, 'Junior Draftsman', 150.00, 1200.00, NULL, 24);
INSERT INTO `under_position` VALUES (61, 'Senior Operations Manager', 500.00, 4000.00, NULL, 25);
INSERT INTO `under_position` VALUES (62, 'Operations Manager', 350.00, 2800.00, NULL, 25);
INSERT INTO `under_position` VALUES (63, 'Assistant Operations Manager', 200.00, 1600.00, NULL, 25);
INSERT INTO `under_position` VALUES (64, 'Senior Field Engineer', 350.00, 2800.00, NULL, 26);
INSERT INTO `under_position` VALUES (65, 'Field Engineer', 250.00, 2000.00, NULL, 26);
INSERT INTO `under_position` VALUES (66, 'Junior Field Engineer', 175.00, 1400.00, NULL, 26);
INSERT INTO `under_position` VALUES (67, 'Senior Safety Officer', 300.00, 2400.00, NULL, 27);
INSERT INTO `under_position` VALUES (68, 'Safety Officer', 225.00, 1800.00, NULL, 27);
INSERT INTO `under_position` VALUES (69, 'Junior Safety Officer', 150.00, 1200.00, NULL, 27);
INSERT INTO `under_position` VALUES (70, 'Senior Quality Control Officer', 300.00, 2400.00, NULL, 28);
INSERT INTO `under_position` VALUES (71, 'Quality Control Officer', 225.00, 1800.00, NULL, 28);
INSERT INTO `under_position` VALUES (72, 'Junior Quality Control Officer', 150.00, 1200.00, NULL, 28);
INSERT INTO `under_position` VALUES (73, 'Senior Logistics Coordinator', 300.00, 2400.00, NULL, 29);
INSERT INTO `under_position` VALUES (74, 'Logistics Coordinato', 225.00, 1800.00, NULL, 29);
INSERT INTO `under_position` VALUES (75, 'Junior Logistics Coordinator', 150.00, 1200.00, NULL, 29);
INSERT INTO `under_position` VALUES (76, 'Senior Project Manager', 400.00, 3200.00, NULL, 30);
INSERT INTO `under_position` VALUES (77, 'Project Manager', 300.00, 2400.00, NULL, 30);
INSERT INTO `under_position` VALUES (78, 'Assistant Project Manager', 300.00, 2400.00, NULL, 30);
INSERT INTO `under_position` VALUES (79, 'Senior Construction Supervisor', 300.00, 2400.00, NULL, 31);
INSERT INTO `under_position` VALUES (80, 'Construction Supervisor', 225.00, 1800.00, NULL, 31);
INSERT INTO `under_position` VALUES (81, 'Junior Construction Supervisor', 150.00, 1200.00, NULL, 31);
INSERT INTO `under_position` VALUES (82, 'Senior Estimator', 350.00, 2800.00, NULL, 32);
INSERT INTO `under_position` VALUES (83, 'Estimator', 250.00, 2000.00, NULL, 32);
INSERT INTO `under_position` VALUES (84, 'Junior Estimato', 175.00, 1400.00, NULL, 32);
INSERT INTO `under_position` VALUES (85, 'Senior Project Coordinator', 275.00, 2200.00, NULL, 33);
INSERT INTO `under_position` VALUES (86, 'Project Coordinator', 200.00, 1600.00, NULL, 33);
INSERT INTO `under_position` VALUES (87, 'Assistant Project Coordinator', 125.00, 1000.00, NULL, 33);
INSERT INTO `under_position` VALUES (88, 'Senior Scheduler', 300.00, 2400.00, NULL, 34);
INSERT INTO `under_position` VALUES (89, 'Scheduler', 225.00, 1800.00, NULL, 34);
INSERT INTO `under_position` VALUES (90, 'Junior Scheduler', 150.00, 1200.00, NULL, 34);

SET FOREIGN_KEY_CHECKS = 1;
