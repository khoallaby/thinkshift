USE thinkshiftdb;

## Store procedures for get and puts
SET DELIMITER //

/** Create support procedures/functions
**/

DROP PROCEDURE IF EXISTS ts_contact_db_id_by_email// ## Tested
## find existing contact id by email address
CREATE PROCEDURE ts_contact_db_id_by_email(IN eml VARCHAR(256))
BEGIN
	SET @eid=(SELECT Id FROM Email WHERE Address=eml);
	SELECT Id FROM Contact WHERE Email1=@eid;
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

DROP PROCEDURE IF EXISTS ts_tag_exists//
## return true/false on check for tag exists by dbid    
CREATE PROCEDURE ts_tag_exists(IN tagName VARCHAR(256))
BEGIN
	SET @val=(SELECT Id FROM ContactGroup WHERE GroupName=tagName);
	IF @val IS NULL THEN 
		SET @yes=FALSE;
	ELSE
		SET @yes=TRUE;
	END IF;
    SELECT @yes;
END//                               

DROP PROCEDURE IF EXISTS ts_cf_exists//
## return true/false on check for custom field exists by dbid
CREATE PROCEDURE ts_cf_exists(IN dbid INT,
							  OUT yes BOOL)
BEGIN
	SET @val=(SELECT Id FROM DataFormField WHERE Id=dbid);
	IF @val IS NULL THEN 
		SET yes=FALSE;
	ELSE
		SET yes=TRUE;
	END IF;
END//           

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

DROP PROCEDURE IF EXISTS ts_phone_exists//
## return true/false on check for phone exists by dbid
CREATE PROCEDURE ts_phone_exists(IN dbid INT,
							     OUT yes BOOL)
BEGIN
	SET @val=(SELECT Id FROM Phone WHERE Id=dbid);
	IF @val IS NULL THEN 
		SET yes=FALSE;
	ELSE
		SET yes=TRUE;
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

DROP PROCEDURE IF EXISTS ts_email_read//
## read
CREATE PROCEDURE ts_email_read(IN dbid INT,
							   OUT eStatus INT,
								   eAddress VARCHAR(256))
BEGIN
	SELECT Address, Status INTO eAddress, eStatus FROM Email;
END//

DROP PROCEDURE IF EXISTS ts_email_update//
DROP PROCEDURE IF EXISTS ts_email_delete//

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
    SELECT @cId, @eId;
END//                                   

DROP PROCEDURE IF EXISTS ts_contact_read//
## read
CREATE PROCEDURE ts_contact_read(IN dbid INT,
								OUT fname VARCHAR(100),
                                    lname VARCHAR(100),
                                    eml VARCHAR(256))
BEGIN
	SELECT FirstName, LastName, Email1 INTO fname, lname, @theEmail FROM Contact WHERE Id=dbid;
    SELECT Address INTO eml FROM Email WHERE Id=@theEmail;
END//

DROP PROCEDURE IF EXISTS ts_contact_update//
## update
CREATE PROCEDURE ts_contact_update(IN dbid INT,
									  fname VARCHAR(100),
                                      lname VARCHAR(100))
BEGIN
	UPDATE Contact
		SET FirstName=fname, LastName=lname
		WHERE Id=dbid;	
END//                                      

DROP PROCEDURE IF EXISTS ts_contact_delete//
## delete
CREATE PROCEDURE ts_contact_delete(IN dbid INT)
BEGIN
	DELETE FROM Contact WHERE Id=dbid;
END//

DROP PROCEDURE IF EXISTS ts_tag_create// ## Tested
## create
CREATE PROCEDURE ts_tag_create(IN tagName VARCHAR(256),
								  tagDesc TEXT,
                                  tagCatId INT)
