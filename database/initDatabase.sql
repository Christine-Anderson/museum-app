CREATE TABLE Article (
    articleID INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    dateAquired TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    condition VARCHAR(50) NOT NULL,
    storageLocation VARCHAR(50) NOT NULL,
    UVProtection CHAR(1) NOT NULL,
    tempControl CHAR(1) NOT NULL,
    humidityControl CHAR(1) NOT NULL
);

CREATE TABLE Artwork (
    articleID INT(11) PRIMARY KEY,
    artist VARCHAR(50),
    yearMade INT(10),
    medium VARCHAR(50),
    FOREIGN KEY(articleID) REFERENCES(Article)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Text (
    articleID INT(11) PRIMARY KEY,
    author VARCHAR(50),
    datePublished DATE,
    FOREIGN KEY(articleID) REFERENCES(Article)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Photo (
    articleID INT(11) PRIMARY KEY,
    yearTaken INT(10),
    locationTaken VARCHAR(50),
    FOREIGN KEY(articleID) REFERENCES(Article)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Artifact (
    articleID INT(11) PRIMARY KEY,
    estimatedYear INT(10),
    madeBy VARCHAR(50),
    material VARCHAR(50),
    FOREIGN KEY(articleID) REFERENCES(Article)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY(creator) REFERENCES(Origin)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
);

CREATE TABLE Origin (
    creator VARCHAR(50) PRIMARY KEY,
    regionOfOrigin VARCHAR(50) NOT NULL
);

CREATE TABLE NaturalSpecimen (
    articleID INT(11) PRIMARY KEY,
    speciesName VARCHAR(50),
    timePeriod VARCHAR(50),
    FOREIGN KEY(articleID) REFERENCES(Article)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    FOREIGN KEY(speciesName) REFERENCES(Species)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
);

CREATE TABLE NaturalSpecimen (
    speciesName VARCHAR(50) PRIMARY KEY,
    nativeTo VARCHAR(50) NOT NULL
);

CREATE TABLE Contract (
    contractID INT(11) PRIMARY KEY AUTO_INCREMENT,
    startDate DATE NOT NULL,
    endDate DATE NOT NULL,
    text VARCHAR(200) NOT NULL
);

CREATE TABLE Owner (
    ownerID INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50),
    address VARCHAR(100),
    phoneNum VARCHAR(20)
);

CREATE TABLE Writes(
    ownerID INT(11),
    contractID INT(11),
    FOREIGN KEY(ownerID) REFERENCES(Owner),
    FOREIGN KEY(contractID) REFERENCES(Contract)
);

CREATE TABLE Loans(
    ownerID INT(11),
    contractID INT(11),
    FOREIGN KEY(ownerID) REFERENCES(Owner),
    FOREIGN KEY(contractID) REFERENCES(Contract)
);

CREATE TABLE PertainsTo(
    contractID INT(11),
    articleID INT(11),
    FOREIGN KEY(contractID) REFERENCES(Contract),
    FOREIGN KEY(articleID) REFERENCES(Article)
);

CREATE TABLE Displays(
    exhibitID INT(11),
    articleID INT(11),
    FOREIGN KEY(exhibitID) REFERENCES(Exhibit),
    FOREIGN KEY(articleID) REFERENCES(Article)
);

-- INSERT
-- INTO Article(articleID, name, dateAquired, condition,storageLocation, UVProtection, tempControl, humidityControl)
-- VALUES ()