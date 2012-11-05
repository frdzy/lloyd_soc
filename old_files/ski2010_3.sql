DROP VIEW IF EXISTS ski2010_3;

CREATE VIEW ski2010_3 AS
    SELECT
        p.id, lastname, p.name, cabin_name, IF( lift_ticket = 1, 'Yes', 'No' ) AS lift,
        IF(driver_seats + 1 > 0, driver_seats + 1, '') AS seats, paid_amount, IF( paid_to_id IS NULL, 'N/A', (SELECT name FROM names WHERE id = paid_to_id )) AS paid_to,
        IF( membership NOT IN ('s', 'f'), lift_ticket * 55 + 80.00, lift_ticket * 40 + 60.00 ) AS price,
        IF(driver.name IS NULL, '', SUBSTRING_INDEX(driver.name, ' ', -1)) AS driver_lastname,
        IF(driver.name IS NULL, '', driver.name) AS driver_name,
        IF(depart.depart_time IS NULL, '', DATE_FORMAT(depart.depart_time, '%h:%i %p')) AS depart_time,
        IF(depart.notes IS NULL, '', depart.notes) AS depart_notes, lol
    FROM names AS p NATURAL JOIN houselist
        JOIN ll_participate ON id = participant_id
        JOIN ski_participant ON id = occupant_id NATURAL JOIN ski_cabins
        LEFT OUTER JOIN names AS driver ON ll_participate.driver_id = driver.id
        LEFT OUTER JOIN ski2010_depart AS depart ON ll_participate.participant_id = depart.driver_id
    WHERE ll_event_id = 2;
