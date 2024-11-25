/*
 Navicat Premium Data Transfer

 Source Server         : PendragonDB
 Source Server Type    : MySQL
 Source Server Version : 100432
 Source Host           : localhost:3306
 Source Schema         : bsu_payroll

 Target Server Type    : MySQL
 Target Server Version : 100432
 File Encoding         : 65001

 Date: 19/11/2024 15:25:43
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for adding_employee
-- ----------------------------
DROP TABLE IF EXISTS `adding_employee`;
CREATE TABLE `adding_employee`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `middle_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `rate_id` int(11) NOT NULL,
  `contact` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `department` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employee_no`(`employee_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 48 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of adding_employee
-- ----------------------------
INSERT INTO `adding_employee` VALUES (39, 'EMP-3475', 'Mariano', 'Janine Kaye', 'Binatero', 'janinekaye@gmail.com', 3, '09420542776', 'Sales', '2024-11-13', 'upper bigte', 0x3330, '', 1, '$2y$10$rXoj3dmEGa36bUH0uQld6ugI/DA83.Uy1jsexMONHPGFR4sBsXBH.', '0000-00-00', '', '', '', '');
INSERT INTO `adding_employee` VALUES (41, 'EMP-6601', 'Yu', 'King Mark', 'rodriguez', 'kingking2931@gmail.com', 4, '09420542776', 'IT', '2024-11-15', 'blk 16', 0x3330, '', 1, '$2y$10$oz1ZZr/loobrcKmANSZxTOdCgEr7FmEk4dqMPafZzakLJpip5.nO6', '0000-00-00', '', '', '', '');
INSERT INTO `adding_employee` VALUES (45, 'EMP-9237', 'Gajultos', 'Garry', 'Dela Torre', 'test1@gmail.com', 5, '23', 'Sales', '2024-12-12', 'ee', '', '', 0, '', '0000-00-00', '', '', '', '');

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `clock_in` datetime(0) NOT NULL,
  `clock_out` datetime(0) NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `worked_time` time(0) NOT NULL,
  `overtime` time(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `employee_no`(`employee_no`) USING BTREE
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `employee_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('Present','Late','Absent') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `time_in` time(0) NULL DEFAULT NULL,
  `time_out` time(0) NULL DEFAULT NULL,
  `actual_time` time(0) NULL DEFAULT NULL,
  `worked_time` time(0) NOT NULL,
  `overtime` time(0) NOT NULL,
  `is_paid` int(11) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employee_no`(`employee_no`, `date`) USING BTREE,
  CONSTRAINT `attendance_report_ibfk_1` FOREIGN KEY (`employee_no`) REFERENCES `adding_employee` (`employee_no`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of attendance_report
-- ----------------------------
INSERT INTO `attendance_report` VALUES (3, 'EMP-6601', 'King Mark Yu', 'Present', '2024-11-15', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 0);
INSERT INTO `attendance_report` VALUES (4, 'EMP-6601', 'King Mark Yu', 'Present', '2024-11-16', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 0);
INSERT INTO `attendance_report` VALUES (5, 'EMP-6601', 'King Mark Yu', 'Late', '2024-11-17', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 0);
INSERT INTO `attendance_report` VALUES (6, 'EMP-6601', 'King Mark Yu', 'Late', '2024-11-18', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 0);
INSERT INTO `attendance_report` VALUES (7, 'EMP-3475', 'Janine Mariano', 'Late', '2024-11-15', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 1);
INSERT INTO `attendance_report` VALUES (8, 'EMP-3475', 'Janine Mariano', 'Late', '2024-11-16', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 1);
INSERT INTO `attendance_report` VALUES (9, 'EMP-3475', 'Janine Mariano', 'Present', '2024-11-17', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00', 1);
INSERT INTO `attendance_report` VALUES (10, 'EMP-3475', 'Janine Mariano', 'Present', '2024-11-18', '08:00:00', '20:53:26', '08:00:00', '00:00:00', '00:00:00', 1);
INSERT INTO `attendance_report` VALUES (11, 'EMP-3475', 'Janine Mariano', 'Present', '2024-11-19', '08:00:00', '18:00:00', '08:00:00', '00:00:00', '00:00:00', 0);

-- ----------------------------
-- Table structure for cash_advance
-- ----------------------------
DROP TABLE IF EXISTS `cash_advance`;
CREATE TABLE `cash_advance`  (
  `cash_advance_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_no` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `requested_amount` decimal(10, 2) NOT NULL,
  `paid_amount` decimal(11, 2) NULL DEFAULT NULL,
  `remaining_balance` decimal(11, 2) NULL DEFAULT NULL,
  `status` enum('Pending','Approved','Partially Paid','Paid','Declined') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'Pending',
  `request_date` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `payment_date` timestamp(0) NULL DEFAULT NULL,
  `months` int(11) NULL DEFAULT NULL,
  `id` int(11) NULL DEFAULT NULL,
  `monthly_payment` decimal(11, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`cash_advance_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cash_advance
-- ----------------------------
INSERT INTO `cash_advance` VALUES (10, 'EMP-3475', 4000.00, NULL, 0.01, 'Paid', '2024-11-19 13:36:34', NULL, 3, 39, 1333.33);

-- ----------------------------
-- Table structure for payroll
-- ----------------------------
DROP TABLE IF EXISTS `payroll`;
CREATE TABLE `payroll`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `rate_per_hour` decimal(10, 2) NULL DEFAULT NULL,
  `basic_per_day` decimal(10, 2) NULL DEFAULT NULL,
  `number_of_days` int(11) NULL DEFAULT NULL,
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
  `total_overtime_hours` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 84 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

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
  `rate_id` int(11) NOT NULL AUTO_INCREMENT,
  `rate_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `rate_per_hour` decimal(11, 2) NULL DEFAULT NULL,
  `rate_per_day` decimal(11, 2) NULL DEFAULT NULL,
  `ot_per_hour` decimal(10, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`rate_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rate_position
-- ----------------------------
INSERT INTO `rate_position` VALUES (1, 'Mechanical Engineering', 69.00, 552.00, 69.00);
INSERT INTO `rate_position` VALUES (2, 'Authorize Manager Officer', 89.00, 712.00, 89.00);
INSERT INTO `rate_position` VALUES (3, 'Quality Control Manger ', 99.00, 792.00, 99.00);
INSERT INTO `rate_position` VALUES (4, 'Office Staff', 89.00, 712.00, 89.00);
INSERT INTO `rate_position` VALUES (5, 'Safety Practitioner', 78.00, 624.00, 78.00);

SET FOREIGN_KEY_CHECKS = 1;
