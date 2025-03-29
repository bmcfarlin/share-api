/*!50003 DROP PROCEDURE IF EXISTS `sp_tx_get_by_wallet` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gen`@`localhost` PROCEDURE `sp_tx_get_by_wallet`(
  p_wallet_id int(10)
)
BEGIN

select
  a.tx_id,
  a.wallet_id,
  a.tx_type_cd,
  a.amount,
  a.creation_user_id,
  a.creation_dtm,
  a.last_update_user_id,
  a.last_update_dtm
from
  tx a
where
  a.wallet_id = p_wallet_id
order by
  a.creation_dtm;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