BEGIN
	SET @tcId=(SELECT Id FROM ContactGroupCategory WHERE Id=tagCatId);
    IF @tcId IS NULL THEN
		SET @tcId=0;
	END IF;
	SET @tId=(SELECT Id FROM ContactGroup WHERE GroupName=tagName);
	IF @tId IS NULL THEN
		INSERT INTO ContactGroup (GroupName, GroupDesc, GroupCatId) VALUES
			(tagName, tagDesc, tagCatId);
		SET @tId=(SELECT Id FROM ContactGroup WHERE GroupName=tagName AND 
		GroupDesc=tagDesc AND GroupCatId=tagCatId);
	END IF;
    SELECT @tId;
END//               

DROP PROCEDURE IF EXISTS ts_tag_read//
## read
CREATE PROCEDURE ts_tag_read(IN dbid INT,
								 OUT tagName VARCHAR(256),
									 tagDesc TEXT,
									 tagCatId INT)
BEGIN
	SELECT GroupName, GroupDesc, GroupCatId INTO tagName, tagDesc, tagCatId 
		FROM ContactGroup WHERE Id=dbid;
END//

DROP PROCEDURE IF EXISTS ts_tag_update//
## update
CREATE PROCEDURE ts_tag_update(IN dbid INT,
								  tagName VARCHAR(256),
                                  tagDesc TEXT,
                                  tagCatId INT)
BEGIN
	UPDATE Contact
		SET GroupName=tagName, GroupDesc=tagDesc, GroupCatId=tagCatId
		WHERE Id=dbid;	
END//

DROP PROCEDURE IF EXISTS ts_tag_delete//
## delete
CREATE PROCEDURE ts_tag_delete(IN dbid INT)
BEGIN
	DELETE FROM ContactGroup WHERE Id=dbid;
END//

DROP PROCEDURE IF EXISTS ts_tag_category_create// ## Tested
## create
CREATE PROCEDURE ts_tag_category_create(IN tagCategoryName VARCHAR(256),
										   tagCategoryDesc TEXT)
BEGIN
	SET @tcId=(SELECT Id FROM ContactGroupCategory WHERE CategoryName=tagCategoryName AND
		CategoryDesc=tagCategoryDesc);
	IF @tcId IS NULL THEN
    	INSERT INTO ContactGroupCategory (CategoryName, CategoryDesc) 
			VALUES (tagCategoryName, tagCategoryDesc);
		SET @tcId=(SELECT Id FROM ContactGroupCategory WHERE CategoryName=tagCategoryName AND
			CategoryDesc=tagCategoryDesc);
	END IF;
    SELECT @tcId;
END//

DROP PROCEDURE IF EXISTS ts_tag_category_update//
## update
CREATE PROCEDURE ts_tag_category_update(IN dbid INT,
										   tagCategoryName VARCHAR(256),
										   tagCategoryDesc TEXT)
BEGIN
	UPDATE Contact
		SET CategoryName=tagCategoryName, CategoryDesc=tagCategoryDesc
		WHERE Id=dbid;	
END//

DROP PROCEDURE IF EXISTS ts_tag_category_read//
## read
CREATE PROCEDURE ts_tag_category_read(IN dbid INT,
									  OUT tagCategoryName VARCHAR(256),
										  tagCategoryDesc TEXT)
BEGIN
	SELECT CategoryName, CategoryDesc INTO tagCategoryName, tagCategoryDesc 
		FROM ContactGroupCategory WHERE Id=dbid;
END//

DROP PROCEDURE IF EXISTS ts_custom_field_create// ## Tested
## create
CREATE PROCEDURE ts_custom_field_create(IN cfLabel VARCHAR(256),
										   cfDataType INT)
BEGIN
	SET @cfId=(SELECT Id FROM DataFormField WHERE Label=cfLabel AND DataType=cfDataType);
    IF @cfId IS NULL THEN
		INSERT INTO DataFormField (Label, DataType) VALUES (cfLabel, cfDataType);
        SET @cfId=(SELECT Id FROM DataFormField WHERE Label=cfLabel AND DataType=cfDataType);
	END IF;
    SELECT @cfId;
END//

SET DELIMITER ;