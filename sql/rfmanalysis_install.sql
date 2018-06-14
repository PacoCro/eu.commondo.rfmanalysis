CREATE TABLE IF NOT EXISTS `civicrm_rfm_analysis_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL UNIQUE,
  `value` char(50) NOT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `civicrm_rfm_analysis_member_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL UNIQUE,
  `RFM_R` int(11) NOT NULL,
  `RFM_F` int(11) NOT NULL,
  `RFM_M` int(11) NOT NULL,
  `RFM_G` int(11) NOT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;