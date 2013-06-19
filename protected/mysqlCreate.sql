CREATE DATABASE destructoPad;

USE destructoPad;

CREATE TABLE padEntry (
    padID BINARY(32) NOT NULL,
    padExpire TINYINT NOT NULL,
    padData BLOB NOT NULL,
    PRIMARY KEY( padID )
);

CREATE USER 'padProc'@'localhost' IDENTIFIED BY 'Blah@ASD4q5FA4asb';
GRANT SELECT,INSERT ON destructoPad.padEntry TO 'padProc'@'localhost';

CREATE USER 'padUpdateProc'@'localhost' IDENTIFIED BY 'WhasdlktjaGarbl!';
GRANT UPDATE,DELETE ON destructoPad.padEntry TO 'padUpdateProc'@'localhost';

DELIMITER ?
CREATE PROCEDURE addPad(IN hash BINARY(32), expire TINYINT(4), padData BLOB)
BEGIN
    INSERT INTO padEntry VALUES (hash, expire, padData);
END ?

DELIMITER ?
CREATE PROCEDURE expirePad()
BEGIN
    DELETE FROM padEntry WHERE padExpire <= 0;
    UPDATE padEntry SET padExpire = padExpire - 1;
END ?

-- These don't work for some reason...
--GRANT EXECUTE ON addPad TO 'padProc'@'localhost';
--GRANT EXECUTE ON expirePad TO 'padUpdateProc'@'localhost';

