/*
 Navicat Premium Data Transfer

 Source Server         : PersonalProjectDB
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : admin_login

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 17/11/2024 21:11:22
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
  UNIQUE INDEX `employee_no`(`employee_no` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 48 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of adding_employee
-- ----------------------------
INSERT INTO `adding_employee` VALUES (39, 'EMP-3475', 'Mariano', 'Janine Kaye', 'Binatero', 'janinekaye@gmail.com', 3, '09420542776', 'Sales', '2024-11-13', 'upper bigte', 0x3330, '', 1, '$2y$10$Z.XBqk5LtHToybGYkXkw3uleZNDUZE4KnHjOM2oGnOj/dWqOjhc8m', '0000-00-00', '', '', '', '');
INSERT INTO `adding_employee` VALUES (41, 'EMP-6601', 'Yu', 'King Mark', 'rodriguez', 'kingking2931@gmail.com', 4, '09420542776', 'IT', '2024-11-15', 'blk 16', 0x3330, '', 1, '$2y$10$vEC2xRXTHtQ6cOd/7cMaLuFVskFsY9rZ.85QBsuIW1..Q5xTTp/5i', '0000-00-00', '', '', '', '');
INSERT INTO `adding_employee` VALUES (45, 'EMP-9237', 'Gajultos', 'Garry', 'Dela Torre', 'test1@gmail.com', 5, '23', 'Sales', '2024-12-12', 'ee', '', '', 0, '', '0000-00-00', '', '', '', '');

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (1, 'admin', '0192023a7bbd73250516f069df18b500');
INSERT INTO `admin` VALUES (3, 'kingpogi', 'b2086154f101464aab3328ba7e060deb');

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
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of attendance
-- ----------------------------
INSERT INTO `attendance` VALUES (4, 'EMP-2358', '2024-11-15', '2024-11-15 08:00:00', '2024-11-15 17:19:58', 'Present', '08:00:00', '00:00:00');
INSERT INTO `attendance` VALUES (5, 'EMP-3475', '2024-11-15', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Absent', '00:00:00', '00:00:00');
INSERT INTO `attendance` VALUES (6, 'EMP-6601', '2024-11-15', '2024-11-15 08:00:00', '2024-11-15 17:53:26', 'Late', '08:00:00', '00:00:00');

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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employee_no`(`employee_no` ASC, `date` ASC) USING BTREE,
  CONSTRAINT `attendance_report_ibfk_1` FOREIGN KEY (`employee_no`) REFERENCES `adding_employee` (`employee_no`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of attendance_report
-- ----------------------------
INSERT INTO `attendance_report` VALUES (2, 'EMP-3475', 'Janine Kaye Mariano', 'Absent', '2024-11-15', NULL, NULL, NULL, '00:00:00', '00:00:00');
INSERT INTO `attendance_report` VALUES (3, 'EMP-6601', 'King Mark Yu', 'Late', '2024-11-15', '08:00:00', '17:53:26', '08:00:00', '00:00:00', '00:00:00');

-- ----------------------------
-- Table structure for cash_advance
-- ----------------------------
DROP TABLE IF EXISTS `cash_advance`;
CREATE TABLE `cash_advance`  (
  `cash_advance_jd` int NOT NULL,
  `cash_advance` decimal(11, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`cash_advance_jd`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cash_advance
-- ----------------------------

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
  PRIMARY KEY (`rate_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of rate_position
-- ----------------------------
INSERT INTO `rate_position` VALUES (1, 'Mechanical Engineering', 69.00, 552.00, 69.00);
INSERT INTO `rate_position` VALUES (2, 'Authorize Manager Officer', 89.00, 712.00, 89.00);
INSERT INTO `rate_position` VALUES (3, 'Quality Control Manger ', 99.00, 792.00, 99.00);
INSERT INTO `rate_position` VALUES (4, 'Office Staff', 89.00, 712.00, 89.00);
INSERT INTO `rate_position` VALUES (5, 'Safety Practitioner', 78.00, 624.00, 78.00);

SET FOREIGN_KEY_CHECKS = 1;
