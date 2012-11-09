/*
 * lloyd_tokens.sql
 *
 * SQL table definitions for login purposes.
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

DROP TABLE IF EXISTS ll_tokens;

/*
 * Table of access tokens
 */
CREATE TABLE ll_tokens (
  -- Token grants access as this ID
  ll_id             INT NOT NULL,
  -- CHAR(32) would also work
  ll_token          BINARY(16),
  -- Allows use of a different token for each event
  ll_event_id       INT NOT NULL,

  PRIMARY KEY (ll_token),
  FOREIGN KEY (ll_id)
    REFERENCES lloyd.houselist(id)
      ON UPDATE CASCADE ON DELETE CASCADE
);

DROP PROCEDURE IF EXISTS ll_create_tokens;

/*
 * Populate ll_tokens with houselist using some event ID
 */
CREATE PROCEDURE ll_tokens_create (ll_event_id INT)
BEGIN
  INSERT INTO ll_tokens (`ll_id`, `ll_token`, `ll_event_id`)
    SELECT id, MD5(RAND()), ll_event_id
      FROM lloyd.houselist;
END;

