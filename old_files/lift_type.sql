SELECT
    SUBSTRING_INDEX(name, ' ', 1) AS firstname,
    CONCAT( email, '@', IF(domain IS NULL, 'caltech.edu', domain) ) AS email
FROM names NATURAL JOIN houselist JOIN ll_participate ON id = participant_id
    JOIN ski_participant ON id = occupant_id NATURAL JOIN ski2010_2
WHERE ll_event_id = 2 AND lift = 'Yes'
INTO OUTFILE '/tmp/ski_mail_lift_type.txt'
    FIELDS TERMINATED BY ';'
    LINES TERMINATED BY '\n';

