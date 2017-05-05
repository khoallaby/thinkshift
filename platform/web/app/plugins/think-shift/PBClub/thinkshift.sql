USE thinkshiftdb;

CREATE TABLE IF NOT EXISTS OAuth2 (
    Id            INTEGER       NOT NULL        PRIMARY KEY ,
    AppName       VARCHAR(5)    NOT NULL ,
    AccessToken   VARCHAR(100)  NOT NULL ,
    RefreshToken  VARCHAR(100)  NOT NULL ,
    GeneratedDate DATETIME      NOT NULL
);

CREATE TABLE IF NOT EXISTS Transactions (
    Id            INTEGER       NOT NULL        PRIMARY KEY ,
    JSON          TEXT          NOT NULL
);

CREATE TABLE IF NOT EXISTS DataFormField (
    Id            INTEGER       NOT NULL        PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL ,
    Is_id         INTEGER       NOT NULL ,
    DataType      INTEGER       NOT NULL ,
    DefaultValue  VARCHAR(256)  NOT NULL ,
    FormId        INTEGER       NOT NULL ,
    GroupId       INTEGER       NOT NULL ,
    Label         VARCHAR(256)  NOT NULL ,
    ListRows      INTEGER       NOT NULL ,
    Name          VARCHAR(256)  NOT NULL ,
    FieldValues   VARCHAR(256)  NOT NULL
);

CREATE TABLE IF NOT EXISTS ContactGroupCategory (
    Id            INTEGER       NOT NULL        PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL ,
    Is_id         INTEGER       NOT NULL ,
    CategoryName  VARCHAR(256)  NOT NULL ,
    CategoryDesc  TEXT          NOT NULL
);

CREATE TABLE IF NOT EXISTS ContactGroup (
    Id            INTEGER       NOT NULL        PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL ,
    Is_id         INTEGER       NOT NULL ,
    GroupName     VARCHAR(256)  NOT NULL ,
    GroupDesc     TEXT          NOT NULL ,
    GroupCatId    INTEGER       NOT NULL ,
    CONSTRAINT ContactGroup_fk_ContactGroupCategory FOREIGN KEY (GroupCatId)
      REFERENCES ContactGroupCategory (Id)
);

CREATE TABLE IF NOT EXISTS Address (
    Id            INTEGER       NOT NULL        PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL ,
    Is_id         INTEGER       NOT NULL ,
    AddressType   INTEGER       NOT NULL ,  ## Other=0, Mailing=1, Billing=2, Shipping=3
    Street1       VARCHAR(100)  NOT NULL ,
    Street2       VARCHAR(100)  NOT NULL ,
    City          VARCHAR(25)   NOT NULL ,
    State         VARCHAR(20)   NOT NULL ,
    County        VARCHAR(30)   NOT NULL ,
    Country       VARCHAR(50)   NOT NULL ,
    PostalCode    VARCHAR(12)   NOT NULL ,
    Zip4          VARCHAR(4)    NOT NULL
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
    Id            INTEGER       NOT NULL        PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL ,
    Is_id         INTEGER       NOT NULL ,
    Status        INTEGER       NOT NULL , ## see address status notes above
    Address       VARCHAR(256)
);

CREATE TABLE IF NOT EXISTS Phone (
    Id            INTEGER       NOT NULL        PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL ,
    Is_id         INTEGER       NOT NULL ,
    PhoneType     VARCHAR(30)   NOT NULL , ## Home, Cell, Business, Fax1, Fax2
    PhoneNumber   VARCHAR(30)   NOT NULL ,
    PhoneExt      VARCHAR(5)    NOT NULL
);

CREATE TABLE IF NOT EXISTS Contact (
    Id            INTEGER       NOT NULL        PRIMARY KEY ,
    Wp_id         INTEGER       NOT NULL ,
    Is_id         INTEGER       NOT NULL ,
    AccountId     INTEGER       NOT NULL ,
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
    ContactType   VARCHAR(30)   NOT NULL ,
    CreatedBy     INTEGER ,
    DateCreated   DATETIME      NOT NULL ,
    Email1        INTEGER ,
    Email2        INTEGER ,
    Email3        INTEGER ,
    FirstName     VARCHAR(100)  NOT NULL ,
    Tags          TEXT , ## comma separated list of IS tag ids
    JobTitle      VARCHAR(100) ,
    Language      VARCHAR(50) ,
    LastName      VARCHAR(100) ,
    LastUpdated   DATETIME      NOT NULL ,
    LastUpdatedBy INTEGER       NOT NULL ,
    LeadSourceId  INTEGER ,
    LeadSource    VARCHAR(256) ,
    MiddleName    VARCHAR(100) ,
    Nickname      VARCHAR(100) ,
    OwnerId       INTEGER       NOT NULL ,
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