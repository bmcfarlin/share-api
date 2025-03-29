/*!50003 DROP PROCEDURE IF EXISTS `sp_affiliate_get_by_partner_user` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gen`@`localhost` PROCEDURE `sp_affiliate_get_by_partner_user`(
  p_partner_id int(10),
  p_user_id varchar(36)
)
BEGIN

select
  a.affiliate_id,
  a.partner_id,
  a.user_id,
  a.code,
  a.creation_user_id,
  a.creation_dtm,
  a.last_update_user_id,
  a.last_update_dtm
from
  affiliate a
where
  a.partner_id = p_partner_id
  and a.user_id = p_user_id;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
