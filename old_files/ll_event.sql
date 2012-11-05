DROP TABLE IF EXISTS event;
DROP TABLE IF EXISTS ll_event;
DROP TABLE IF EXISTS ll_participate;

CREATE TABLE event (
    event_id        INT AUTO_INCREMENT,
    event_type      VARCHAR(20) NOT NULL,
    event_date      DATE NOT NULL,
    event_loc       VARCHAR(50) NOT NULL,
    start_time      TIME,
    end_time        TIME,
    notes           VARCHAR(200),

    PRIMARY KEY( event_id )
);

CREATE TABLE ll_event (
    event_id        INT,
    event_type      VARCHAR(20) NOT NULL,
    ll_event_id     INT UNIQUE AUTO_INCREMENT,

    cost            NUMERIC(11,2) NOT NULL default 0.0,
    organizer_id    INT(11) NOT NULL,

    PRIMARY KEY( event_id ),
    FOREIGN KEY( event_id, event_type )
        REFERENCES fred.event( event_id, event_type )
            ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY( organizer_id )
        REFERENCES lloyd.houselist( id )
            ON UPDATE CASCADE ON DELETE CASCADE,
    CHECK( event_type = 'll_event' )
);

CREATE TABLE ll_participate (
    ll_event_id     INT,
    participant_id  INT,

    paid_amount     NUMERIC(11,2) NOT NULL default 0.0,
    paid_to_id      INT,

    driver_id       INT,
    driver_seats    INT DEFAULT 0,

    PRIMARY KEY( ll_event_id, participant_id ),
    FOREIGN KEY( ll_event_id )
        REFERENCES ll_event( ll_event_id )
            ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY( participant_id )
        REFERENCES lloyd.houselist( id )
            ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY( paid_to_id )
        REFERENCES lloyd.houselist( id )
            ON UPDATE CASCADE ON DELETE CASCADE
    FOREIGN KEY( driver_id )
        REFERENCES lloyd.houselist( id )
            ON UPDATE CASCADE ON DELETE CASCADE
);
