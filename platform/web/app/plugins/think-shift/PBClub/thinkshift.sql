DROP SCHEMA IF EXISTS thinkshiftdb;

CREATE DATABASE thinkshiftdb;

USE thinkshiftdb;

CREATE TABLE IF NOT EXISTS OAuth2 (
    Id            INTEGER       NOT NULL        AUTO_INCREMENT		PRIMARY KEY ,
    AppName       VARCHAR(5)    NOT NULL 		DEFAULT '',
    AccessToken   VARCHAR(100)  NOT NULL 		DEFAULT '',
    RefreshToken  VARCHAR(100)  NOT NULL 		DEFAULT '',
    GeneratedDate DATETIME      NOT NULL		DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS Transactions (
    Id            INTEGER       NOT NULL        AUTO_INCREMENT		PRIMARY KEY ,
    JSON          TEXT
);

CREATE TABLE IF NOT EXISTS DataFormField (
    Id            INTEGER       NOT NULL        AUTO_INCREMENT		PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL 		DEFAULT 0,
    Is_id         INTEGER       NOT NULL 		DEFAULT 0,
    DataType      INTEGER       NOT NULL 		DEFAULT 15,
    DefaultValue  VARCHAR(256)  NOT NULL 		DEFAULT '',
    FormId        INTEGER       NOT NULL 		DEFAULT -1,
    GroupId       INTEGER       NOT NULL 		DEFAULT 0,
    Label         VARCHAR(256)  NOT NULL 		DEFAULT '',
    ListRows      INTEGER       NOT NULL 		DEFAULT 0,
    Name          VARCHAR(256)  NOT NULL 		DEFAULT '',
    FieldValues   VARCHAR(256)  NOT NULL		DEFAULT ''
);

CREATE TABLE IF NOT EXISTS ContactGroupCategory (
    Id            INTEGER       NOT NULL        AUTO_INCREMENT		PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL 		DEFAULT 0,
    Is_id         INTEGER       NOT NULL 		DEFAULT 0,
    CategoryName  VARCHAR(256)  NOT NULL 		DEFAULT '',
    CategoryDesc  TEXT
);

DROP TABLE IF EXISTS ContactGroup;
CREATE TABLE IF NOT EXISTS ContactGroup (
    Id            INTEGER       NOT NULL        AUTO_INCREMENT		PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL 		DEFAULT 0,
    Is_id         INTEGER       NOT NULL 		DEFAULT 0,
    GroupName     VARCHAR(256)  NOT NULL 		DEFAULT '',
    GroupDesc     TEXT,
    GroupCatId    INTEGER       DEFAULT 0
);

CREATE TABLE IF NOT EXISTS Address (
    Id            INTEGER       NOT NULL        AUTO_INCREMENT		PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL 		DEFAULT 0,
    Is_id         INTEGER       NOT NULL 		DEFAULT 0,
    AddressType   INTEGER       NOT NULL 		DEFAULT 1,  ## Other=0, Mailing=1, Billing=2, Shipping=3
    Street1       VARCHAR(100)  NOT NULL 		DEFAULT '',
    Street2       VARCHAR(100)  NOT NULL 		DEFAULT '',
    City          VARCHAR(25)   NOT NULL 		DEFAULT '',
    State         VARCHAR(20)   NOT NULL 		DEFAULT '',
    County        VARCHAR(30)   NOT NULL 		DEFAULT '',
    Country       VARCHAR(50)   NOT NULL 		DEFAULT '',
    PostalCode    VARCHAR(12)   NOT NULL 		DEFAULT '',
    Zip4          VARCHAR(4)    NOT NULL		DEFAULT ''
);

/**
Status (for email table 'Status')
    SingleOptIn=0 - This person has opted in but not confirmed their email address
    UnengagedMarketable=1 - This person has been unengaged for a period of time
    DoubleOptin=2 - This person has clicked an email confirmation link.
    Confirmed=3 - This person has confirmed their email address.
    UnengagedNonMarketable=4 - This person has been unengaged for too long a period of time to be marketed to
    NonMarketable=5 - There is no evidence that this person has consented to receive marketing.
    Lockdown=6 - This person was added while the app was locked down.
    Bounce=7 - This person's email address has bounced too many times.
    HardBounce=8 - This person's email address has hard bounced.
    Manual=9 - This person has opted out of all email marketing.
    Admin=10 - This person was manually opted out by an administrator.
    ListUnsubscribe=11 - This person has opted out of all email marketing.
    Feedback=12 - This person reported spam messages to his/her provider.
    Spam=13 - This person provided feedback when opting out.
    Invalid=14 - This email address failed the regular expression validation

 */
CREATE TABLE IF NOT EXISTS Email (
    Id            INTEGER       NOT NULL        AUTO_INCREMENT		PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL 		DEFAULT 0,
    Is_id         INTEGER       NOT NULL 		DEFAULT 0,
    Status        INTEGER       NOT NULL 		DEFAULT 0, ## see address status notes above
    Address       VARCHAR(256)	UNIQUE
);

CREATE TABLE IF NOT EXISTS Phone (
    Id            INTEGER       NOT NULL        AUTO_INCREMENT		PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL 		DEFAULT 0,
    Is_id         INTEGER       NOT NULL 		DEFAULT 0,
    PhoneType     VARCHAR(30)   NOT NULL 		DEFAULT '', ## Home, Cell, Business, Fax1, Fax2
    PhoneNumber   VARCHAR(30)   NOT NULL 		DEFAULT '',
    PhoneExt      VARCHAR(5)    NOT NULL		DEFAULT ''
);

CREATE TABLE IF NOT EXISTS Contact (
    Id            INTEGER       NOT NULL        AUTO_INCREMENT		PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL 		DEFAULT 0,
    Is_id         INTEGER       NOT NULL 		DEFAULT 0,
    AccountId     INTEGER       NOT NULL 		DEFAULT 0,
    Address1      INTEGER ,
    Address2      INTEGER ,
    Address3      INTEGER ,
    Anniversary   DATE ,
    AssistantName VARCHAR(100) ,
    AssistantPhone INTEGER ,
    BillingInfo   VARCHAR(256) ,
    Birthday      DATE ,
    CompanyName   VARCHAR(100) ,
    CompanyId     INTEGER ,
    ContactNotes  TEXT ,
    ContactType   VARCHAR(30)   NOT NULL 		DEFAULT '',
    CreatedBy     INTEGER ,
    DateCreated   DATETIME      NOT NULL 		DEFAULT NOW(),
    Email1        INTEGER 		NOT NULL		DEFAULT 0,
    Email2        INTEGER ,
    Email3        INTEGER ,
    FirstName     VARCHAR(100)  NOT NULL 		DEFAULT '',
    Tags          TEXT , ## comma separated list of IS tag ids
    JobTitle      VARCHAR(100) ,
    Language      VARCHAR(50) ,
    LastName      VARCHAR(100) ,
    LastUpdated   DATETIME      NOT NULL 		DEFAULT NOW(),
    LastUpdatedBy INTEGER       NOT NULL 		DEFAULT 0,
    LeadSourceId  INTEGER ,
    LeadSource    VARCHAR(256) ,
    MiddleName    VARCHAR(100) ,
    Nickname      VARCHAR(100) ,
    OwnerId       INTEGER       NOT NULL 		DEFAULT 0,
    Password      VARCHAR(100) ,
    Phone1        INTEGER ,
    Phone2        INTEGER ,
    Phone3        INTEGER ,
    Phone4        INTEGER ,
    Phone5        INTEGER ,
    ReferralCode  VARCHAR(100) ,
    SpouseName    VARCHAR(256) ,
    Suffix        VARCHAR(20) ,
    TimeZone      VARCHAR(100) ,
    Title         VARCHAR(20) ,
    Username      VARCHAR(50) ,
    Validated     VARCHAR(30) ,
    Website       VARCHAR(256) ,
    CONSTRAINT Contact_fk_Address1 FOREIGN KEY (Address1)
        REFERENCES Address (Id) ,
    CONSTRAINT Contact_fk_Address2 FOREIGN KEY (Address2)
        REFERENCES Address (Id) ,
    CONSTRAINT Contact_fk_Address3 FOREIGN KEY (Address3)
        REFERENCES Address (Id) ,
    CONSTRAINT Contact_fk_Email1 FOREIGN KEY (Email1)
        REFERENCES Email (Id) ,
    CONSTRAINT Contact_fk_Email2 FOREIGN KEY (Email2)
        REFERENCES Email (Id) ,
    CONSTRAINT Contact_fk_Email3 FOREIGN KEY (Email3)
        REFERENCES Email (Id) ,
    CONSTRAINT Contact_fk_Phone1 FOREIGN KEY (Phone1)
        REFERENCES Phone (Id) ,
    CONSTRAINT Contact_fk_Phone2 FOREIGN KEY (Phone2)
        REFERENCES Phone (Id) ,
    CONSTRAINT Contact_fk_Phone3 FOREIGN KEY (Phone3)
        REFERENCES Phone (Id) ,
    CONSTRAINT Contact_fk_Phone4 FOREIGN KEY (Phone4)
        REFERENCES Phone (Id) ,
    CONSTRAINT Contact_fk_Phone5 FOREIGN KEY (Phone5)
        REFERENCES Phone (Id)
);

CREATE TABLE IF NOT EXISTS ContactGroupAssign (
	Id			INTEGER       NOT NULL        AUTO_INCREMENT		PRIMARY KEY ,
    ContactId	INTEGER		  NOT NULL ,
    DateCreated DATETIME	  NOT NULL 		  DEFAULT NOW() ,
    GroupId		INTEGER		  NOT NULL
);