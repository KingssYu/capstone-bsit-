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

 Date: 28/11/2024 22:25:15
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
INSERT INTO `adding_employee` VALUES (39, 'EMP-3475', 'Mariano', 'Janine Kaye', 'Binatero', 'gajultos.garry123@gmail.com', 3, '09420542776', 5, '2024-11-13', 'upper bigte', 0x3330, '', 1, '$2y$10$HlBeBNdC7QSiJdbBq/sSI.eOD3V567uBvuUmHsOpjfKu4zipXe1Ba', '2024-11-28', '', '', '23', '3', '../uploads/Dinuguan.jpg', 'Regular');
INSERT INTO `adding_employee` VALUES (41, 'EMP-6601', 'Yu', 'King Mark', 'rodriguez', 'kingking2931@gmail.com', 4, '09420542776', 2, '2024-11-15', 'blk 16', 0x3330, '', 1, '$2y$10$oz1ZZr/loobrcKmANSZxTOdCgEr7FmEk4dqMPafZzakLJpip5.nO6', '0000-00-00', '', '', '', '', NULL, 'Part Time');
INSERT INTO `adding_employee` VALUES (45, 'EMP-9237', 'Gajultos', 'Garry', 'Dela Torre', 'test1@gmail.com', 5, '23', 4, '2024-12-12', 'ee', '', '', 0, '', '0000-00-00', '', '', '', '', NULL, 'Part Time');

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
INSERT INTO `attendance_report` VALUES (10, 'EMP-3475', 'Janine Mariano', 'Present', '2024-11-18', '08:00:00', '19:53:26', '08:00:00', '00:00:00', '00:00:00', 0);
INSERT INTO `attendance_report` VALUES (11, 'EMP-3475', 'Janine Mariano', 'Present', '2024-11-23', '08:00:00', '18:00:00', '08:00:00', '00:00:00', '00:00:00', 0);

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
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cash_advance
-- ----------------------------
INSERT INTO `cash_advance` VALUES (10, 'EMP-3475', 4000.00, NULL, 0.01, 'Pending', '2024-11-19 13:36:34', NULL, 3, 39, 1333.33);
INSERT INTO `cash_advance` VALUES (11, 'EMP-3475', 4000.00, NULL, 4000.00, 'Pending', '2024-11-19 22:20:28', NULL, 3, 39, 1333.33);

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department`  (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`department_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES (2, 'Department 1');
INSERT INTO `department` VALUES (4, 'Department 2');
INSERT INTO `department` VALUES (5, 'Department 3');
INSERT INTO `department` VALUES (6, 'Department 4');
INSERT INTO `department` VALUES (7, 'Department 5');

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
) ENGINE = InnoDB AUTO_INCREMENT = 84 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rate_position
-- ----------------------------
INSERT INTO `rate_position` VALUES (1, 'Mechanical Engineering', 69.00, 552.00, 69.00, 2);
INSERT INTO `rate_position` VALUES (2, 'Authorize Manager Officer', 89.00, 712.00, 89.00, 4);
INSERT INTO `rate_position` VALUES (3, 'Quality Control Manger ', 99.00, 792.00, 99.00, 5);
INSERT INTO `rate_position` VALUES (4, 'Office Staff', 89.00, 712.00, 89.00, 6);
INSERT INTO `rate_position` VALUES (5, 'Safety Practitioner', 78.00, 624.00, 78.00, 4);
INSERT INTO `rate_position` VALUES (9, 'Test', NULL, NULL, NULL, 2);

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
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of under_position
-- ----------------------------
INSERT INTO `under_position` VALUES (3, '23', 23.00, 23.00, NULL, 5);
INSERT INTO `under_position` VALUES (12, 'Test', 23.00, 23.00, NULL, 6);
INSERT INTO `under_position` VALUES (14, 'Senior Position', 80.00, 800.00, NULL, 1);
INSERT INTO `under_position` VALUES (15, 'Junior Position', 70.00, 700.00, NULL, 1);

SET FOREIGN_KEY_CHECKS = 1;
