SELECT
    CONCAT( email, '@', IF(domain IS NULL, 'caltech.edu', domain) ) AS email
FROM names NATURAL JOIN houselist JOIN ll_participate ON id = participant_id
    JOIN ski_participant ON id = occupant_id NATURAL JOIN ski2010_3
WHERE ll_event_id = 2
INTO OUTFILE '/tmp/ski_mail_20110107.txt'
    FIELDS TERMINATED BY ';'
    LINES TERMINATED BY '\n';

