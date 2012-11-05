DROP VIEW IF EXISTS ski2010_2;

CREATE VIEW ski2010_2 AS
    SELECT
        id, lastname, name, cabin_name, IF( lift_ticket = 1, 'Yes', 'No' ) AS lift,
        driver_seats + 1 AS seats, paid_amount, IF( paid_to_id IS NULL, 'N/A', (SELECT name FROM names WHERE id = paid_to_id )) AS paid_to,
        IF( membership NOT IN ('s', 'f'), lift_ticket * 55 + 80.00, lift_ticket * 40 + 60.00 ) AS price
    FROM names NATURAL JOIN houselist
        JOIN ll_participate ON id = participant_id
        JOIN ski_participant ON id = occupant_id NATURAL JOIN ski_cabins
    WHERE ll_event_id = 2;
