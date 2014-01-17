# phpMyAdmin SQL Dump
# version 2.5.2-pl1
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Jun 03, 2008 at 12:19 AM
# Server version: 4.1.20
# PHP Version: 4.4.8
# 
# Database : `test_db`
# 

# --------------------------------------------------------

#
# Table structure for table `branch_index`
#
# Creation: Apr 30, 2008 at 08:06 PM
#

USE creativechimp;

CREATE TABLE `branch_index` (
  `branch_id` tinyint(2) unsigned NOT NULL auto_increment,
  `branch_name` varchar(15) default NULL,
  `country_id` tinyint(3) unsigned default NULL,
  PRIMARY KEY  (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

#
# Dumping data for table `branch_index`
#

INSERT INTO `branch_index` VALUES (1, 'Koh Phangan', 222);
INSERT INTO `branch_index` VALUES (2, 'Chiang Mai', 222);
INSERT INTO `branch_index` VALUES (3, 'Rishikesh', 107);
INSERT INTO `branch_index` VALUES (4, 'Dharamsala', 107);
INSERT INTO `branch_index` VALUES (5, 'Nepal', 159);
INSERT INTO `branch_index` VALUES (6, 'Vancouver', 43);
INSERT INTO `branch_index` VALUES (7, 'Ottawa', 43);
INSERT INTO `branch_index` VALUES (8, 'United Kingdom', 235);

# --------------------------------------------------------

#
# Table structure for table `country_index`
#
# Creation: Apr 29, 2008 at 04:33 AM
#

CREATE TABLE `country_index` (
  `country_id` tinyint(3) unsigned NOT NULL auto_increment,
  `country_name` varchar(40) default NULL,
  PRIMARY KEY  (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=251 ;

#
# Dumping data for table `country_index`
#

INSERT INTO `country_index` VALUES (1, 'Unknown');
INSERT INTO `country_index` VALUES (2, 'Afghanistan');
INSERT INTO `country_index` VALUES (3, 'Africa');
INSERT INTO `country_index` VALUES (4, 'Albania');
INSERT INTO `country_index` VALUES (5, 'Algeria');
INSERT INTO `country_index` VALUES (6, 'American Samoa');
INSERT INTO `country_index` VALUES (7, 'Andorra');
INSERT INTO `country_index` VALUES (8, 'Angola');
INSERT INTO `country_index` VALUES (9, 'Anguilla');
INSERT INTO `country_index` VALUES (10, 'Antarctica');
INSERT INTO `country_index` VALUES (11, 'Antigua & Barbuda');
INSERT INTO `country_index` VALUES (12, 'Antilles, Netherlands');
INSERT INTO `country_index` VALUES (13, 'Arabia, Saudi');
INSERT INTO `country_index` VALUES (14, 'Argentina');
INSERT INTO `country_index` VALUES (15, 'Armenia');
INSERT INTO `country_index` VALUES (16, 'Aruba');
INSERT INTO `country_index` VALUES (17, 'Australia');
INSERT INTO `country_index` VALUES (18, 'Austria');
INSERT INTO `country_index` VALUES (19, 'Azerbaijan');
INSERT INTO `country_index` VALUES (20, 'Bahamas, The');
INSERT INTO `country_index` VALUES (21, 'Bahrain');
INSERT INTO `country_index` VALUES (22, 'Bangladesh');
INSERT INTO `country_index` VALUES (23, 'Barbados');
INSERT INTO `country_index` VALUES (24, 'Belarus');
INSERT INTO `country_index` VALUES (25, 'Belgium');
INSERT INTO `country_index` VALUES (26, 'Belize');
INSERT INTO `country_index` VALUES (27, 'Benin');
INSERT INTO `country_index` VALUES (28, 'Bermuda');
INSERT INTO `country_index` VALUES (29, 'Bhutan');
INSERT INTO `country_index` VALUES (30, 'Bolivia');
INSERT INTO `country_index` VALUES (31, 'Bosnia and Herzegovina');
INSERT INTO `country_index` VALUES (32, 'Botswana');
INSERT INTO `country_index` VALUES (33, 'Bouvet Island');
INSERT INTO `country_index` VALUES (34, 'Brazil');
INSERT INTO `country_index` VALUES (35, 'British Indian Ocean Territory');
INSERT INTO `country_index` VALUES (36, 'British Virgin Islands');
INSERT INTO `country_index` VALUES (37, 'Brunei Darussalam');
INSERT INTO `country_index` VALUES (38, 'Bulgaria');
INSERT INTO `country_index` VALUES (39, 'Burkina Faso');
INSERT INTO `country_index` VALUES (40, 'Burundi');
INSERT INTO `country_index` VALUES (41, 'Cambodia');
INSERT INTO `country_index` VALUES (42, 'Cameroon');
INSERT INTO `country_index` VALUES (43, 'Canada');
INSERT INTO `country_index` VALUES (44, 'Cape Verde');
INSERT INTO `country_index` VALUES (45, 'Cayman Islands');
INSERT INTO `country_index` VALUES (46, 'Central African Republic');
INSERT INTO `country_index` VALUES (47, 'Chad');
INSERT INTO `country_index` VALUES (48, 'Chile');
INSERT INTO `country_index` VALUES (49, 'China');
INSERT INTO `country_index` VALUES (50, 'Christmas Island');
INSERT INTO `country_index` VALUES (51, 'Cocos (Keeling) Islands');
INSERT INTO `country_index` VALUES (52, 'Colombia');
INSERT INTO `country_index` VALUES (53, 'Comoros');
INSERT INTO `country_index` VALUES (54, 'Congo');
INSERT INTO `country_index` VALUES (55, 'Congo, Democratic Rep. of the');
INSERT INTO `country_index` VALUES (56, 'Cook Islands');
INSERT INTO `country_index` VALUES (57, 'Costa Rica');
INSERT INTO `country_index` VALUES (58, 'Cote D\'Ivoire');
INSERT INTO `country_index` VALUES (59, 'Croatia');
INSERT INTO `country_index` VALUES (60, 'Cuba');
INSERT INTO `country_index` VALUES (61, 'Cyprus');
INSERT INTO `country_index` VALUES (62, 'Czech Republic');
INSERT INTO `country_index` VALUES (63, 'Denmark');
INSERT INTO `country_index` VALUES (64, 'Djibouti');
INSERT INTO `country_index` VALUES (65, 'Dominica');
INSERT INTO `country_index` VALUES (66, 'Dominican Republic');
INSERT INTO `country_index` VALUES (67, 'East Timor (Timor-Leste)');
INSERT INTO `country_index` VALUES (68, 'Ecuador');
INSERT INTO `country_index` VALUES (69, 'Egypt');
INSERT INTO `country_index` VALUES (70, 'El Salvador');
INSERT INTO `country_index` VALUES (71, 'Equatorial Guinea');
INSERT INTO `country_index` VALUES (72, 'Eritrea');
INSERT INTO `country_index` VALUES (73, 'Estonia');
INSERT INTO `country_index` VALUES (74, 'Ethiopia');
INSERT INTO `country_index` VALUES (75, 'European Union');
INSERT INTO `country_index` VALUES (76, 'Falkland Islands (Malvinas)');
INSERT INTO `country_index` VALUES (77, 'Faroe Islands');
INSERT INTO `country_index` VALUES (78, 'Fiji');
INSERT INTO `country_index` VALUES (79, 'Finland');
INSERT INTO `country_index` VALUES (80, 'France');
INSERT INTO `country_index` VALUES (81, 'French Guiana');
INSERT INTO `country_index` VALUES (82, 'French Polynesia');
INSERT INTO `country_index` VALUES (83, 'French Southern Territories - TF');
INSERT INTO `country_index` VALUES (84, 'Gabon');
INSERT INTO `country_index` VALUES (85, 'Gambia, the');
INSERT INTO `country_index` VALUES (86, 'Georgia');
INSERT INTO `country_index` VALUES (87, 'Germany');
INSERT INTO `country_index` VALUES (88, 'Ghana');
INSERT INTO `country_index` VALUES (89, 'Gibraltar');
INSERT INTO `country_index` VALUES (90, 'Greece');
INSERT INTO `country_index` VALUES (91, 'Greenland');
INSERT INTO `country_index` VALUES (92, 'Grenada');
INSERT INTO `country_index` VALUES (93, 'Guadeloupe');
INSERT INTO `country_index` VALUES (94, 'Guam');
INSERT INTO `country_index` VALUES (95, 'Guatemala');
INSERT INTO `country_index` VALUES (96, 'Guernsey and Alderney');
INSERT INTO `country_index` VALUES (97, 'Guinea');
INSERT INTO `country_index` VALUES (98, 'Guinea-Bissau');
INSERT INTO `country_index` VALUES (99, 'Guinea, Equatorial');
INSERT INTO `country_index` VALUES (100, 'Guiana, French');
INSERT INTO `country_index` VALUES (101, 'Guyana');
INSERT INTO `country_index` VALUES (102, 'Haiti');
INSERT INTO `country_index` VALUES (103, 'Heard and McDonald Islands');
INSERT INTO `country_index` VALUES (104, 'Honduras');
INSERT INTO `country_index` VALUES (105, 'Hungary');
INSERT INTO `country_index` VALUES (106, 'Iceland');
INSERT INTO `country_index` VALUES (107, 'India');
INSERT INTO `country_index` VALUES (108, 'Indonesia');
INSERT INTO `country_index` VALUES (109, 'Iran, Islamic Republic of');
INSERT INTO `country_index` VALUES (110, 'Iraq');
INSERT INTO `country_index` VALUES (111, 'Ireland');
INSERT INTO `country_index` VALUES (112, 'Israel');
INSERT INTO `country_index` VALUES (113, 'Italy');
INSERT INTO `country_index` VALUES (114, 'Jamaica');
INSERT INTO `country_index` VALUES (115, 'Japan');
INSERT INTO `country_index` VALUES (116, 'Jersey');
INSERT INTO `country_index` VALUES (117, 'Jordan');
INSERT INTO `country_index` VALUES (118, 'Kazakhstan');
INSERT INTO `country_index` VALUES (119, 'Kenya');
INSERT INTO `country_index` VALUES (120, 'Kiribati');
INSERT INTO `country_index` VALUES (121, 'Korea, Demo. People\'s Rep. of');
INSERT INTO `country_index` VALUES (122, 'Korea, (South) Republic of');
INSERT INTO `country_index` VALUES (123, 'Kuwait');
INSERT INTO `country_index` VALUES (124, 'Kyrgyzstan');
INSERT INTO `country_index` VALUES (125, 'Lao');
INSERT INTO `country_index` VALUES (126, 'Latvia');
INSERT INTO `country_index` VALUES (127, 'Lebanon');
INSERT INTO `country_index` VALUES (128, 'Lesotho');
INSERT INTO `country_index` VALUES (129, 'Liberia');
INSERT INTO `country_index` VALUES (130, 'Libyan Arab Jamahiriya');
INSERT INTO `country_index` VALUES (131, 'Liechtenstein');
INSERT INTO `country_index` VALUES (132, 'Lithuania');
INSERT INTO `country_index` VALUES (133, 'Luxembourg');
INSERT INTO `country_index` VALUES (134, 'Macedonia, TFYR');
INSERT INTO `country_index` VALUES (135, 'Madagascar');
INSERT INTO `country_index` VALUES (136, 'Malawi');
INSERT INTO `country_index` VALUES (137, 'Malaysia');
INSERT INTO `country_index` VALUES (138, 'Maldives');
INSERT INTO `country_index` VALUES (139, 'Mali');
INSERT INTO `country_index` VALUES (140, 'Malta');
INSERT INTO `country_index` VALUES (141, 'Man, Isle of');
INSERT INTO `country_index` VALUES (142, 'Marshall Islands');
INSERT INTO `country_index` VALUES (143, 'Martinique');
INSERT INTO `country_index` VALUES (144, 'Mauritania');
INSERT INTO `country_index` VALUES (145, 'Mauritius');
INSERT INTO `country_index` VALUES (146, 'Mayotte');
INSERT INTO `country_index` VALUES (147, 'Mexico');
INSERT INTO `country_index` VALUES (148, 'Micronesia, Federated States of');
INSERT INTO `country_index` VALUES (149, 'Moldova, Republic of');
INSERT INTO `country_index` VALUES (150, 'Monaco');
INSERT INTO `country_index` VALUES (151, 'Mongolia');
INSERT INTO `country_index` VALUES (152, 'Montenegro');
INSERT INTO `country_index` VALUES (153, 'Montserrat');
INSERT INTO `country_index` VALUES (154, 'Morocco');
INSERT INTO `country_index` VALUES (155, 'Mozambique');
INSERT INTO `country_index` VALUES (156, 'Myanmar (ex-Burma)');
INSERT INTO `country_index` VALUES (157, 'Namibia');
INSERT INTO `country_index` VALUES (158, 'Nauru');
INSERT INTO `country_index` VALUES (159, 'Nepal');
INSERT INTO `country_index` VALUES (160, 'Netherlands');
INSERT INTO `country_index` VALUES (161, 'Netherlands Antilles');
INSERT INTO `country_index` VALUES (162, 'New Caledonia');
INSERT INTO `country_index` VALUES (163, 'New Zealand');
INSERT INTO `country_index` VALUES (164, 'Nicaragua');
INSERT INTO `country_index` VALUES (165, 'Niger');
INSERT INTO `country_index` VALUES (166, 'Nigeria');
INSERT INTO `country_index` VALUES (167, 'Niue');
INSERT INTO `country_index` VALUES (168, 'Norfolk Island');
INSERT INTO `country_index` VALUES (169, 'Northern Mariana Islands');
INSERT INTO `country_index` VALUES (170, 'Norway');
INSERT INTO `country_index` VALUES (171, 'Oman');
INSERT INTO `country_index` VALUES (172, 'Pakistan');
INSERT INTO `country_index` VALUES (173, 'Palau');
INSERT INTO `country_index` VALUES (174, 'Palestinian Territory');
INSERT INTO `country_index` VALUES (175, 'Panama');
INSERT INTO `country_index` VALUES (176, 'Papua New Guinea');
INSERT INTO `country_index` VALUES (177, 'Paraguay');
INSERT INTO `country_index` VALUES (178, 'Peru');
INSERT INTO `country_index` VALUES (179, 'Philippines');
INSERT INTO `country_index` VALUES (180, 'Pitcairn Island');
INSERT INTO `country_index` VALUES (181, 'Poland');
INSERT INTO `country_index` VALUES (182, 'Portugal');
INSERT INTO `country_index` VALUES (183, 'Puerto Rico');
INSERT INTO `country_index` VALUES (184, 'Qatar');
INSERT INTO `country_index` VALUES (185, 'Reunion');
INSERT INTO `country_index` VALUES (186, 'Romania');
INSERT INTO `country_index` VALUES (187, 'Russia (Russian Federation)');
INSERT INTO `country_index` VALUES (188, 'Rwanda');
INSERT INTO `country_index` VALUES (189, 'Sahara');
INSERT INTO `country_index` VALUES (190, 'Saint Helena');
INSERT INTO `country_index` VALUES (191, 'Saint Kitts and Nevis');
INSERT INTO `country_index` VALUES (192, 'Saint Lucia');
INSERT INTO `country_index` VALUES (193, 'Saint Pierre and Miquelon');
INSERT INTO `country_index` VALUES (194, 'Saint Vincent and the Grenadines');
INSERT INTO `country_index` VALUES (195, 'Samoa');
INSERT INTO `country_index` VALUES (196, 'San Marino');
INSERT INTO `country_index` VALUES (197, 'Sao Tome and Principe');
INSERT INTO `country_index` VALUES (198, 'Saudi Arabia');
INSERT INTO `country_index` VALUES (199, 'Senegal');
INSERT INTO `country_index` VALUES (200, 'Serbia');
INSERT INTO `country_index` VALUES (201, 'Seychelles');
INSERT INTO `country_index` VALUES (202, 'Sierra Leone');
INSERT INTO `country_index` VALUES (203, 'Singapore');
INSERT INTO `country_index` VALUES (204, 'Slovakia');
INSERT INTO `country_index` VALUES (205, 'Slovenia');
INSERT INTO `country_index` VALUES (206, 'Solomon Islands');
INSERT INTO `country_index` VALUES (207, 'Somalia');
INSERT INTO `country_index` VALUES (208, 'South Africa');
INSERT INTO `country_index` VALUES (209, 'S. Georgia and S. Sandwich Is.');
INSERT INTO `country_index` VALUES (210, 'Spain');
INSERT INTO `country_index` VALUES (211, 'Sri Lanka');
INSERT INTO `country_index` VALUES (212, 'Sudan');
INSERT INTO `country_index` VALUES (213, 'Suriname');
INSERT INTO `country_index` VALUES (214, 'Svalbard and Jan Mayen Islands');
INSERT INTO `country_index` VALUES (215, 'Swaziland');
INSERT INTO `country_index` VALUES (216, 'Sweden');
INSERT INTO `country_index` VALUES (217, 'Switzerland');
INSERT INTO `country_index` VALUES (218, 'Syrian Arab Republic');
INSERT INTO `country_index` VALUES (219, 'Taiwan');
INSERT INTO `country_index` VALUES (220, 'Tajikistan');
INSERT INTO `country_index` VALUES (221, 'Tanzania, United Republic of');
INSERT INTO `country_index` VALUES (222, 'Thailand');
INSERT INTO `country_index` VALUES (223, 'Togo');
INSERT INTO `country_index` VALUES (224, 'Tokelau');
INSERT INTO `country_index` VALUES (225, 'Tonga');
INSERT INTO `country_index` VALUES (226, 'Trinidad & Tobago');
INSERT INTO `country_index` VALUES (227, 'Tunisia');
INSERT INTO `country_index` VALUES (228, 'Turkey');
INSERT INTO `country_index` VALUES (229, 'Turkmenistan');
INSERT INTO `country_index` VALUES (230, 'Turks and Caicos Islands');
INSERT INTO `country_index` VALUES (231, 'Tuvalu');
INSERT INTO `country_index` VALUES (232, 'Uganda');
INSERT INTO `country_index` VALUES (233, 'Ukraine');
INSERT INTO `country_index` VALUES (234, 'United Arab Emirates');
INSERT INTO `country_index` VALUES (235, 'United Kingdom');
INSERT INTO `country_index` VALUES (236, 'United States');
INSERT INTO `country_index` VALUES (237, 'US Minor Outlying Islands');
INSERT INTO `country_index` VALUES (238, 'Uruguay');
INSERT INTO `country_index` VALUES (239, 'Uzbekistan');
INSERT INTO `country_index` VALUES (240, 'Vanuatu');
INSERT INTO `country_index` VALUES (241, 'Vatican City State (Holy See)');
INSERT INTO `country_index` VALUES (242, 'Venezuela');
INSERT INTO `country_index` VALUES (243, 'Viet Nam');
INSERT INTO `country_index` VALUES (244, 'Virgin Islands, British');
INSERT INTO `country_index` VALUES (245, 'Virgin Islands, U.S.');
INSERT INTO `country_index` VALUES (246, 'Wallis and Futuna');
INSERT INTO `country_index` VALUES (247, 'Western Sahara');
INSERT INTO `country_index` VALUES (248, 'Yemen');
INSERT INTO `country_index` VALUES (249, 'Zambia');
INSERT INTO `country_index` VALUES (250, 'Zimbabwe');

# --------------------------------------------------------

#
# Table structure for table `course_index`
#
# Creation: May 03, 2008 at 01:33 AM
#

CREATE TABLE `course_index` (
  `course_id` smallint(3) unsigned NOT NULL default '0',
  `category` varchar(20) default NULL,
  `course_name` varchar(20) default NULL,
  PRIMARY KEY  (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Dumping data for table `course_index`
#

INSERT INTO `course_index` VALUES (1, 'Hatha', '1');
INSERT INTO `course_index` VALUES (2, 'Hatha', '2');
INSERT INTO `course_index` VALUES (3, 'Hatha', '3');
INSERT INTO `course_index` VALUES (4, 'Hatha', '4');
INSERT INTO `course_index` VALUES (5, 'Hatha', '5');
INSERT INTO `course_index` VALUES (6, 'Hatha', '6');
INSERT INTO `course_index` VALUES (7, 'Hatha', '7');
INSERT INTO `course_index` VALUES (8, 'Hatha', '8');
INSERT INTO `course_index` VALUES (9, 'Hatha', '9');
INSERT INTO `course_index` VALUES (10, 'Hatha', '10');
INSERT INTO `course_index` VALUES (11, 'Hatha', '11');
INSERT INTO `course_index` VALUES (12, 'Hatha', '12');
INSERT INTO `course_index` VALUES (13, 'Hatha', '13');
INSERT INTO `course_index` VALUES (14, 'Hatha', '14');
INSERT INTO `course_index` VALUES (15, 'Kundalini', '15');
INSERT INTO `course_index` VALUES (16, 'Kundalini', '16');
INSERT INTO `course_index` VALUES (17, 'Kundalini', '17');
INSERT INTO `course_index` VALUES (18, 'Kundalini', '18');
INSERT INTO `course_index` VALUES (19, 'Kundalini', '19');
INSERT INTO `course_index` VALUES (20, 'Kundalini', '20');
INSERT INTO `course_index` VALUES (21, 'Kundalini', '21');
INSERT INTO `course_index` VALUES (22, 'Kundalini', '22');
INSERT INTO `course_index` VALUES (23, 'Kundalini', '23');
INSERT INTO `course_index` VALUES (24, 'Kundalini', '24');
INSERT INTO `course_index` VALUES (101, 'Tapas', 'Muladhara');
INSERT INTO `course_index` VALUES (102, 'Tapas', 'Swadhisthana');
INSERT INTO `course_index` VALUES (103, 'Tapas', 'Manipura');
INSERT INTO `course_index` VALUES (104, 'Tapas', 'Anahata');
INSERT INTO `course_index` VALUES (105, 'Tapas', 'Vishuddha');
INSERT INTO `course_index` VALUES (106, 'Tapas', 'Ajna');
INSERT INTO `course_index` VALUES (107, 'Tapas', 'Sahasrara');
INSERT INTO `course_index` VALUES (201, 'Kashmir Shaivism', 'Study');
INSERT INTO `course_index` VALUES (301, 'Advanced', 'Study');

# --------------------------------------------------------

#
# Table structure for table `feedback_firstmonth_general`
#
# Creation: Apr 30, 2008 at 08:14 PM
#

CREATE TABLE `feedback_firstmonth_general` (
  `id` mediumint(5) unsigned NOT NULL auto_increment,
  `email` varchar(40) default NULL,
  `referrer` varchar(20) default NULL,
  `overall_exp` tinyint(1) unsigned default NULL,
  `overall_comments` text,
  `best_elements` text,
  `needs_improvement` text,
  `tohelp_newstudents` text,
  `teachings_quality` tinyint(1) unsigned default NULL,
  `teachings_quality_comments` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

#
# Dumping data for table `feedback_firstmonth_general`
#

INSERT INTO `feedback_firstmonth_general` VALUES (2, 'n/a', 'From An Agama Studen', 3, 'overall great', 'bestX\r\nbestY\r\nbestZ', 'worstA\r\nworstB\r\nworstC', 'can\'t help others if you don\'t help yourself', 2, 'just kidding, its all good');
INSERT INTO `feedback_firstmonth_general` VALUES (4, 'n/a', 'Lonely Planet', 5, '', '', '', '', NULL, '');

# --------------------------------------------------------

#
# Table structure for table `feedback_firstmonth_teachers`
#
# Creation: Apr 30, 2008 at 08:15 PM
#

CREATE TABLE `feedback_firstmonth_teachers` (
  `form_id` mediumint(5) unsigned NOT NULL auto_increment,
  `email` varchar(40) default NULL,
  `firstname` varchar(20) default NULL,
  `surname` varchar(30) default NULL,
  `fav` tinyint(1) unsigned default NULL,
  `hatha` tinyint(1) unsigned default NULL,
  `lecture` tinyint(1) unsigned default NULL,
  `comments` text,
  PRIMARY KEY  (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

#
# Dumping data for table `feedback_firstmonth_teachers`
#


# --------------------------------------------------------

#
# Table structure for table `sash_index`
#
# Creation: May 06, 2008 at 10:07 PM
#

CREATE TABLE `sash_index` (
  `sash_id` tinyint(1) unsigned NOT NULL auto_increment,
  `sash_color` varchar(15) default NULL,
  PRIMARY KEY  (`sash_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

#
# Dumping data for table `sash_index`
#

INSERT INTO `sash_index` VALUES (1, 'No Sash Status');
INSERT INTO `sash_index` VALUES (2, 'Red');
INSERT INTO `sash_index` VALUES (3, 'Orange');
INSERT INTO `sash_index` VALUES (4, 'Yellow');
INSERT INTO `sash_index` VALUES (5, 'Green');
INSERT INTO `sash_index` VALUES (6, 'Blue');
INSERT INTO `sash_index` VALUES (7, 'Violet');

# --------------------------------------------------------

#
# Table structure for table `students_astrological`
#
# Creation: Apr 29, 2008 at 03:18 AM
#

CREATE TABLE `students_astrological` (
  `student_id` smallint(6) NOT NULL default '0',
  `sunsign` varchar(12) default NULL,
  `ascendant` varchar(12) default NULL,
  `moon` varchar(12) default NULL,
  `mercury` varchar(12) default NULL,
  `venus` varchar(12) default NULL,
  `mars` varchar(12) default NULL,
  `jupiter` varchar(12) default NULL,
  `saturn` varchar(12) default NULL,
  `uranus` varchar(12) default NULL,
  `neptune` varchar(12) default NULL,
  `pluto` varchar(12) default NULL,
  PRIMARY KEY  (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Dumping data for table `students_astrological`
#

INSERT INTO `students_astrological` VALUES (0, 'gemini', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (15, 'capricorn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (16, 'cancer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (17, 'aquarius', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (18, 'taurus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (19, 'aquarius', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (20, 'taurus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (21, 'libra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (22, 'gemeni', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (29, 'leo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (30, 'capricorn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (32, 'gemini', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (33, 'gemini', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (37, 'leo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `students_astrological` VALUES (38, 'aquarius', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

# --------------------------------------------------------

#
# Table structure for table `students_basic`
#
# Creation: May 31, 2008 at 07:18 AM
#

CREATE TABLE `students_basic` (
  `counter` smallint(5) unsigned NOT NULL auto_increment,
  `student_id` smallint(5) unsigned NOT NULL default '0',
  `firstname` varchar(15) default NULL,
  `surname` varchar(15) default NULL,
  `email` varchar(40) default NULL,
  `gender` varchar(10) default NULL,
  `country_id` smallint(3) unsigned default '0',
  `passport` bigint(12) unsigned default NULL,
  `occupation` varchar(30) default NULL,
  PRIMARY KEY  (`counter`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

#
# Dumping data for table `students_basic`
#

INSERT INTO `students_basic` VALUES (1, 15, 'Rowen', 'Marks', 'rowenm@gmail.com', 'female', 236, 94546563, '');
INSERT INTO `students_basic` VALUES (2, 16, 'Michael', 'Dunn', 'spandashakti@yahoo.com', 'male', 208, 65765865, 'blah');
INSERT INTO `students_basic` VALUES (3, 17, 'Justine', 'Baruch', 'baruchjustine@gmail.com', 'female', 236, 0, 'teacher');
INSERT INTO `students_basic` VALUES (4, 18, 'Lori', 'Doyle', 'lori@agamayoga.com', 'female', 236, 0, 'angel');
INSERT INTO `students_basic` VALUES (5, 19, 'Tiffany', 'Patrella', 'tiffany.patrella@gmail.com', 'female', 236, 0, 'maha shakti');
INSERT INTO `students_basic` VALUES (6, 20, 'Andrew', 'Reece', 'areece@alumni.bates.edu', 'male', 236, 203006432, 'student');
INSERT INTO `students_basic` VALUES (7, 21, 'Allison', 'Reece', 'allie.reece@gmail.com', 'female', 236, 19238749871, 'sister extraordinaire');
INSERT INTO `students_basic` VALUES (8, 22, 'Joe', 'Schmoe', 'joshmo@gmail.com', 'male', 17, 0, '');
INSERT INTO `students_basic` VALUES (10, 31, 'Arturo', 'Kline', 'klineinator@freemail.com', 'male', 113, 0, '');
INSERT INTO `students_basic` VALUES (15, 32, 'alice', 'mccoy', 'alicelite@yahoo.co.uk', 'female', 235, NULL, NULL);
INSERT INTO `students_basic` VALUES (16, 33, 'skipper', 'mccoy', 'skipster@yahoo.co.uk', 'male', 235, NULL, NULL);
INSERT INTO `students_basic` VALUES (17, 37, 'marky', 'markusson', 'funkybunch@gmail.com', 'male', 236, NULL, NULL);
INSERT INTO `students_basic` VALUES (18, 38, 'walter', 'shicko', 'walttheman@bates.edu', 'male', 236, NULL, NULL);

# --------------------------------------------------------

#
# Table structure for table `students_birthinfo`
#
# Creation: May 06, 2008 at 10:28 AM
#

CREATE TABLE `students_birthinfo` (
  `student_id` smallint(6) NOT NULL default '0',
  `birth_date` date default NULL,
  `birth_time` varchar(5) default NULL,
  `birth_city` varchar(20) default NULL,
  `birth_state` varchar(20) default NULL,
  `birth_country` smallint(3) unsigned default NULL,
  PRIMARY KEY  (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Dumping data for table `students_birthinfo`
#

INSERT INTO `students_birthinfo` VALUES (0, '1984-10-08', '07:36', 'princeton', 'nj', 236);
INSERT INTO `students_birthinfo` VALUES (15, '1982-01-05', '10:32', 'Ann Arbor', 'Michigan', 236);
INSERT INTO `students_birthinfo` VALUES (16, '1979-06-23', '07:14', 'bsdf', 'fdfdfsdf', 208);
INSERT INTO `students_birthinfo` VALUES (17, '1981-02-17', '12:11', '', '', 236);
INSERT INTO `students_birthinfo` VALUES (18, '1967-05-18', 'XX:XX', '', '', 0);
INSERT INTO `students_birthinfo` VALUES (19, '1978-02-03', 'XX:XX', '', '', 0);
INSERT INTO `students_birthinfo` VALUES (20, '1979-05-17', '11:26', 'Princeton', 'NJ', 236);
INSERT INTO `students_birthinfo` VALUES (21, '1984-10-08', 'XX:XX', 'Princeton', 'NJ', 236);
INSERT INTO `students_birthinfo` VALUES (22, '1980-06-13', '13:', '', '', 0);
INSERT INTO `students_birthinfo` VALUES (29, '1916-08-16', ':', '', '', 0);
INSERT INTO `students_birthinfo` VALUES (30, '1977-01-17', '15:', '', '', 0);
INSERT INTO `students_birthinfo` VALUES (32, '1979-06-05', NULL, NULL, NULL, NULL);
INSERT INTO `students_birthinfo` VALUES (33, '1979-06-05', NULL, NULL, NULL, NULL);
INSERT INTO `students_birthinfo` VALUES (37, '1970-08-21', NULL, NULL, NULL, NULL);
INSERT INTO `students_birthinfo` VALUES (38, '1979-02-05', NULL, NULL, NULL, NULL);

# --------------------------------------------------------

#
# Table structure for table `students_comments`
#
# Creation: Apr 30, 2008 at 11:58 PM
#

CREATE TABLE `students_comments` (
  `student_id` smallint(5) unsigned NOT NULL default '0',
  `comments` text,
  PRIMARY KEY  (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Dumping data for table `students_comments`
#

INSERT INTO `students_comments` VALUES (0, 'Allie is my sister.  She rocks.');
INSERT INTO `students_comments` VALUES (16, 'lklkj');
INSERT INTO `students_comments` VALUES (17, 'justine is a little cutie.');
INSERT INTO `students_comments` VALUES (22, 'joe is a schmoe.');

# --------------------------------------------------------

#
# Table structure for table `students_currentstatus`
#
# Creation: Apr 30, 2008 at 08:11 PM
#

CREATE TABLE `students_currentstatus` (
  `student_id` smallint(5) unsigned NOT NULL default '0',
  `current_month` tinyint(2) unsigned default NULL,
  `current_branch` tinyint(2) unsigned default NULL,
  `current_sash` tinyint(1) unsigned default NULL,
  `is_volunteer` tinyint(1) default NULL,
  `is_teacher` tinyint(1) default NULL,
  PRIMARY KEY  (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Dumping data for table `students_currentstatus`
#

INSERT INTO `students_currentstatus` VALUES (0, 2, 1, 1, 0, 0);
INSERT INTO `students_currentstatus` VALUES (15, 12, 1, 2, 0, 1);
INSERT INTO `students_currentstatus` VALUES (16, 101, 1, 7, 1, 1);
INSERT INTO `students_currentstatus` VALUES (17, 20, 1, 4, 1, 1);
INSERT INTO `students_currentstatus` VALUES (18, 105, 1, 4, 1, 1);
INSERT INTO `students_currentstatus` VALUES (19, 10, 1, 3, 1, 1);
INSERT INTO `students_currentstatus` VALUES (20, 14, 1, 4, 1, 1);
INSERT INTO `students_currentstatus` VALUES (21, 3, 3, 1, 0, 0);
INSERT INTO `students_currentstatus` VALUES (22, 12, 1, 3, 1, 0);
INSERT INTO `students_currentstatus` VALUES (29, 13, 1, 3, 1, 0);
INSERT INTO `students_currentstatus` VALUES (30, 8, 8, 2, 0, 0);
INSERT INTO `students_currentstatus` VALUES (32, 2, 1, NULL, NULL, NULL);
INSERT INTO `students_currentstatus` VALUES (33, 2, 1, NULL, NULL, NULL);
INSERT INTO `students_currentstatus` VALUES (37, 3, 1, NULL, NULL, NULL);
INSERT INTO `students_currentstatus` VALUES (38, 9, 1, NULL, NULL, NULL);

# --------------------------------------------------------

#
# Table structure for table `students_emergencycontact`
#
# Creation: Apr 29, 2008 at 03:19 AM
#

CREATE TABLE `students_emergencycontact` (
  `student_id` smallint(6) NOT NULL default '0',
  `emergency_name` varchar(30) default NULL,
  `emergency_phone_home` varchar(15) default NULL,
  `emergency_phone_work` varchar(15) default NULL,
  `emergency_phone_mobile` varchar(15) default NULL,
  `emergency_email` varchar(40) default NULL,
  `emergency_address` varchar(50) default NULL,
  `emergency_relatedhow` varchar(20) default NULL,
  PRIMARY KEY  (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Dumping data for table `students_emergencycontact`
#

INSERT INTO `students_emergencycontact` VALUES (15, 'Parris Marks', '+(919)9426086', '+()', '+()', 'parrislynn@gmail.com', '', 'relative');
INSERT INTO `students_emergencycontact` VALUES (16, 'dsfsdf', '+(434)2342342', '+27214389615', '+()', 'spandashakti@gmail.com', '2342342', 'unknown');
INSERT INTO `students_emergencycontact` VALUES (20, 'mary reece', '+(1)9088747814', '+(1)9082028917', '+(1)9082028917', 'mmreece@patmedia.net', '19 norfolk way skillman, nj 08558 USA', 'relative');
INSERT INTO `students_emergencycontact` VALUES (21, 'mary reece', NULL, NULL, NULL, NULL, NULL, NULL);

# --------------------------------------------------------

#
# Table structure for table `students_local`
#
# Creation: May 27, 2008 at 07:10 AM
#

CREATE TABLE `students_local` (
  `unique_identifier` smallint(5) unsigned NOT NULL auto_increment,
  `student_id` smallint(5) unsigned NOT NULL default '0',
  `local_branch` tinyint(2) unsigned default NULL,
  `local_address_ghname` varchar(20) default NULL,
  `local_address_housenumber` int(4) unsigned default NULL,
  `local_phone` varchar(15) default NULL,
  `local_travel_withpartner` tinyint(1) default NULL,
  `local_travel_partner_details` tinytext,
  PRIMARY KEY  (`unique_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

#
# Dumping data for table `students_local`
#

INSERT INTO `students_local` VALUES (1, 0, 1, 'Seaside Bungalow', 5, '+(66)0800439978', 1, '');
INSERT INTO `students_local` VALUES (2, 16, 3, 'sdfsf', 333, '+(333)333333333', 0, '');
INSERT INTO `students_local` VALUES (3, 17, 1, 'backroad bungalows', 3, '+()', 1, 'partner');
INSERT INTO `students_local` VALUES (4, 20, 2, 'amrita ashram', 2, '0869533408', 1, 'partner');
INSERT INTO `students_local` VALUES (5, 20, 1, 'pook house', 1, '0869533408', 1, 'partner');

# --------------------------------------------------------

#
# Table structure for table `students_medical`
#
# Creation: Apr 29, 2008 at 03:20 AM
#

CREATE TABLE `students_medical` (
  `student_id` smallint(5) unsigned NOT NULL default '0',
  `medform_complete` tinyint(1) default NULL,
  `medform_issues` tinyint(1) default NULL,
  `medform_issues_details` text,
  PRIMARY KEY  (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Dumping data for table `students_medical`
#

INSERT INTO `students_medical` VALUES (0, 1, 0, '');
INSERT INTO `students_medical` VALUES (16, 1, 0, '');
INSERT INTO `students_medical` VALUES (17, 1, 0, '');
INSERT INTO `students_medical` VALUES (18, 1, 0, '');
INSERT INTO `students_medical` VALUES (19, 1, 0, '');
INSERT INTO `students_medical` VALUES (20, 1, 0, '');

# --------------------------------------------------------

#
# Table structure for table `teachers_basic`
#
# Creation: Apr 30, 2008 at 08:10 PM
#

CREATE TABLE `teachers_basic` (
  `teacher_id` smallint(5) unsigned NOT NULL auto_increment,
  `student_id` smallint(5) unsigned default NULL,
  `teacher_level` tinyint(1) unsigned default NULL,
  `is_active` tinyint(1) default NULL,
  `is_ttc` tinyint(1) default NULL,
  PRIMARY KEY  (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

#
# Dumping data for table `teachers_basic`
#

INSERT INTO `teachers_basic` VALUES (2, 15, 1, 1, 1);
INSERT INTO `teachers_basic` VALUES (3, 16, 3, 1, 0);
INSERT INTO `teachers_basic` VALUES (4, 17, 2, 1, 1);
INSERT INTO `teachers_basic` VALUES (5, 18, 3, 1, 0);
INSERT INTO `teachers_basic` VALUES (6, 19, 1, 0, 0);
INSERT INTO `teachers_basic` VALUES (7, 20, 1, 1, 1);

# --------------------------------------------------------

#
# Table structure for table `ttc_participants`
#
# Creation: Apr 30, 2008 at 08:09 PM
#

CREATE TABLE `ttc_participants` (
  `participant_id` smallint(5) unsigned NOT NULL auto_increment,
  `student_id` smallint(5) unsigned default NULL,
  `ttc_graduated` tinyint(1) default NULL,
  `ttc_branch` tinyint(3) unsigned default NULL,
  `ttc_month` tinyint(2) unsigned default NULL,
  `ttc_year` smallint(4) unsigned default NULL,
  PRIMARY KEY  (`participant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

#
# Dumping data for table `ttc_participants`
#

INSERT INTO `ttc_participants` VALUES (2, 15, 1, 1, 1, 2006);
INSERT INTO `ttc_participants` VALUES (3, 17, 1, 1, 1, 2006);
INSERT INTO `ttc_participants` VALUES (4, 20, 1, 1, 1, 2007);

# --------------------------------------------------------

#
# Table structure for table `user_access`
#
# Creation: Jun 01, 2008 at 01:17 AM
#

CREATE TABLE `user_access` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `username` varchar(15) default NULL,
  `password` text,
  `access_level` tinyint(1) unsigned default NULL,
  `student_id` smallint(5) unsigned NOT NULL default '0',
  `firstname` varchar(15) default NULL,
  `surname` varchar(15) default NULL,
  `email` varchar(40) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

#
# Dumping data for table `user_access`
#

INSERT INTO `user_access` VALUES (1, 'dharmahound', 'dharma175hound', 1, 20, 'andrew', 'reece', 'areece@naropa.edu');
