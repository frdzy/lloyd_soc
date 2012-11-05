DROP TABLE IF EXISTS ski_cabins;
DROP TABLE IF EXISTS ski_participant;

CREATE TABLE ski_cabins (
    cabin_id        INT AUTO_INCREMENT,
    cabin_name      VARCHAR(50) UNIQUE NOT NULL,
    cabin_space     INT NOT NULL,

    PRIMARY KEY( cabin_id )
);

CREATE TABLE ski_participant (
    occupant_id     INT,
    cabin_id        INT NOT NULL,
    lift_ticket     TINYINT(1) NOT NULL DEFAULT 0,

    PRIMARY KEY( occupant_id ),
    FOREIGN KEY( occupant_id )
        REFERENCES lloyd.ll_participate( participant_id )
            ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY( cabin_id )
        REFERENCES lloyd.ski_cabins( cabin_id )
            ON UPDATE CASCADE ON DELETE CASCADE
);
