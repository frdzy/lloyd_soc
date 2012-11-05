SELECT
    SUBSTRING_INDEX(name, ' ', 1) AS firstname,
    CONCAT( email, '@', IF(domain IS NULL, 'caltech.edu', domain) ) AS email,
    lift_ticket, driver_seats + 1 AS passengers, paid_amount, price,
    IF( cabin_name IN (SELECT cabin_name
                        FROM ski2010_cabins
                        WHERE occupants > cabin_space AND cabin_name
                            NOT IN ('Golden Bear Manor', 'Bear Berry Manor')),
        1, 0) AS overfull
FROM names NATURAL JOIN houselist JOIN ll_participate ON id = participant_id
    JOIN ski_participant ON id = occupant_id NATURAL JOIN ski2010_2
WHERE ll_event_id = 2
INTO OUTFILE '/tmp/ski_mail_20101209.txt'
    FIELDS TERMINATED BY ';'
    LINES TERMINATED BY '\n';

