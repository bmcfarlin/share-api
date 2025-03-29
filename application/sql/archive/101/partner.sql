SET foreign_key_checks=0;
SET @saved_cs_client = @@character_set_client;
SET @saved_cs_results = @@character_set_results;
SET @saved_col_connection = @@collation_connection;

SET character_set_client = utf8mb4;
SET character_set_results = utf8mb4;
SET collation_connection = utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS `sh`;
USE `sh`;


DROP TABLE IF EXISTS `sh`.`partner`;
DROP TABLE IF EXISTS `sh`.`partner_audit`;
DROP TRIGGER IF EXISTS `sh`.`tr_partner_ains`;
DROP TRIGGER IF EXISTS `sh`.`tr_partner_aupd`;
DROP TRIGGER IF EXISTS `sh`.`tr_partner_adel`;
DROP PROCEDURE IF EXISTS `sh`.`sp_partner_get`;
DROP PROCEDURE IF EXISTS `sh`.`sp_partner_get_by_id`;
DROP PROCEDURE IF EXISTS `sh`.`sp_partner_ups`;
DROP PROCEDURE IF EXISTS `sh`.`sp_partner_ins`;
DROP PROCEDURE IF EXISTS `sh`.`sp_partner_upd`;
DROP PROCEDURE IF EXISTS `sh`.`sp_partner_del`;
DROP PROCEDURE IF EXISTS `sh`.`sp_partner_trn`;


CREATE TABLE `sh`.`partner` (
  partner_id int(10) unsigned auto_increment not null,
  name varchar(100),
  description varchar(512),
  url varchar(100),
  email varchar(100),
  primary key (`partner_id`)
);

DELIMITER $$

CREATE PROCEDURE `sh`.`sp_partner_get`()
BEGIN

select
  a.partner_id,
  a.name,
  a.description,
  a.url,
  a.email
from
  partner a;


END $$

DELIMITER ; 


DELIMITER $$

CREATE PROCEDURE `sh`.`sp_partner_get_by_id`(
  p_partner_id int(10)
)
BEGIN

select
  a.partner_id,
  a.name,
  a.description,
  a.url,
  a.email
from
  partner a
where
  a.partner_id = p_partner_id;


END $$

DELIMITER ; 


DELIMITER $$

CREATE PROCEDURE `sh`.`sp_partner_ins`(
  p_name varchar(100),
  p_description varchar(512),
  p_url varchar(100),
  p_email varchar(100)
)
BEGIN

insert partner
(
  name,
  description,
  url,
  email
)
values
(
  p_name,
  p_description,
  p_url,
  p_email
);

select
  a.partner_id,
  a.name,
  a.description,
  a.url,
  a.email
from
  partner a
where
  a.partner_id = last_insert_id();


END $$

DELIMITER ; 


DELIMITER $$

CREATE PROCEDURE `sh`.`sp_partner_upd`(
  p_partner_id int(10),
  p_name varchar(100),
  p_description varchar(512),
  p_url varchar(100),
  p_email varchar(100)
)
BEGIN

update
  partner
set
  name = p_name,
  description = p_description,
  url = p_url,
  email = p_email
where
  partner_id = p_partner_id;

select
  a.partner_id,
  a.name,
  a.description,
  a.url,
  a.email
from
  partner a
where
  a.partner_id = p_partner_id;


END $$

DELIMITER ; 


DELIMITER $$

CREATE PROCEDURE `sh`.`sp_partner_del`(
  p_partner_id int(10)
)
BEGIN

delete from
  partner
where
  partner_id = p_partner_id;


END $$

DELIMITER ; 


DELIMITER $$

CREATE PROCEDURE `sh`.`sp_partner_trn`()
BEGIN

truncate table partner;


END $$

DELIMITER ; 


SET character_set_client = @saved_cs_client;
SET character_set_results = @saved_cs_results;
SET collation_connection  = @saved_col_connection;
SET foreign_key_checks=1;

