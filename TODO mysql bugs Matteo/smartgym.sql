/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : smartgym

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-09-17 20:10:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `account`
-- ----------------------------
DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `id_account` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_account`),
  UNIQUE KEY `email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of account
-- ----------------------------
INSERT INTO `account` VALUES ('8', 'Matteo', 'Novembre', 'matteo.novembre@gmail.com', '$pbkdf2-sha256$29000$1RrDWAshRAiB8B7DeA8BQA$CwIk2vTctC6uwnTuUYI8xpN33rSKKqngcEaVpZVmc9M');

-- ----------------------------
-- Table structure for `exercise`
-- ----------------------------
DROP TABLE IF EXISTS `exercise`;
CREATE TABLE `exercise` (
  `id_exercise` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `muscular_zone` text NOT NULL,
  `url` varchar(250) NOT NULL,
  PRIMARY KEY (`id_exercise`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of exercise
-- ----------------------------
INSERT INTO `exercise` VALUES ('1', 'Distensioni con bilancere su panca', 'Improvement of different muscular zones', 'Chest', 'https://www.youtube.com/watch?v=HtA6wD-3bDA');
INSERT INTO `exercise` VALUES ('2', 'Distensioni con manubri su panca inclinata', 'Improvement of different muscular zones', 'Chest', 'https://www.youtube.com/watch?v=HtA6wD-3bDA');
INSERT INTO `exercise` VALUES ('3', 'Butterfly', 'Routine exercise', 'Chest', 'https://www.youtube.com/watch?v=HtA6wD-3bDA');
INSERT INTO `exercise` VALUES ('4', 'French press', 'Biceps development', 'Biceps', 'https://www.youtube.com/watch?v=fkbzIetmK-4');
INSERT INTO `exercise` VALUES ('5', 'Tricipes machine', 'Tricipes develpoment', 'Tricipes', 'https://www.youtube.com/watch?v=HtA6wD-3bDA');
INSERT INTO `exercise` VALUES ('6', 'Lat machine', 'qualcosa5', 'schiena', '');
INSERT INTO `exercise` VALUES ('7', 'Curl bilancere', 'qualcosa6', 'bicipiti', '');
INSERT INTO `exercise` VALUES ('8', 'Leg extension', 'qualcosa7', 'gambe', '');
INSERT INTO `exercise` VALUES ('9', 'Panca hyperextension', 'qualcosa8', 'spalle', '');
INSERT INTO `exercise` VALUES ('10', 'Shoulder press', 'qualcosa9', 'spalle', '');
INSERT INTO `exercise` VALUES ('11', 'prova', 'prova', 'prova', 'https://www.youtube.com/watch?v=Z3fIYNPGFZA');

-- ----------------------------
-- Table structure for `exercise_schedules`
-- ----------------------------
DROP TABLE IF EXISTS `exercise_schedules`;
CREATE TABLE `exercise_schedules` (
  `id_list` int(11) NOT NULL AUTO_INCREMENT,
  `id_schedule` int(11) NOT NULL,
  `id_exercise` int(11) NOT NULL,
  `day` int(11) NOT NULL DEFAULT '1',
  `repetitions` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`id_list`),
  KEY `id_schedule` (`id_schedule`),
  CONSTRAINT `exercise_schedules_ibfk_1` FOREIGN KEY (`id_schedule`) REFERENCES `schedules` (`id_schedule`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of exercise_schedules
-- ----------------------------
INSERT INTO `exercise_schedules` VALUES ('13', '15', '1', '1', '3', '15', 'half contraction');
INSERT INTO `exercise_schedules` VALUES ('15', '15', '3', '2', '4', '20', 'half contraction');
INSERT INTO `exercise_schedules` VALUES ('16', '15', '4', '3', '3', '20', 'max-max stripping ');
INSERT INTO `exercise_schedules` VALUES ('17', '15', '5', '3', '4', '20', 'max ');
INSERT INTO `exercise_schedules` VALUES ('18', '15', '9', '2', '3', '15', 'max ');
INSERT INTO `exercise_schedules` VALUES ('19', '15', '6', '1', '3', '20', 'max ');

-- ----------------------------
-- Table structure for `messages`
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `body` text NOT NULL,
  `send_date` date NOT NULL,
  `destination` text NOT NULL,
  PRIMARY KEY (`id_message`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of messages
-- ----------------------------
INSERT INTO `messages` VALUES ('22', 'Summer Promotion', 'Only for June and July, 40% sales on the subscription.', '2018-05-14', 'all');
INSERT INTO `messages` VALUES ('39', 'Summer Holidays', 'This gym will be closed from 01-08-2018 to 10-09-2018.', '2018-09-15', 'all');

-- ----------------------------
-- Table structure for `room`
-- ----------------------------
DROP TABLE IF EXISTS `room`;
CREATE TABLE `room` (
  `id_room` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id_room`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of room
-- ----------------------------

-- ----------------------------
-- Table structure for `schedules`
-- ----------------------------
DROP TABLE IF EXISTS `schedules`;
CREATE TABLE `schedules` (
  `id_schedule` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `details` varchar(250) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `num_days` int(11) DEFAULT '1',
  `objective` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_schedule`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of schedules
-- ----------------------------
INSERT INTO `schedules` VALUES ('15', '1', 'Split routine', 'Muscle development', '2018-09-13', '2018-10-13', '3', 'Muscle development');

-- ----------------------------
-- Table structure for `subscription`
-- ----------------------------
DROP TABLE IF EXISTS `subscription`;
CREATE TABLE `subscription` (
  `id_subscription` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `price` float NOT NULL,
  PRIMARY KEY (`id_subscription`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of subscription
-- ----------------------------
INSERT INTO `subscription` VALUES ('1', 'daily', '4.99');
INSERT INTO `subscription` VALUES ('2', 'weekly', '9.99');
INSERT INTO `subscription` VALUES ('3', 'monthly', '29.99');
INSERT INTO `subscription` VALUES ('4', 'quarterly', '79.99');
INSERT INTO `subscription` VALUES ('5', 'annual', '299.99');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `surname` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `birth_date` date NOT NULL,
  `address` text NOT NULL,
  `phone` text,
  `subscription` text NOT NULL,
  `end_subscription` date NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'Matteo', 'Novembre', 'matteo.novembre94@gmail.com', '1234', '1994-08-06', 'Via Quarnaro I, n.41', '0', '6', '2018-09-10');
INSERT INTO `user` VALUES ('2', 'Irene', 'Raimondi', 'ireraim@gmail.com', '$2y$10$.ahTbMa.wqeVje48mgYK1uJzwapMBXCSUxcb4qPuGo9NvUYm1fSRa', '1991-07-14', 'Via Monte Grappa 61', '0', '2', '2018-09-10');
INSERT INTO `user` VALUES ('3', 'Sergio', 'Micalizzi', 'sergio.micalizzi@gmail.com', '$2y$10$CTDkeY8TYUwybx5qJqGr9uvEwclgIg6Qd13xRZbu8/gBskgnims5G', '1992-01-01', 'via prova', '0', 'weekly', '2018-09-21');
INSERT INTO `user` VALUES ('4', 'prova', 'prova', 'prova@gmail.com', '$2y$10$3CUAzQzHMRa5JruTJhxQhOh1QMPIySjn0mqtkCeRXyE1G1XBq/UJO', '1900-06-06', 'prova', '3333333333', 'daily', '2018-09-16');
