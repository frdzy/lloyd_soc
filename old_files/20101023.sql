DROP VIEW IF EXISTS hp7;

CREATE VIEW hp7 AS
    SELECT part.id, part.name AS name, paid_amount,
        IF(paidto.name IS NULL, 'N/A', paidto.name) AS paid_to,
        (SELECT cost FROM ll_event AS t WHERE t.ll_event_id = ll_event_id)
                - paid_amount AS need_to_pay
    FROM ll_participate NATURAL JOIN ll_event NATURAL JOIN fred.event
        JOIN houselist ON participant_id = houselist.id
        JOIN names AS part ON participant_id = part.id
        LEFT OUTER JOIN names AS paidto ON paid_to_id = paidto.id
        ORDER BY (IF( need_to_pay > 0, 0, 1)), lastname, name DESC;
