DROP VIEW IF EXISTS ski2012_cabins;

CREATE VIEW ski2012_cabins AS
    SELECT cabin_name, cabin_space, COUNT(occupant_id) AS occupants
    FROM ski_cabins LEFT OUTER JOIN ski_participant
        ON ski_cabins.cabin_id = ski_participant.cabin_id AND ll_event_id = 5
    WHERE available
    GROUP BY ski_cabins.cabin_name, ll_event_id
    ORDER BY ski_cabins.cabin_id;
/*
    SELECT part.id, part.name AS name, paid_amount,
        IF(paidto.name IS NULL, 'N/A', paidto.name) AS paid_to,
        (SELECT cost FROM ll_event AS t WHERE t.ll_event_id = ll_event_id)
                - paid_amount AS need_to_pay, driver_seats + 1 AS seats,
        IF(driven_by.name IS NULL, '', driven_by.name) AS driven_by
    FROM ll_participate NATURAL JOIN ll_event NATURAL JOIN fred.event
        JOIN houselist ON participant_id = houselist.id
        JOIN names AS part ON participant_id = part.id
        LEFT OUTER JOIN names AS paidto ON paid_to_id = paidto.id
        LEFT OUTER JOIN names AS driven_by ON driver_id = driven_by.id
    WHERE ll_event_id = 1
    ORDER BY (IF (paid_amount >= 15, 1, 0) ), driven_by.name,
        driver_seats DESC, lastname, name DESC;
*/
