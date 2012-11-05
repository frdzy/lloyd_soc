/*
 * lloyd_events.sql
 *
 * SQL table definitions for basic event tracking.
 * Create a database named 'lloyd' and source this in.
 */


/*
 * Provide your own implementation of lloyd.houselist that has at least
 * the following columns:

CREATE TABLE houselist (
  id                INT UNIQUE AUTO_INCREMENT,

  firstname         VARCHAR NOT NULL,
  nickname          VARCHAR NOT NULL DEFAULT '',
  lastname          VARCHAR NOT NULL,
  email             VARCHAR NOT NULL,
  domain            VARCHAR NOT NULL DEFAULT '',

  PRIMARY KEY (id)
);

*/

DROP TABLE IF EXISTS ll_event;
DROP TABLE IF EXISTS ll_event_cost;
DROP TABLE IF EXISTS ll_participate;

/*
 * Basic information about an event
 */
CREATE TABLE ll_event (
  ll_event_id       INT UNIQUE AUTO_INCREMENT,

  -- Event info
  event_desc        VARCHAR(50) NOT NULL,
  event_date_start  DATE NOT NULL,
  event_date_end    DATE NOT NULL,
  event_loc         VARCHAR(100) NOT NULL,
  event_notes       VARCHAR(200) NOT NULL DEFAULT '',

  -- Foreign keys
  event_organizer   INT NOT NULL,

  PRIMARY KEY (ll_event_id),
  FOREIGN KEY (event_organizer)
    REFERENCES lloyd.houselist(id)
      ON UPDATE CASCADE ON DELETE CASCADE
);

/*
 * Cost associated with event
 */
CREATE TABLE ll_event_cost (
  -- Cost info
  cost_desc         VARCHAR(100) NOT NULL,
  cost_amount       NUMERIC(11, 2) NOT NULL DEFAULT 0.0,

  -- Foreign keys
  ll_event_id       INT NOT NULL,

  PRIMARY KEY (ll_event_id, cost_desc),
  FOREIGN KEY (ll_event_id)
    REFERENCES ll_event (ll_event_id)
      ON UPDATE CASCADE ON DELETE CASCADE
);

/*
 * Participant info for a given event
 */
CREATE TABLE ll_participate (
  -- Participant info
  ll_event_id     INT,
  participant_id  INT,

  -- Payment info
  paid_amount     NUMERIC(11,2) NOT NULL DEFAULT 0.0,
  paid_to_id      INT,
  -- Transportation info
  driver_id       INT,
  driver_seats    INT DEFAULT 0,

  PRIMARY KEY (ll_event_id, participant_id),
  FOREIGN KEY (ll_event_id)
    REFERENCES ll_event (ll_event_id)
      ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (participant_id)
    REFERENCES lloyd.houselist (id)
      ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (paid_to_id)
    REFERENCES lloyd.houselist (id)
      ON UPDATE CASCADE ON DELETE CASCADE
  FOREIGN KEY (driver_id)
    REFERENCES lloyd.houselist (id)
      ON UPDATE CASCADE ON DELETE CASCADE
);

