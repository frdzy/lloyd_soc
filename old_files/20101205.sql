SELECT
    SUBSTRING_INDEX(name, ' ', 1) AS firstname,
    CONCAT( email, '@', IF(domain IS NULL, 'caltech.edu', domain) ) AS email,
    IF( lift_ticket = 1, 'Yes', 'No' ) AS lift_ticket,
    IF( driver_seats > -1, CONCAT(driver_seats + 1, ' people (including yourself)'), 'No') AS passengers,
    IF( membership NOT IN ('s', 'f'), lift_ticket * 55 + 80.00, lift_ticket * 40 + 60.00 ) AS price,
    paid_amount
FROM names NATURAL JOIN houselist JOIN ll_participate ON id = participant_id
    JOIN ski_participant ON id = occupant_id
WHERE ll_event_id = 2
INTO OUTFILE '/tmp/ski_mail_20101207.txt'
    FIELDS TERMINATED BY ';'
    LINES TERMINATED BY '\n';

