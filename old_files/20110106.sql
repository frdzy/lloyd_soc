SELECT
    SUBSTRING_INDEX(name, ' ', 1) AS firstname,
    CONCAT( email, '@', IF(domain IS NULL, 'caltech.edu', domain) ) AS email,
    lift_ticket, driver_seats + 1 AS passengers, paid_amount, price
FROM names NATURAL JOIN houselist JOIN ll_participate ON id = participant_id
    JOIN ski_participant ON id = occupant_id NATURAL JOIN ski2010_3
WHERE ll_event_id = 2 AND lift_ticket = 1
INTO OUTFILE '/tmp/skiiers.txt'
    FIELDS TERMINATED BY ';'
    LINES TERMINATED BY '\n';

