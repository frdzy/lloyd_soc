DROP PROCEDURE IF EXISTS fn_ll_participant_add;
DROP PROCEDURE IF EXISTS fn_ll_car_random_sr_fr;

-- TODO: delete this
DELETE FROM ll_participate
  WHERE ll_event_id = 4;

DELIMITER !

CREATE PROCEDURE fn_ll_participant_add
  (name_query VARCHAR(25), ll_event INT, seats INT)
BEGIN
  DECLARE return_status INT DEFAULT -1;
  SET return_status =
    (SELECT COUNT(*)
    FROM houselist
    WHERE CONCAT(firstname, nickname, lastname) LIKE
      CONCAT('%', name_query, '%'));

  SET autocommit = 0;
  START TRANSACTION;
    IF (return_status = 1)
    THEN
      SELECT id INTO return_status
      FROM houselist
      WHERE CONCAT(firstname, nickname, lastname) LIKE
        CONCAT('%', name_query, '%');
      REPLACE INTO ll_participate VALUE
        (ll_event, return_status, 0, NULL, NULL, seats - 1);
    ELSE
      SET return_status = -1;
    END IF;
  COMMIT;
  SET autocommit = 1;

  SELECT return_status AS status;
END;

CREATE PROCEDURE fn_ll_car_random_sr_fr (ll_event INT)
BEGIN
  
  DECLARE cur CURSOR FOR
    SELECT id FROM ll_participate
      WHERE ll_event_id = ll_event
        AND driver_id = NULL;
  SET autocommit = 0;
  START TRANSACTION;
    WHILE NOT DONE DO
    END WHILE;
  COMMIT;
  SET autocommit = 1;
END;

!
DELIMITER ;
