CREATE TABLE IF NOT EXISTS `tbl_balance_sheet` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_date` longtext NOT NULL,
  `trans_amount` longtext NOT NULL,
  `trans_desc` longtext NOT NULL,
  `trans_type` longtext NOT NULL COMMENT 'Deposit, Invested, Return Investment, Loss, Withdraw',
  `user_id` int(11) NOT NULL,
  `balance_line` longtext NOT NULL,
  PRIMARY KEY (`trans_id`)
);
CREATE TABLE IF NOT EXISTS `tbl_investment` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `trading_acct_id` int(11) NOT NULL,
  `investor_id` int(11) NOT NULL,
  `invested_amount` longtext NOT NULL,
  `investment_target` longtext,
  `trading_earning` longtext,
  `invested_percentage` longtext NOT NULL,
  `entry_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `investment_status` varchar(255) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`entry_id`)
);
CREATE TABLE IF NOT EXISTS `tbl_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `default_url` longtext NOT NULL,
  PRIMARY KEY (`id`)
);
INSERT INTO `tbl_settings` (`id`, `default_url`) VALUES
(1, 'http://localhost/trading_app/');

CREATE TABLE IF NOT EXISTS `tbl_trading_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vm_name` longtext NOT NULL,
  `trading_acct_number` longtext NOT NULL,
  `instance` longtext NOT NULL,
  `broker_server` longtext NOT NULL,
  `investor_password` longtext NOT NULL,
  `balance` int(11) NOT NULL DEFAULT '0',
  `trading_status` varchar(255) NOT NULL DEFAULT 'No',
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) NOT NULL DEFAULT 'Investor' COMMENT 'Investor,Admin',
  `first_name` longtext COMMENT 'name if admin',
  `last_name` longtext COMMENT 'name if admin',
  `login_id` longtext NOT NULL,
  `password` longtext NOT NULL,
  `investor_balance` longtext,
  `is_admin` int(11) NOT NULL DEFAULT '0' COMMENT '0=not admin, 1=admin',
  PRIMARY KEY (`id`)
);
INSERT INTO `tbl_users` (`id`, `user_type`, `first_name`, `last_name`, `login_id`, `password`, `investor_balance`, `is_admin`) VALUES
(1, 'Admin', 'Admin', NULL, 'admin@admin.com', 'admin8485', NULL, 0);
COMMIT;

