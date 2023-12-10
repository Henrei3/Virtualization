DROP SCHEMA IF EXISTS projet CASCADE;
CREATE SCHEMA projet;

CREATE TABLE projet.logs (
    id_log SERIAL NOT NULL PRIMARY KEY,
    log     VARCHAR(255) NOT NULL,
    date    TIMESTAMP   NOT NULL
);
  

INSERT INTO projet.logs VALUES (DEFAULT, 'jean' ,'2023-03-11');
INSERT INTO projet.logs VALUES (DEFAULT, 'pierre' ,'2023-04-11');