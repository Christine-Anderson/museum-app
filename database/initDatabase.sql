CREATE TABLE Article (
    articleID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    dateAquired TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    condition VARCHAR(50) NOT NULL,
    storageLocation VARCHAR(50) NOT NULL,
    UVProtection CHAR(1) NOT NULL,
    tempControl CHAR(1) NOT NULL,
    humidityControl CHAR(1) NOT NULL
);

CREATE TABLE Artwork (
    articleID INT PRIMARY KEY,
    artist VARCHAR(50),
    yearMade INT,
    medium VARCHAR(50),
    FOREIGN KEY(articleID) REFERENCES(Article)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Text (
    articleID INT PRIMARY KEY,
    author VARCHAR(50),
    datePublished DATE,
    FOREIGN KEY(articleID) REFERENCES(Article)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Photo (
    articleID INT PRIMARY KEY,
    yearTaken INT,
    locationTaken VARCHAR(50),
    FOREIGN KEY(articleID) REFERENCES(Article)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Artifact (
    articleID INT PRIMARY KEY,
    estimatedYear VARCHAR(50),
    regionOfOrigin VARCHAR(50),
    material VARCHAR(50),
    FOREIGN KEY(articleID) REFERENCES(Article)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
);

CREATE TABLE NaturalSpecimen (
    articleID INT PRIMARY KEY,
    speciesName VARCHAR(50),
    timePeriod VARCHAR(50),
    FOREIGN KEY(articleID) REFERENCES(Article)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    FOREIGN KEY(speciesName) REFERENCES(Species)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
);

CREATE TABLE Species (
    speciesName VARCHAR(50) PRIMARY KEY,
    nativeTo VARCHAR(50) NOT NULL
);

CREATE TABLE Contract (
    contractID INT PRIMARY KEY AUTO_INCREMENT,
    startDate DATE NOT NULL,
    endDate DATE NOT NULL,
    text VARCHAR(200) NOT NULL
);

CREATE TABLE Owner (
    ownerID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    buildingNum INT NOT NULL,
    street VARCHAR(50) NOT NULL,
    postalOrZIPCode CHAR(7) NOT NULL,
    country VARCHAR(50) NOT NULL,
    phoneNum VARCHAR(20) NOT NULL
    FOREIGN KEY(postalOrZIPCode) REFERENCES(PostalCode)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
);

CREATE TABLE PostalCode (
    postalOrZIPCode CHAR(7) PRIMARY KEY,
    city VARCHAR(50) NOT NULL
);

CREATE TABLE Writes(
    ownerID INT,
    contractID INT,
    FOREIGN KEY(ownerID) REFERENCES(Owner),
    FOREIGN KEY(contractID) REFERENCES(Contract)
);

CREATE TABLE Loans(
    ownerID INT,
    contractID INT,
    FOREIGN KEY(ownerID) REFERENCES(Owner),
    FOREIGN KEY(contractID) REFERENCES(Contract)
);

CREATE TABLE PertainsTo(
    contractID INT,
    articleID INT,
    FOREIGN KEY(contractID) REFERENCES(Contract),
    FOREIGN KEY(articleID) REFERENCES(Article)
);

CREATE TABLE Displays(
    exhibitID INT,
    articleID INT,
    FOREIGN KEY(exhibitID) REFERENCES(Exhibit),
    FOREIGN KEY(articleID) REFERENCES(Article)
);

-- if you want to put any items I've added on display in the exhibit, please change storageLocation to "on display"

INSERT
INTO Article(articleID, name, dateAquired, condition, storageLocation, UVProtection, tempControl, humidityControl)
VALUES
    (11111, "The Forest (or The Trees)", 2021-11-05 21:45:59, "storage room 1", "Y", "Y", "Y"),  --borrow from Van art gallery
    (11112, "William Shakespeare's Comedies, Histories, & Tragedies", 2022-01-12 13:45:31, "storage room 5", "Y", "Y", "Y"), --borrow from UBC
    (11113, "Moonrise, Hernandez", 2018-03-03 13:45:46, "storage room 5", "Y", "Y", "Y"),
    (11114, "The Mask of Tutankhamun", 2022-07-16 03:15:50, "storage room 3", "Y", "Y", "Y"), --borrow from Egyptian Museum, Cairo, Egypt
    (11115, "blue whale skeleton", 2022-10-25 09:20:18, "storage room 3", "N", "Y", "Y"),
    (11116, "replica Woolly mammoth", 2021-10-20 06:35:19, "storage room 1", "N", "Y", "Y"), -- borrow from royal bc museum
    (11117, "IBM cheese slicer", 2023-02-09 12:00:12, "storage room 1", "N", "N", "N"), -- borrow from burnaby museum
    (11118, "The Jade Mask of the Maya King Pakal", 2017-03-25 14:40:49, "storage room 3", "Y", "Y", "Y"), 
    (11119, "Ancient Egyptian flint arrowhead", 2023-11-08 18:45:05, "storage room 2", "Y", "Y", "Y"),
    (11120, "Neolithic pottery vase", 2018-07-06 08:05:56, "storage room 3", "Y", "Y", "Y"),
    (11121, "Ancient Sumerian chisel", 2021-09-13 03:25:59, "storage room 3", "Y", "Y", "Y"),
    (11122, "Ammonite fossil", 2019-07-20 12:30:15, "storage room 1", "N", "Y", "Y"),
    (11123, "T. rex skeleton", 2017-03-25 14:40:49, "storage room 5", "N", "Y", "Y"),
    (11124, "Ancient Mosquito in Burmese amber", 2011-04-26 18:30:37, "storage room 5", "N", "Y", "Y"),
    (11125, "Mona Lisa", 2021-09-13 03:25:59, "storage room 1", "Y", "Y", "Y"), -- borrow from  Louvre Museum, Paris, France
    --TODO
    (11126, "Ammonite fossil", 2019-07-20 12:30:15, "storage room 1", "N", "Y", "Y"),
    (11127, "T. rex skeleton", 2017-03-25 14:40:49, "storage room 5", "N", "Y", "Y"),
    (11128, "Ancient Mosquito in Burmese amber", 2011-04-26 18:30:37, "storage room 5", "N", "Y", "Y");

INSERT 
INTO Artwork (articleID, artist, yearMade, medium)
VALUES 
    (11111, "Emily Carr", 1931, "oil on canvas");

INSERT 
INTO Text (articleID, author, datePublished)
VALUES 
    (11112, "William Shakespeare", 1623);

INSERT 
INTO Photo (articleID, yearTaken, locationTaken)
VALUES
    (11113, 1941, "Hernandez, New Mexico, USA");

INSERT 
INTO Artifact (articleID, estimatedYear, regionOfOrigin, material)
VALUES 
    (11114, "1323 BCE (New Kingdom, 18th Dynasty)", "tomb of Tutankhamun in the Valley of the Kings, Luxor, Egypt", 
    "Gold with inlays of lapis lazuli, carnelian, quartz, obsidian, and other stones"),
    (11118, "700 AD (Late Classic period)", "Palenque, Mexico", "carved jadeite"),
    (11119, "1200 BC", "Nile River, Egypt", "flint"),
    (11120, "3000 BC", "Yangshao culture, China", "earthenware"),
    (11121, "2500 BCE", "Mesopotamia (present-day Iraq)", "copper");

INSERT 
INTO NaturalSpecimen (articleID, speciesName, timePeriod)
VALUES 
    (11115, "Balaenoptera musculus", "Holocene epoch (Present)"),
    (11116, "Mammuthus primigenius", "Pleistocene epoch"),
    (11122, "Pleuroceras solare", "lower Jurassic, upper Pliensbachian period"),
    (11123, "Tyrannosaurus rex", "Late Cretaceous Period"),
    (11124, "Burmaculex antiquus", "Cretaceous Period");

INSERT 
INTO Species (speciesName, nativeTo)
VALUES 
    ("Mammuthus primigenius", "all major oceans"),
    ("Balaenoptera musculus", "northern Eurasia and North America"),
    ("Pleuroceras solare", "Canada and Europe"),
    ("Tyrannosaurus rex", "North America"),
    ("Burmaculex antiquus", "Canada");

-- Contract

-- Owner

-- Writes

-- Loans

-- PertainsTo

-- Displays (Marcus fill in? depends on Exhibits)