CREATE DATABASE destructoPad;

USE destructoPad;

CREATE TABLE padEntry (
    padID BINARY(32) NOT NULL,
    padExpire TINYINT NOT NULL,
    padData BLOB NOT NULL,
    PRIMARY KEY( padID )
);

GRANT SELECT,INSERT ON destructoPad.padEntry TO 'padProc'@'localhost' IDENTIFIED BY 'Blah@ASD4q5FA4asb';
GRANT UPDATE,DELETE ON destructoPad.padEntry TO 'padUpdateProc'@'localhost' IDENTIFIED BY 'WhasdlktjaGarbl!';

DELIMITER ?
CREATE PROCEDURE addPad(IN hash BINARY(32), expire TINYINT(4), padData BLOB)
BEGIN
    INSERT INTO padEntry VALUES (hash, expire, padData);
END ?

CREATE PROCEDURE getPad(IN hash BINARY(32), OUT padDataOut BLOB)
BEGIN
    SELECT padData INTO padDataOut FROM padEntry WHERE padID = hash LIMIT 1;
    DELETE FROM padEntry WHERE padID = hash;
END ?

CREATE PROCEDURE expirePad()
BEGIN
    DELETE FROM padEntry WHERE padExpire <= 0;
    UPDATE padEntry SET padExpire = padExpire - 1;
END ?

DELIMITER ;

GRANT EXECUTE ON PROCEDURE destructoPad.addPad TO 'padProc'@'localhost';
GRANT EXECUTE ON PROCEDURE destructoPad.getPad TO 'padProc'@'localhost';
GRANT EXECUTE ON PROCEDURE destructoPad.expirePad TO 'padUpdateProc'@'localhost';
