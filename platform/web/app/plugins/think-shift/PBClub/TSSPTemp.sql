USE thinkshiftdb;

## Store procedures for get and puts
SET DELIMITER //

/** Create support procedures/functions
		
**/

DROP PROCEDURE IF EXISTS ts_email_exists// ## Tested
## return true/false on check for email exists by dbid
CREATE PROCEDURE ts_email_exists(IN eml VARCHAR(256))
BEGIN
	SET @val=(SELECT Id FROM Email WHERE Address=eml);
	IF @val IS NULL THEN 
		SELECT FALSE;
	ELSE
		SELECT TRUE;
	END IF;
END//

DROP PROCEDURE IF EXISTS ts_contact_db_id_by_email// ## Tested
## find existing contact id by email address
CREATE PROCEDURE ts_contact_db_id_by_email(IN eml VARCHAR(256))
BEGIN
	SELECT Id FROM Email WHERE Address=eml;
END//

DROP PROCEDURE IF EXISTS ts_contact_exists// ## Tested
## return true/false on check for contact exists by dbid
CREATE PROCEDURE ts_contact_exists(IN eml VARCHAR(256))
BEGIN
	SET @eId=(SELECT Id FROM Email WHERE Address=eml);
    SELECT Id INTO @yes FROM Contact WHERE Email1=@eId;
    IF @yes IS NULL THEN
		SELECT FALSE;
	ELSE
		SELECT TRUE;
	END IF;
END//

DROP PROCEDURE IF EXISTS ts_set_email_status// ## Tested
## method for modifying the email status
CREATE PROCEDURE ts_set_email_status(IN email VARCHAR(256),
									    eStatus INT)
BEGIN
	UPDATE Email SET Status=eStatus WHERE Address=email;
END//

DROP PROCEDURE IF EXISTS ts_email_create// ## Tested
## create
CREATE PROCEDURE ts_email_create(IN eAddress VARCHAR(256),
									eStatus INT)
BEGIN
	SET @val=(SELECT Id FROM Email WHERE Address=eAddress);
    IF @val IS NULL THEN
		INSERT INTO Email (Address, Status) VALUES (eAddress, eStatus);
	ELSE
		UPDATE Email SET Status=eStatus WHERE Id=@val;
	END IF;
    SELECT Id FROM Email WHERE Address=eAddress;
END//

DROP PROCEDURE IF EXISTS ts_contact_create// ## Tested
## create
CREATE PROCEDURE ts_contact_create(IN fname VARCHAR(100),
									  lname VARCHAR(100),
                                      email VARCHAR(256))
BEGIN
	SET @eId=(SELECT Id FROM Email WHERE Address=email);
    IF @eId IS NULL THEN
		INSERT INTO Email (Address, Status) VALUES (email, 0);
        SET @eId=(SELECT Id FROM Email WHERE Address=email);
	END IF;
    SET @cId=(SELECT Id FROM Contact WHERE Email1=@eId);
    IF @cId IS NULL THEN
		INSERT INTO Contact (FirstName, LastName, Email1) VALUES (fname, lname, @eId);
        SET @cId=(SELECT Id FROM Contact WHERE Email1=@eId);
	END IF;
    SELECT @cId;
END//

DROP PROCEDURE IF EXISTS ts_tag_create// ## Tested
## create
CREATE PROCEDURE ts_tag_create(IN tagName VARCHAR(256),
								  tagDesc TEXT,
                                  tagCatId INT)
BEGIN
	SET @tcId=(SELECT Id FROM ContactGroupCategory WHERE Id=tagCatId);
    IF @tcId IS NULL THEN
		SET @tcId=1;
	ELSE
		SET @tcId=tagCatId;
	END IF;
	SET @tId=(SELECT Id FROM ContactGroup WHERE GroupName=tagName AND 
		GroupDesc=tagDesc AND GroupCatId=tagCatId);
	IF @tId IS NULL THEN
		INSERT INTO ContactGroup (GroupName, GroupDesc, GroupCatId) VALUES
			(tagName, tagDesc, tagCatId);
		SET @tId=(SELECT Id FROM ContactGroup WHERE GroupName=tagName AND 
		GroupDesc=tagDesc AND GroupCatId=tagCatId);
	END IF;
    SELECT @tId;
END//

