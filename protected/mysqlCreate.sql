-- Create and use database
CREATE DATABASE destructoPad;
USE destructoPad;

-- Create tables.
CREATE TABLE padEntry (
    padID BINARY(32) NOT NULL,
    padExpire TINYINT NOT NULL,
    padData BLOB NOT NULL,
    PRIMARY KEY( padID )
);

-- Set up permissions on the padEntry table for each user.
-- CHANGE BOTH OF THESE PASSWORDS, AND THEN CHANGE THEM IN THE data.php CONFIG!
GRANT SELECT,INSERT,DELETE ON padEntry TO 'padProc'@'localhost' IDENTIFIED BY 'Blah@ASD4q5FA4asb';
GRANT UPDATE,DELETE ON padEntry TO 'padUpdateProc'@'localhost' IDENTIFIED BY 'WhasdlktjaGarbl!';

-- Create our sprocs
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

-- Set up necessary execute permissions on sprocs.
-- expirePad should be called using CRON or AT once an hour through
-- the expireOnTime.php script.
GRANT EXECUTE ON PROCEDURE addPad TO 'padProc'@'localhost';
GRANT EXECUTE ON PROCEDURE getPad TO 'padProc'@'localhost';
GRANT EXECUTE ON PROCEDURE expirePad TO 'padUpdateProc'@'localhost';

FLUSH PRIVILEGES;
