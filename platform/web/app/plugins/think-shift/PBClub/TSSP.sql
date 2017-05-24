USE thinkshiftdb;

## Store procedures for get and puts
SET DELIMITER //

/** Create support procedures/functions
		
**/

DROP PROCEDURE IF EXISTS ts_contact_db_id_by_email//
## find existing contact id by email address
CREATE PROCEDURE ts_contact_db_id_by_email(IN eml VARCHAR(256))
BEGIN
	SELECT Id FROM Email WHERE Address=eml;
END//

DROP PROCEDURE IF EXISTS ts_contact_db_id_by_wp_id//
## find existing contact id by wp id
CREATE PROCEDURE ts_contact_db_id_by_wp_id(IN wpid INT,
								  		   OUT dbid INT)
BEGIN
	SET dbid=(SELECT Id FROM Contact WHERE Wp_id=wpid);
END//               

DROP PROCEDURE IF EXISTS ts_contact_db_id_by_is_id//
## find existing contact id by Infusionsoft id
CREATE PROCEDURE ts_contact_db_id_by_is_id(IN isid INT,
										   OUT dbid INT)
BEGIN
	SET dbid=(SELECT Id FROM Contact WHERE Is_id=isid);
END//                                           

DROP PROCEDURE IF EXISTS ts_contact_exists//
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
CREATE PROCEDURE ts_tag_exists(IN dbid INT,
							   OUT yes BOOL)
BEGIN
	SET @val=(SELECT Id FROM ContactGroup WHERE Id=dbid);
	IF @val IS NULL THEN 
		SET yes=FALSE;
	ELSE
		SET yes=TRUE;
	END IF;
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

DROP PROCEDURE IF EXISTS ts_email_exists//
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

DROP PROCEDURE IF EXISTS ts_set_email_status//
## method for modifying the email status
CREATE PROCEDURE ts_set_email_status(IN email VARCHAR(256),
									    eStatus INT)
BEGIN
	UPDATE Email SET Status=eStatus WHERE Address=email;
END//                                        


/** CRUD stored procedures for the email table 
	(start with basic fields and then add more fields later if need be
	Currently just using FirstName, LastName & Email
		ts_email_create(IN firstname VARCHAR(100),
									  lastname VARCHAR(100),
                                      email VARCHAR(256),
						  OUT isid INT)
		ts_email_read(IN dbid INT,
						OUT fname VARCHAR(100),
                            lname VARCHAR(100),
                            eml VARCHAR(256))
		ts_email_update(IN dbid INT,
							 fname VARCHAR(100),
                             lname VARCHAR(100))
		ts_email_delete(IN dbid INT)
**/

DROP PROCEDURE IF EXISTS ts_email_create//
## create
CREATE PROCEDURE ts_email_create(IN eAddress VARCHAR(256),
									eStatus INT,
								 OUT dbid INT)
BEGIN
	INSERT INTO Email (Address, Status) VALUES (eAddress, eStatus);
	SET dbid=(SELECT Id FROM Email WHERE Address=eAddress);        
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

/** Probably should not allow email update/delete to mainain history
DROP PROCEDURE IF EXISTS ts_email_update;
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
*/


/** CRUD stored procedures for the contact table 
	(start with basic fields and then add more fields later if need be
	Currently just using FirstName, LastName & Email
		ts_contact_create(IN firstname VARCHAR(100),
									  lastname VARCHAR(100),
                                      email VARCHAR(256),
						  OUT isid INT)
		ts_contact_read(IN dbid INT,
						OUT fname VARCHAR(100),
                            lname VARCHAR(100),
                            eml VARCHAR(256))
		ts_contact_update(IN dbid INT,
							 fname VARCHAR(100),
                             lname VARCHAR(100))
		ts_contact_delete(IN dbid INT)
**/

DROP PROCEDURE IF EXISTS ts_contact_create//
## create
CREATE PROCEDURE ts_contact_create(IN firstname VARCHAR(100),
									  lastname VARCHAR(100),
                                      email VARCHAR(256),
								   OUT dbid INT)
BEGIN
	CALL ts_email_create(email, 0, @emlId);
	INSERT INTO Contact (FirstName, LastName, Email1) VALUES (firstname, lastname, @emlId);
    SELECT Id INTO dbid FROM Contact WHERE Email1=@emlId;
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


/** CRUD stored procedures for the tag (ContactGroup) table
		ts_tag_create(IN tagName VARCHAR(256),
						 tagDesc TEXT,
                         tagCatId INT,
					  OUT dbid INT)
		ts_tag_read(IN dbid INT,
					OUT tagName VARCHAR(256),
						tagDesc TEXT,
						tagCatId INT)
		ts_tag_update(IN dbid INT,
						 tagName VARCHAR(256),
                         tagDesc TEXT,
                         tagCatId INT)
		ts_tag_delete(IN dbid INT)
**/

DROP PROCEDURE IF EXISTS ts_tag_create//
## create
CREATE PROCEDURE ts_tag_create(IN tagName VARCHAR(256),
								  tagDesc TEXT,
                                  tagCatId INT,
							   OUT dbid INT)
BEGIN
	INSERT INTO ContactGroup (GroupName, GroupDesc, GroupCatId) 
		VALUES (tagName, tagDesc, tagCatId);
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

/** add/update/read tag category
	delete later
			ts_tag_category_create(IN tagCategoryName VARCHAR(256),
									  tagCategoryDesc TEXT,
								   OUT dbid INT)
			ts_tag_category_update(IN dbid INT,
									  tagCategoryName VARCHAR(256),
									  tagCategoryDesc TEXT)
			ts_tag_category_read(IN dbid INT,
								 OUT tagCategoryName VARCHAR(256),
									 tagCategoryDesc TEXT)

**/

DROP PROCEDURE IF EXISTS ts_tag_category_create//
## create
CREATE PROCEDURE ts_tag_category_create(IN tagCategoryName VARCHAR(256),
										   tagCategoryDesc TEXT,
										OUT dbid INT)
BEGIN
	INSERT INTO ContactGroupCategory (CategoryName, CategoryDesc) 
		VALUES (tagCategoryName, tagCategoryDesc);
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

/** add/update stored procedures for the custom field (DataFormField) table
	edit later
			ts_custom_field_create(IN cfLabel VARCHAR(256),
									  cfDataType INT, ## create a data type method in the db access object
								   OUT dbid INT)
**/

DROP PROCEDURE IF EXISTS ts_custom_field_create//
## create
CREATE PROCEDURE ts_custom_field_create(IN cfLabel VARCHAR(256),
										   cfDataType INT, ## create a data type method in the db access object
										OUT dbid INT)
BEGIN
	INSERT INTO DataFormField (DataType,FormId,Label) VALUES (cfDataType, -1, cfLabel);
END//

SET DELIMITER ;