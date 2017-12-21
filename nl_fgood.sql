/*
SQLyog Community v8.61 
MySQL - 5.5.25a : Database - nl_fgood
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`nl_fgood` /*!40100 DEFAULT CHARACTER SET utf8 */;

/*Table structure for table `apphea_set` */

DROP TABLE IF EXISTS `apphea_set`;

CREATE TABLE `apphea_set` (
  `apphea_txt` varchar(80) DEFAULT NULL,
  `appbg_col` varchar(10) DEFAULT NULL,
  `apphea_log` varchar(100) DEFAULT NULL,
  `modified_by` varchar(45) NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `apphea_set` */

insert  into `apphea_set`(`apphea_txt`,`appbg_col`,`apphea_log`,`modified_by`,`modified_on`) values ('1','59A1FF','complogo.jpg','admin','2012-11-06 07:04:51');

/*Table structure for table `arpthea_set` */

DROP TABLE IF EXISTS `arpthea_set`;

CREATE TABLE `arpthea_set` (
  `compname` varchar(80) CHARACTER SET latin1 DEFAULT NULL,
  `add_line_1` varchar(80) CHARACTER SET latin1 DEFAULT NULL,
  `add_line_2` varchar(80) CHARACTER SET latin1 DEFAULT NULL,
  `add_line_3` varchar(80) CHARACTER SET latin1 DEFAULT NULL,
  `telno` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `faxno` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `emailadd` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `homeurl` varchar(80) CHARACTER SET latin1 DEFAULT NULL,
  `lastlogin` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `lastupd` datetime DEFAULT NULL,
  `menucode` varchar(10) DEFAULT NULL,
  `rpttitle` varchar(80) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `arpthea_set` */

insert  into `arpthea_set`(`compname`,`add_line_1`,`add_line_2`,`add_line_3`,`telno`,`faxno`,`emailadd`,`homeurl`,`lastlogin`,`lastupd`,`menucode`,`rpttitle`) values ('1','LOT 7127,JALAN PASAR SAMBUNGAN,dd','36000 TELUK INTAN','PERAK','605-6222d','605-622d','','','kelly','2012-11-01 19:10:35','PURC01','PURCHASE ORDER');

/*Table structure for table `arptrmk_set` */

DROP TABLE IF EXISTS `arptrmk_set`;

CREATE TABLE `arptrmk_set` (
  `menucode` varchar(10) DEFAULT NULL,
  `rptrmk` varchar(200) DEFAULT NULL,
  `seqno` smallint(5) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `arptrmk_set` */

/*Table structure for table `location_master` */

DROP TABLE IF EXISTS `location_master`;

CREATE TABLE `location_master` (
  `location_code` varchar(10) DEFAULT NULL,
  `location_desc` varchar(50) DEFAULT NULL,
  `create_by` varchar(20) CHARACTER SET latin1 NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `currency_code` (`location_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `location_master` */

insert  into `location_master`(`location_code`,`location_desc`,`create_by`,`creation_time`,`modified_by`,`modified_on`) values ('UP','UPSTAIRS','admin','2012-12-15 18:20:10','admin','2012-12-15 11:20:10');

/*Table structure for table `menud` */

DROP TABLE IF EXISTS `menud`;

CREATE TABLE `menud` (
  `menu_name` varchar(60) CHARACTER SET latin1 DEFAULT NULL,
  `menu_desc` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `menu_seq` int(10) unsigned DEFAULT NULL,
  `menu_path` varchar(200) DEFAULT NULL,
  `menu_type` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `menu_code` varchar(10) DEFAULT NULL,
  `menu_parent` varchar(20) DEFAULT NULL,
  `menu_stat` varchar(45) DEFAULT NULL,
  `modified_by` varchar(45) NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `menud` */

insert  into `menud`(`menu_name`,`menu_desc`,`menu_seq`,`menu_path`,`menu_type`,`menu_code`,`menu_parent`,`menu_stat`,`modified_by`,`modified_on`) values ('Company Profile','Welcome Page',1,'/home.php','Program','HOME01','HOME','ACTIVE','admin','2012-07-14 06:57:18'),('User Login Maintenance','Maintenance User Account Setting',2,'./admin_set/user_master.php','Program','HOME02','HOME','ACTIVE','admin','2012-07-09 12:50:38'),('Menu Master','Setting Menu ',4,'/admin_set/menu_set.php','Program','HOME03','HOME','ACTIVE','admin','2012-07-09 12:36:14'),('Profile Master','Setting Profile Role Authorization',3,'./admin_set/profile_master.php','Program','HOME04','HOME','ACTIVE','admin','2012-06-12 11:38:09'),('Master','Master File Set Up menu',2,'./home.php','Main Menu','MAST','','ACTIVE','admin','2012-06-23 10:00:18'),('Bill Of Material','Bill Of Material',3,'./home.php','Main Menu','BOMP','','ACTIVE','admin','2012-06-23 09:59:31'),('Sales Management','Sales Management System',4,'./home.php','Main Menu','SALE','','ACTIVE','admin','2012-06-23 10:00:41'),('Purchase Management','Purchasing Management System',5,'./home.php','Main Menu','PURC','','ACTIVE','admin','2012-06-23 10:00:30'),('Inventory Management','Inventory Management System',6,'./home.php','Main Menu','INVT','','ACTIVE','admin','2012-06-23 10:00:02'),('Stock Master File','Stock Master File Setting',1,'/home.php','Sub Menu','STKMAS','INVT','ACTIVE','admin','2012-07-14 06:58:31'),('BOM Master File','BOM Master File Setting',2,'/home.php','Sub Menu','BOMMAS','BOMP','ACTIVE','admin','2012-07-14 08:21:24'),('Agent Master','Agent Code Maintenance',3,'./main_mas/country_mas.php','Program','MAST03','MAST','ACTIVE','admin','2012-12-15 04:54:05'),('Home','Home Page / First Page',1,'/home.php','Main Menu','HOME','','ACTIVE','admin','2012-07-14 05:33:52'),('Item Group Master','Item Group Code Master File',3,'./stck_mas/itm_group_master.php','Program','STKMAS03','STKMAS','ACTIVE','admin','2012-06-12 13:37:53'),('Stock Category','Stock Category Detail',2,'/stck_mas/catmas.php','Program','STKMAS02','STKMAS','ACTIVE','admin','2012-07-15 05:35:48'),('Product Master','Product Code Maintenance',1,'/stck_mas/colormas.php','Program','MAST01','MAST','ACTIVE','admin','2012-07-09 12:39:29'),('Supplier Master','Supplier Code Maintenance',4,'./main_mas/curr_mas.php','Program','MAST04','MAST','ACTIVE','admin','2012-07-09 12:41:31'),('UOM Master','UOM Code Maintenance',5,'./main_mas/prod_uommas.php','Program','MAST05','MAST','ACTIVE','admin','2012-06-12 13:16:26'),('SKU Master','SKU Code Maintenance',6,'./main_mas/sku_mas.php','Program','MAST06','MAST','ACTIVE','admin','2012-06-12 13:19:00'),('Sales Type Master','Sales Type Maintenance',7,'./main_mas/salestype_mas.php','Program','MAST07','MAST','ACTIVE','admin','2012-08-07 09:54:04'),('Ship Type Master','Ship Type Maintenance',8,'./main_mas/shiptype_mas.php','Program','MAST08','MAST','ACTIVE','admin','2012-06-12 13:22:41'),('RM Price Control','RM Price Control Application',8,'/stck_mas/m_rawmat_price_ctrl.php','Program','STKMAS08','STKMAS','ACTIVE','admin','2012-09-12 01:27:04'),('RM Master Sub Code','Sub Code Set Up For RM Item Master',7,'/stck_mas/m_rm_subcode.php','Program','STKMAS07','STKMAS','ACTIVE','admin','2012-09-27 11:03:41'),('RM Master Main Code','Main Code Set Up For RM Item Master',6,'./stck_mas/rawmat_mas.php','Program','STKMAS06 ','STKMAS','ACTIVE','admin','2012-06-12 13:45:11'),('Stock UOM Master','UOM Master File',4,'/stck_mas/uommas.php','Program','STKMAS04','STKMAS','ACTIVE','admin','2012-07-15 09:50:00'),('RM Booking Menu','Raw Material Booking Menu',2,'','Sub Menu','PURC02','PURC','ACTIVE','admin','2012-10-31 16:09:53'),('Product Type Master','Setting Code For Product Type Master',1,'/bom_master/product_type_master.php','Program','BOMMAS01','BOMMAS','ACTIVE','admin','2012-07-15 14:15:29'),('Product Buyer Master','Product Buyer Code Master File',2,'./bom_master/pro_buy_master.php','Program','BOMMAS02','BOMMAS','ACTIVE','admin','2012-06-12 14:59:00'),('Product Prefix Setting','Product Category Master',3,'./bom_master/pro_cat_master.php','Program','BOMMAS03','BOMMAS','ACTIVE','admin','2012-06-12 15:28:47'),('Product Code Master','Product /Style Detail Set Up',4,'./bom_master/pro_code_master.php','Program','BOMMAS04','BOMMAS','ACTIVE','admin','2012-06-23 14:32:43'),('Job File','Description Of Job Pay Rate',5,'./bom_master/job_id_master.php','Program','BOMMAS05','BOMMAS','ACTIVE','admin','2012-06-20 11:58:36'),('Product Job Pay Rate','Detail Of Product Job Model',6,'./bom_master/projob_rate1.php','Program','BOMMAS06','BOMMAS','ACTIVE','admin','2012-07-09 12:42:24'),('BOM Transaction','Bill Of Material Transaction Maintenance',2,'/home.php','Sub Menu','BOMTRAN','BOMP','ACTIVE','admin','2012-07-14 06:58:13'),('Product Costing','Product Cost & Material Maintenance',1,'./bom_tran/m_pro_cost.php','Program','BOMTRAN01','BOMTRAN','ACTIVE','admin','2012-07-07 15:51:14'),('Reset User Password','Reset Login Password',5,'./admin_set/reset_pass.php','Program','HOME05','HOME','ACTIVE','admin','2012-06-28 13:12:34'),('Header Maintenance','Application Header Maintenance',6,'/admin_set/head_set.php','Program','HOME06','HOME','ACTIVE','admin','2012-07-14 15:30:12'),('Customer Master','Customer Master Code Maintenance',2,'/main_mas/m_cust_mas.php','Program','MAST02','MAST','ACTIVE','admin','2012-08-13 04:18:51'),('Sales Order Form','Sales Order Entry',1,'/sales_tran/m_sale_form.php','Program','SALE1','SALE','ACTIVE','admin','2012-08-15 01:16:31'),('Purchase Order','Purchased',1,'/pur_ord/m_po.php','Program','PURC01','PURC','ACTIVE','admin','2012-08-17 04:08:42'),('Stock Transaction','Stock Transaction Menu',2,'','Sub Menu','STKTRAN','INVT','ACTIVE','admin','2012-08-17 09:35:04'),('Stock GRN','Received Stock By P/O ',2,'/stck_tran/m_rm_receive.php','Program','STKTRAN01','STKTRAN','ACTIVE','admin','2012-08-24 12:47:07'),('Stock Issue ','Issue Raw Material D/O ',3,'/stck_tran/m_rm_issue.php','Program','STKTRAN02','STKTRAN','ACTIVE','admin','2012-08-24 12:47:13'),('Report Header Maintenance','Report Header Date',7,'/admin_set/m_rpthead_set.php','Program','HOME07','HOME','ACTIVE','admin','2012-08-18 17:13:50'),('Material Planning','Material Planing From Sales Order',3,'/bom_tran/m_mat_plan.php','Program','BOMTRAN02','BOMTRAN','ACTIVE','admin','2012-10-19 06:19:03'),('Stock Opening ','Opening Balance',1,'/stck_tran/m_rm_opening.php','Program','STKTRAN00','STKTRAN','ACTIVE','admin','2012-08-24 12:46:58'),('Stock Adjustment','Adjustment Stock',4,'/stck_tran/m_rm_adj.php','Program','STKTRAN03','STKTRAN','ACTIVE','admin','2012-08-24 12:55:26'),('Stock Return','Stock Return',5,'/stck_tran/m_rm_return.php','Program','STKTRAN04','STKTRAN','ACTIVE','admin','2012-08-24 12:57:23'),('Sales Order Approval','Approved Sales Order By Director/FM',2,'/sales_tran/sales_appr.php','Program','SALE02','SALE','ACTIVE','admin','2012-09-13 01:27:05'),('Stock Reject','Reject Stock',6,'/stck_tran/m_rm_reject.php','Program','STKTRAN05','STKTRAN','ACTIVE','admin','2012-09-18 02:44:01'),('Product Color Master','Color Master For Product ',7,'/bom_master/prod_clr_master.php','Program','BOMMAS07','BOMMAS','ACTIVE','admin','2012-10-14 04:39:13'),('Product UOM Master','UOM Master For Product ',8,'/bom_master/prod_uommas.php','Program','BOMMAS08','BOMMAS','ACTIVE','admin','2012-10-14 06:10:31'),('Stock Location Master','Locatio Detail Master File',5,'/stck_mas/stk_lotc.php','Program','STKMAS05','STKMAS','ACTIVE','admin','2012-10-15 13:24:05'),('Product Costing Approval','Product Costing Approval',2,'/bom_tran/procost_appr.php','Program','BOMTRAN03','BOMTRAN','ACTIVE','admin','2012-10-19 06:18:04'),('Stock Label','Label For Stock Item Openning, GRN',3,'/stck_mas/stk_label.php','Program','STKLBL01','INVT','ACTIVE','admin','2012-10-22 12:11:40'),('Location Master','Location Master Code Maintenance',9,'/main_mas/location_mas.php','Program','MAST09','MAST','ACTIVE','admin','2012-10-29 13:27:28'),('Booking For RM Item','Booking System To Manage Booking RM Item',1,'/book_tran/m_booksys.php','Program','BOOK01','PURC02','ACTIVE','admin','2012-10-31 16:11:39'),('NLG Product Master','NLG Product Code Maintenance',10,'/main_mas/nlg_prod_mas.php','Program','MAST10','MAST','ACTIVE','admin','2012-12-15 00:00:00'),('Own/MDF Category Master','Own/MDF Category Code Maintenance',11,'/main_mas/own_mdf_cat_mas.php','Program','MAST11','MAST','ACTIVE','admin','2012-12-15 00:00:00'),('Supervisor Master','Supervisor Code Maintenance',12,'/main_mas/supervisor_mas.php','Program','MAST12','MAST','ACTIVE','admin','2012-12-15 00:00:00'),('Price Master','Price Code Maintenance ',13,'/main_mas/price_mas.php','Program','MAST13','MAST','ACTIVE','admin','2012-12-15 00:00:00'),('Counter Commission Master','Counter Commission Maintenance',14,'/main_mas/comm_mas.php','Program','MAST14','MAST','ACTIVE','admin','2012-12-15 00:00:00'),('Term Master','Term Code Maintenance',15,'/main_mas/term_mas.php','Program','MAST15','MAST','ACTIVE','admin','2012-12-15 00:00:00'),('Zone Master','Zone Code Maintenance',16,'/main_mas/zone_mas.php','Program','MAST16','MAST','ACTIVE','admin','2012-12-15 00:00:00');

/*Table structure for table `price_master` */

DROP TABLE IF EXISTS `price_master`;

CREATE TABLE `price_master` (
  `price_code` varchar(20) NOT NULL,
  `price_desc` varchar(60) NOT NULL,
  `create_by` varchar(45) NOT NULL,
  `create_on` datetime NOT NULL,
  `modified_by` varchar(45) NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`price_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `price_master` */

insert  into `price_master`(`price_code`,`price_desc`,`create_by`,`create_on`,`modified_by`,`modified_on`) values ('EMM','EAST MALAYSIA MEMBER','admin','2012-12-15 13:07:56','admin','2012-12-15 13:07:56');

/*Table structure for table `prod_uommas` */

DROP TABLE IF EXISTS `prod_uommas`;

CREATE TABLE `prod_uommas` (
  `uom_code` varchar(20) NOT NULL,
  `uom_desc` varchar(50) DEFAULT NULL,
  `uom_pack` varchar(10) DEFAULT NULL,
  `modified_by` varchar(45) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`uom_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `prod_uommas` */

insert  into `prod_uommas`(`uom_code`,`uom_desc`,`uom_pack`,`modified_by`,`modified_on`,`created_by`,`created_on`) values ('DZN','DOZEN','12','admin','2012-12-15 00:00:00','admin','2012-12-15 00:00:00');

/*Table structure for table `profile_access` */

DROP TABLE IF EXISTS `profile_access`;

CREATE TABLE `profile_access` (
  `profile_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `menu_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pa_access` smallint(5) unsigned NOT NULL,
  `pa_add` smallint(5) unsigned NOT NULL,
  `pa_update` smallint(5) unsigned NOT NULL,
  `pa_view` smallint(5) unsigned NOT NULL,
  `pa_delete` smallint(5) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `profile_access` */

insert  into `profile_access`(`profile_code`,`menu_code`,`pa_access`,`pa_add`,`pa_update`,`pa_view`,`pa_delete`) values ('MAST','INVEN3',1,1,1,1,1),('MAST','INVEN2',1,1,1,1,1),('MAST','INVEN1',1,0,0,0,0),('MAST','SALM',1,0,0,0,0),('MAST','MAST8',1,1,1,1,1),('MAST','MAST7',1,1,1,1,1),('MAST','MAST6',1,1,1,1,1),('MAST','MAST3',1,1,1,1,1),('MAST','MAST2',1,0,0,0,0),('MAST','MAST5',1,1,1,1,1),('MAST','MAST1',1,1,1,1,1),('ROLE2','INVEN2',1,0,1,1,1),('ROLE2','INVEN1',1,0,0,0,0),('ROLE2','MAST6',1,1,0,1,0),('ROLE2','MAST3',1,1,0,1,0),('ROLE2','MAST2',1,0,0,0,0),('ROLE2','MAST',1,0,0,0,0),('ROLE2','HOME1',1,0,0,0,0),('ROLE3','HOME1',1,0,0,0,0),('ROLE3','SALM',1,0,0,0,0),('MAST','MAST',1,0,0,0,0),('MAST','HOME1',1,0,0,0,0),('RMCLERK','MAST01',1,1,1,1,1),('RMCLERK','STKMAS01',1,1,1,1,1),('ROLE2','INVEN3',1,0,1,1,1),('RMCLERK','MAST',1,0,0,0,0),('GCLERK','HOME',1,0,0,0,0),('GCLERK','HOME01',1,1,0,1,0),('GCLERK','HOME02',1,1,0,1,0),('GCLERK','HOME04',1,1,0,1,0),('GCLERK','HOME03',1,1,0,1,0),('GCLERK','HOME05',1,1,0,1,0),('GCLERK','HOME06',1,1,0,1,0),('RMSVSOR','MAST',1,0,0,0,0),('RMSVSOR','STKMAS01',1,1,1,1,1),('RMSVSOR','MAST01',1,1,1,1,1),('RMSVSOR','MAST02',1,1,1,1,1),('RMSVSOR','MAST03',1,1,1,1,1),('RMSVSOR','MAST04',1,1,1,1,1),('RMSVSOR','MAST05',1,1,1,1,1),('RMSVSOR','MAST06',1,1,1,1,1),('RMSVSOR','MAST07',1,1,1,1,1),('RMSVSOR','BOMP',1,0,0,0,0),('RMSVSOR','BOMMAS',1,0,0,0,0),('RMSVSOR','BOMMAS01',1,1,1,1,1),('RMSVSOR','BOMMAS02',1,1,1,1,1),('RMSVSOR','BOMMAS03',1,1,1,1,1),('RMSVSOR','BOMMAS04',1,1,1,1,1),('RMSVSOR','BOMMAS05',1,1,1,1,1),('RMSVSOR','BOMMAS06',1,1,1,1,1),('RMSVSOR','BOMTRAN',1,0,0,0,0),('RMSVSOR','BOMTRAN01',1,1,1,1,1),('RMSVSOR','BOMTRAN02',1,1,1,1,1),('RMSVSOR','SALE',1,0,0,0,0),('RMSVSOR','SALE1',1,1,1,1,1),('RMSVSOR','PURC',1,0,0,0,0),('RMSVSOR','PURC01',1,1,1,1,1),('RMSVSOR','INVT',1,0,0,0,0),('RMSVSOR','STKMAS',1,0,0,0,0),('RMSVSOR','STKMAS02',1,1,1,1,1),('RMSVSOR','STKMAS03',1,1,1,1,1),('RMSVSOR','STKMAS04',1,1,1,1,1),('RMSVSOR','STKMAS05',1,1,1,1,1),('RMSVSOR','STKMAS06 ',1,1,1,1,1),('RMSVSOR','STKMAS07',1,1,1,1,1),('RMSVSOR','STKMAS08',1,1,1,1,1),('RMSVSOR','STKTRAN',1,0,0,0,0),('RMSVSOR','STKTRAN01',1,1,1,1,1),('RMSVSOR','STKTRAN02',1,1,1,1,1),('MANA','STKTRAN04',1,1,1,1,0),('MANA','STKTRAN03',1,1,0,0,0),('MANA','STKTRAN02',1,1,0,1,1),('MANA','STKTRAN01',1,1,0,1,1),('MANA','STKTRAN00',1,1,1,0,1),('MANA','STKTRAN',1,0,0,0,0),('MANA','STKMAS08',1,1,1,0,1),('MANA','STKMAS07',1,1,1,0,1),('MANA','STKMAS06 ',1,1,0,1,1),('MANA','STKMAS05',1,1,0,0,1),('MANA','STKMAS04',1,1,0,1,1),('MANA','STKMAS03',1,1,0,1,1),('MANA','STKMAS02',1,1,1,0,1),('MANA','INVT',1,0,0,0,0),('MANA','STKMAS',1,0,0,0,0),('MANA','PURC01',1,1,0,1,1),('MANA','PURC',1,0,0,0,0),('MANA','SALE1',1,1,1,0,1),('MANA','SALE',1,0,0,0,0),('MANA','BOMTRAN02',1,1,0,1,0),('MANA','BOMTRAN01',1,1,0,1,1),('MANA','BOMTRAN',1,0,0,0,0),('MANA','BOMMAS06',1,1,0,1,1),('MANA','BOMMAS05',1,1,1,0,1),('MANA','BOMMAS04',1,1,1,1,0),('MANA','BOMMAS03',1,1,0,0,0),('MANA','BOMMAS02',1,1,0,0,1),('MANA','BOMMAS01',1,1,0,0,0),('MANA','BOMMAS',1,0,0,0,0),('MANA','BOMP',1,0,0,0,0),('MANA','MAST07',1,1,1,0,0),('MANA','MAST06',1,1,1,0,1),('MANA','MAST05',1,1,1,0,0),('MANA','MAST04',1,1,0,1,0),('MANA','MAST03',1,1,1,0,1),('MANA','MAST02',1,1,0,0,1),('MANA','MAST01',1,1,0,0,0),('MANA','STKMAS01',1,1,0,1,1),('MANA','MAST',1,0,0,0,0),('MANA','HOME07',1,1,1,0,1),('MANA','HOME06',1,1,1,1,1),('MANA','HOME05',1,1,1,1,1),('MANA','HOME03',1,1,1,0,1),('MANA','HOME04',1,1,1,1,1),('MANA','HOME02',1,1,1,0,1),('MANA','HOME01',1,1,1,0,1),('MANA','HOME',1,0,0,0,0),('RMCLERK','MAST02',1,1,1,1,1),('RMCLERK','MAST03',1,1,1,1,1),('RMCLERK','MAST04',1,1,1,1,1),('RMCLERK','MAST05',1,1,1,1,1),('RMCLERK','MAST06',1,1,1,1,1),('RMCLERK','MAST07',1,1,1,1,1),('RMCLERK','PURC',1,0,0,0,0),('RMCLERK','PURC01',1,1,1,1,1),('RMCLERK','INVT',1,0,0,0,0),('RMCLERK','STKMAS',1,0,0,0,0),('RMCLERK','STKMAS02',1,1,1,1,1),('RMCLERK','STKMAS03',1,1,1,1,1),('RMCLERK','STKMAS04',1,1,1,1,1),('RMCLERK','STKMAS05',1,1,1,1,1),('RMCLERK','STKMAS06 ',1,1,1,1,1),('RMCLERK','STKMAS07',1,1,1,1,1),('RMCLERK','STKMAS08',1,1,1,1,1),('RMCLERK','STKTRAN',1,0,0,0,0),('RMCLERK','STKTRAN00',1,1,1,1,1),('RMCLERK','STKTRAN01',1,1,1,1,1),('RMCLERK','STKTRAN02',1,1,1,1,1),('RMCLERK','STKTRAN03',1,1,1,1,1),('RMCLERK','STKTRAN04',1,1,1,1,1),('RMCLERK','STKTRAN05',1,1,1,1,1);

/*Table structure for table `profile_master` */

DROP TABLE IF EXISTS `profile_master`;

CREATE TABLE `profile_master` (
  `profile_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `profile_desc` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `create_by` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `create_on` datetime NOT NULL,
  `modified_by` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`profile_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `profile_master` */

insert  into `profile_master`(`profile_code`,`profile_desc`,`create_by`,`create_on`,`modified_by`,`modified_on`) values ('GCLERK','GENERAL CLEK','admin','2012-07-02 11:35:52','admin','2012-07-26 17:06:10'),('MANA','MANAGEMENT','admin','2012-09-11 02:05:57','admin','2012-09-14 01:15:47'),('MAST','administrator','admin','2012-05-26 06:46:01','admin','2012-05-27 15:42:02'),('RMCLERK','RM CLERK','admin','2012-07-02 11:04:31','admin','2012-09-24 20:13:26'),('RMSVSOR','RMSUPERVISOR','admin','2012-07-02 11:42:17','admin','2012-08-29 20:34:48'),('ROLE2','Stock Manager','admin','2012-05-26 06:47:38','admin','2012-05-30 10:54:33'),('ROLE3','Sales Person','admin','2012-05-26 06:49:00','admin','2012-05-26 06:49:00');

/*Table structure for table `progauth` */

DROP TABLE IF EXISTS `progauth`;

CREATE TABLE `progauth` (
  `username` varchar(20) NOT NULL,
  `program_name` varchar(20) NOT NULL,
  `deleter` varchar(1) DEFAULT NULL,
  `insertr` varchar(1) DEFAULT NULL,
  `updater` varchar(1) DEFAULT NULL,
  `viewr` varchar(1) DEFAULT NULL,
  `accessr` varchar(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `progauth` */

insert  into `progauth`(`username`,`program_name`,`deleter`,`insertr`,`updater`,`viewr`,`accessr`) values ('kelly','STKTRAN05','1','1','1','1','1'),('kelly','STKTRAN04','1','1','1','1','1'),('kelly','STKTRAN03','1','1','1','1','1'),('kelly','STKTRAN02','1','1','1','1','1'),('admin','STKMAS07','1','1','1','1','1'),('admin','STKMAS06 ','1','1','1','1','1'),('admin','STKMAS05','1','1','1','1','1'),('admin','STKMAS04','1','1','1','1','1'),('admin','STKMAS03','1','1','1','1','1'),('admin','STKMAS02','1','1','1','1','1'),('admin','STKMAS','0','0','0','0','1'),('admin','INVT','0','0','0','0','1'),('admin','BOOK01','1','1','1','1','1'),('admin','PURC02','0','0','0','0','1'),('admin','PURC01','1','1','1','1','1'),('admin','PURC','0','0','0','0','1'),('admin','SALE02','1','1','1','1','1'),('admin','SALE1','1','1','1','1','1'),('admin','SALE','0','0','0','0','1'),('admin','BOMTRAN02','1','1','1','1','1'),('admin','BOMTRAN03','1','1','1','1','1'),('admin','BOMTRAN01','1','1','1','1','1'),('RMTrainee','STKMAS02','1','1','1','1','1'),('RMTrainee','STKMAS','0','0','0','0','1'),('RMTrainee','INVT','0','0','0','0','1'),('RMTrainee','PURC01','1','1','1','1','1'),('RMTrainee','PURC','0','0','0','0','1'),('RMTrainee','MAST07','1','1','1','1','1'),('RMTrainee','MAST06','1','1','1','1','1'),('RMTrainee','MAST05','1','1','1','1','1'),('Jega','STKMAS07','0','0','0','0','1'),('Jega','STKMAS06 ','0','0','0','0','1'),('Jega','STKMAS05','0','0','0','0','1'),('Jega','STKMAS04','0','0','0','0','1'),('Jega','STKMAS03','0','0','0','0','1'),('Jega','STKMAS02','0','0','0','0','1'),('Jega','STKMAS','0','0','0','0','1'),('Jega','INVT','0','0','0','0','1'),('Jega','BOMMAS06','0','0','0','0','1'),('Jega','BOMMAS05','0','0','0','0','1'),('Jega','BOMMAS04','0','0','0','0','1'),('Jega','BOMMAS03','0','0','0','0','1'),('Jega','BOMMAS02','0','0','0','0','1'),('Jega','BOMMAS01','0','0','0','0','1'),('Jega','BOMMAS','0','0','0','0','1'),('Jega','BOMP','0','0','0','0','1'),('Jega','MAST06','1','1','1','1','1'),('Jega','MAST05','1','1','1','1','1'),('Jega','MAST04','1','1','1','1','1'),('Jega','MAST03','1','1','1','1','1'),('Jega','MAST02','1','1','1','1','1'),('Jega','MAST01','1','1','1','1','1'),('Jega','STKMAS01','1','1','1','1','1'),('Jega','MAST','0','0','0','0','1'),('Jega','HOME03','1','1','1','1','1'),('Jega','HOME04','1','1','1','1','1'),('Jega','HOME02','1','1','1','1','1'),('Jega','HOME01','1','1','1','1','1'),('Jega','HOME','0','0','0','0','1'),('Jega','STKMAS08','0','0','0','0','1'),('admin','BOMTRAN','0','0','0','0','1'),('admin','BOMMAS08','1','1','1','1','1'),('admin','BOMMAS07','1','1','1','1','1'),('admin','BOMMAS06','1','1','1','1','1'),('admin','BOMMAS05','1','1','1','1','1'),('admin','BOMMAS04','1','1','1','1','1'),('admin','BOMMAS03','1','1','1','1','1'),('admin','BOMMAS02','1','1','1','1','1'),('admin','BOMMAS01','1','1','1','1','1'),('admin','BOMMAS','0','0','0','0','1'),('admin','BOMP','0','0','0','0','1'),('admin','MAST16','1','1','1','1','1'),('RMTrainee','MAST04','1','1','1','1','1'),('RMTrainee','MAST03','1','1','1','1','1'),('RMTrainee','MAST02','1','1','1','1','1'),('RMTrainee','MAST01','1','1','1','1','1'),('RMTrainee','STKMAS01','1','1','1','1','1'),('RMTrainee','MAST','0','0','0','0','1'),('admin','MAST15','1','1','1','1','1'),('admin','MAST14','1','1','1','1','1'),('admin','MAST13','1','1','1','1','1'),('admin','MAST12','1','1','1','1','1'),('admin','MAST11','1','1','1','1','1'),('admin','MAST10','1','1','1','1','1'),('admin','MAST09','1','1','1','1','1'),('admin','MAST08','1','1','1','1','1'),('admin','MAST07','1','1','1','1','1'),('kevin','HOME01','0','0','0','0','1'),('kevin','HOME','0','0','0','0','1'),('RMTrainee','STKTRAN05','1','1','1','1','1'),('RMTrainee','STKTRAN04','1','1','1','1','1'),('RMTrainee','STKTRAN03','1','1','1','1','1'),('RMTrainee','STKTRAN02','1','1','1','1','1'),('RMTrainee','STKTRAN01','1','1','1','1','1'),('RMTrainee','STKTRAN00','1','1','1','1','1'),('RMTrainee','STKTRAN','0','0','0','0','1'),('RMTrainee','STKMAS08','1','1','1','1','1'),('RMTrainee','STKMAS07','1','1','1','1','1'),('RMTrainee','STKMAS06 ','1','1','1','1','1'),('RMTrainee','STKMAS05','1','1','1','1','1'),('RMTrainee','STKMAS04','1','1','1','1','1'),('RMTrainee','STKMAS03','1','1','1','1','1'),('kelly','STKTRAN01','1','1','1','1','1'),('kelly','STKTRAN00','1','1','1','1','1'),('kelly','STKTRAN','0','0','0','0','1'),('kelly','STKMAS08','1','1','1','1','1'),('kelly','STKMAS07','1','1','1','1','1'),('kelly','STKMAS06 ','1','1','1','1','1'),('kelly','STKMAS05','1','1','1','1','1'),('kelly','STKMAS04','1','1','1','1','1'),('kelly','STKMAS03','1','1','1','1','1'),('kelly','STKMAS02','1','1','1','1','1'),('kelly','STKMAS','0','0','0','0','1'),('kelly','INVT','0','0','0','0','1'),('kelly','PURC01','1','1','1','1','1'),('kelly','PURC','0','0','0','0','1'),('kelly','SALE02','1','1','1','1','1'),('kelly','SALE1','1','1','1','1','1'),('kelly','SALE','0','0','0','0','1'),('kelly','BOMTRAN02','0','0','0','0','1'),('kelly','BOMMAS08','1','1','1','1','1'),('gloria','STKMAS05','1','1','1','1','1'),('gloria','STKMAS04','1','1','1','1','1'),('gloria','STKMAS03','1','1','1','1','1'),('gloria','STKMAS02','1','1','1','1','1'),('gloria','STKMAS','0','0','0','0','1'),('gloria','INVT','0','0','0','0','1'),('gloria','PURC01','1','1','1','1','1'),('gloria','PURC','0','0','0','0','1'),('gloria','SALE1','1','1','1','1','1'),('gloria','SALE','0','0','0','0','1'),('gloria','BOMTRAN02','1','1','1','1','1'),('gloria','BOMTRAN01','1','1','1','1','1'),('gloria','BOMTRAN','0','0','0','0','1'),('gloria','BOMMAS06','1','1','1','1','1'),('gloria','BOMMAS05','1','1','1','1','1'),('gloria','BOMMAS04','1','1','1','1','1'),('gloria','BOMMAS03','1','1','1','1','1'),('gloria','BOMMAS02','1','1','1','1','1'),('gloria','BOMMAS01','1','1','1','1','1'),('gloria','BOMMAS','0','0','0','0','1'),('gloria','BOMP','0','0','0','0','1'),('gloria','MAST08','1','1','1','1','1'),('gloria','MAST07','1','1','1','1','1'),('gloria','MAST06','1','1','1','1','1'),('gloria','MAST05','1','1','1','1','1'),('gloria','MAST04','1','1','1','1','1'),('gloria','MAST03','1','1','1','1','1'),('gloria','MAST02','1','1','1','1','1'),('gloria','MAST01','1','1','1','1','1'),('gloria','STKMAS01','1','1','1','1','1'),('gloria','MAST','0','0','0','0','1'),('gloria','HOME07','1','1','1','1','1'),('gloria','HOME','0','0','0','0','1'),('admin','MAST06','1','1','1','1','1'),('admin','MAST05','1','1','1','1','1'),('admin','MAST04','1','1','1','1','1'),('admin','MAST03','1','1','1','1','1'),('admin','MAST02','1','1','1','1','1'),('admin','MAST01','1','1','1','1','1'),('kelly','STKLBL01','1','1','1','1','1'),('gloria','STKTRAN02','1','1','1','1','1'),('gloria','STKTRAN01','1','1','1','1','1'),('gloria','STKTRAN','0','0','0','0','1'),('gloria','STKMAS08','1','1','1','1','1'),('gloria','STKMAS07','1','1','1','1','1'),('gloria','STKMAS06 ','1','1','1','1','1'),('kelly','BOMMAS07','1','1','1','1','1'),('kelly','BOMMAS06','1','1','1','1','1'),('kelly','BOMMAS05','1','1','1','1','1'),('admin','MAST','0','0','0','0','1'),('kelly','BOMMAS04','1','1','1','1','1'),('kelly','BOMMAS03','1','1','1','1','1'),('kelly','BOMMAS02','1','1','1','1','1'),('kelly','BOMMAS01','1','1','1','1','1'),('kelly','BOMMAS','0','0','0','0','1'),('kelly','BOMP','0','0','0','0','1'),('kelly','MAST08','1','1','1','1','1'),('admin','HOME07','1','1','1','1','1'),('admin','HOME06','1','1','1','1','1'),('admin','HOME05','1','1','1','1','1'),('admin','HOME03','1','1','1','1','1'),('admin','HOME04','1','1','1','1','1'),('admin','HOME02','1','1','1','1','1'),('admin','HOME01','1','1','1','1','1'),('admin','HOME','0','0','0','0','1'),('kelly','MAST07','1','1','1','1','1'),('kelly','MAST06','1','1','1','1','1'),('kelly','MAST05','1','1','1','1','1'),('kelly','MAST04','1','1','1','1','1'),('kelly','MAST03','1','1','1','1','1'),('kelly','MAST02','1','1','1','1','1'),('kelly','MAST01','1','1','1','1','1'),('kelly','STKMAS01','1','1','1','1','1'),('kelly','MAST','0','0','0','0','1'),('kelly','HOME07','1','1','1','1','1'),('kelly','HOME','0','0','0','0','1'),('admin','STKMAS08','1','1','1','1','1'),('admin','STKTRAN','0','0','0','0','1'),('admin','STKTRAN00','1','1','1','1','1'),('admin','STKTRAN01','1','1','1','1','1'),('admin','STKTRAN02','1','1','1','1','1'),('admin','STKTRAN03','1','1','1','1','1'),('admin','STKTRAN04','1','1','1','1','1'),('admin','STKTRAN05','1','1','1','1','1'),('admin','STKLBL01','1','1','1','1','1');

/*Table structure for table `salestype_master` */

DROP TABLE IF EXISTS `salestype_master`;

CREATE TABLE `salestype_master` (
  `salestype_code` varchar(5) DEFAULT NULL,
  `salestype_desc` varchar(50) DEFAULT NULL,
  `create_by` varchar(20) CHARACTER SET latin1 NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `currency_code` (`salestype_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `salestype_master` */

insert  into `salestype_master`(`salestype_code`,`salestype_desc`,`create_by`,`creation_time`,`modified_by`,`modified_on`) values ('B','BEST BUY','admin','2012-12-15 17:45:50','admin','2012-12-15 10:45:50');

/*Table structure for table `shiptype_master` */

DROP TABLE IF EXISTS `shiptype_master`;

CREATE TABLE `shiptype_master` (
  `shiptype_code` varchar(5) DEFAULT NULL,
  `shiptype_desc` varchar(50) DEFAULT NULL,
  `create_by` varchar(20) CHARACTER SET latin1 NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `currency_code` (`shiptype_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `shiptype_master` */

insert  into `shiptype_master`(`shiptype_code`,`shiptype_desc`,`create_by`,`creation_time`,`modified_by`,`modified_on`) values ('C','CONSIGNMENT','admin','2012-12-15 18:00:40','admin','2012-12-15 11:00:40');

/*Table structure for table `sku_master` */

DROP TABLE IF EXISTS `sku_master`;

CREATE TABLE `sku_master` (
  `sku_code` varchar(30) NOT NULL,
  `customer_code` varchar(60) NOT NULL,
  `create_by` varchar(45) NOT NULL,
  `create_on` datetime NOT NULL,
  `modified_by` varchar(45) NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`sku_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `sku_master` */

insert  into `sku_master`(`sku_code`,`customer_code`,`create_by`,`create_on`,`modified_by`,`modified_on`) values ('SKU','TESTHH','admin','2012-12-15 10:02:00','admin','2012-12-15 10:06:01'),('TOW','RE','admin','2012-12-15 10:06:17','admin','2012-12-15 10:06:17');

/*Table structure for table `supervisor_master` */

DROP TABLE IF EXISTS `supervisor_master`;

CREATE TABLE `supervisor_master` (
  `supervisor_code` varchar(15) DEFAULT NULL,
  `supervisor_name` varchar(70) DEFAULT NULL,
  `counter_code` varchar(15) DEFAULT NULL,
  `create_by` varchar(20) CHARACTER SET latin1 NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `currency_code` (`supervisor_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `supervisor_master` */

insert  into `supervisor_master`(`supervisor_code`,`supervisor_name`,`counter_code`,`create_by`,`creation_time`,`modified_by`,`modified_on`) values ('SUP','SUPP','CONTER','admin','2012-12-15 19:46:36','admin','2012-12-15 12:46:36'),('SUPS','SSF','FS','admin','2012-12-15 00:00:00','admin','2012-12-15 00:00:00');

/*Table structure for table `term_master` */

DROP TABLE IF EXISTS `term_master`;

CREATE TABLE `term_master` (
  `term_code` varchar(5) NOT NULL,
  `term_desc` varchar(60) NOT NULL,
  `create_by` varchar(45) NOT NULL,
  `create_on` datetime NOT NULL,
  `modified_by` varchar(45) NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`term_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `term_master` */

insert  into `term_master`(`term_code`,`term_desc`,`create_by`,`create_on`,`modified_by`,`modified_on`) values ('60','60 DAYS','kelly','2012-09-24 20:23:58','kelly','2012-09-24 20:23:58'),('120','120 DAYS','kelly','2012-09-24 20:23:40','kelly','2012-09-24 20:23:40'),('30','30 DAYS','kelly','2012-09-24 20:23:49','kelly','2012-09-24 20:23:49'),('90','90 DAYS','kelly','2012-09-24 20:24:07','kelly','2012-09-24 20:24:07'),('CSH','CASH','kelly','2012-09-24 20:24:45','admin','2012-12-15 13:20:18'),('180','180 DAYS','kelly','2012-09-24 20:32:25','kelly','2012-09-24 20:32:25'),('150','150 DAYS','kelly','2012-09-24 20:32:33','kelly','2012-09-24 20:32:33');

/*Table structure for table `user_account` */

DROP TABLE IF EXISTS `user_account`;

CREATE TABLE `user_account` (
  `username` varchar(20) CHARACTER SET latin1 NOT NULL,
  `userid` varchar(20) CHARACTER SET latin1 NOT NULL,
  `first_name` varchar(80) CHARACTER SET latin1 DEFAULT NULL,
  `last_name` varchar(80) CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `mobile_number` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `pre_proflecd` varchar(20) NOT NULL,
  `userpass` text,
  `status` varchar(20) NOT NULL,
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `user_account` */

insert  into `user_account`(`username`,`userid`,`first_name`,`last_name`,`email`,`mobile_number`,`pre_proflecd`,`userpass`,`status`) values ('admin','00001','admin1','admin','admin@test.com','0162222222','MAST','202cb962ac59075b964b07152d234b70','ACTIVE'),('kevin','KEVIN','kevin','','','','ROLE3','81dc9bdb52d04dc20036dbd8313ed055','ACTIVE');

/*Table structure for table `zone_master` */

DROP TABLE IF EXISTS `zone_master`;

CREATE TABLE `zone_master` (
  `zone_code` varchar(5) NOT NULL,
  `zone_desc` varchar(60) NOT NULL,
  `create_by` varchar(45) NOT NULL,
  `create_on` datetime NOT NULL,
  `modified_by` varchar(45) NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`zone_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `zone_master` */

insert  into `zone_master`(`zone_code`,`zone_desc`,`create_by`,`create_on`,`modified_by`,`modified_on`) values ('EM','EAST MALAYSIA','admin','2012-12-15 00:00:00','admin','2012-12-15 13:39:12'),('WM','WEST MALAYSIA','admin','2012-12-15 00:00:00','admin','2012-12-15 00:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
