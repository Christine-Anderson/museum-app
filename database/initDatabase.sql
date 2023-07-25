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
    yearPublished INT,
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
    buildingNum INT,
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
    articleID INT,
    FOREIGN KEY(ownerID) REFERENCES(Owner),
    FOREIGN KEY(articleID) REFERENCES(Article)
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

-- if you want to put any items I've added on display in the exhibit, please change storageLocation to "onDisplay"

INSERT
INTO Article(articleID, name, dateAquired, condition, storageLocation, UVProtection, tempControl, humidityControl)
VALUES
    (11111, "Forest, British Columbia", 2021-11-05 21:45:59, "Storage room 1", "Y", "Y", "Y"),
    (11112, "William Shakespeare's Comedies, Histories, & Tragedies", 2022-01-12 13:45:31, "Storage room 5", "Y", "Y", "Y"),
    (11114, "Tutankhamun's Mask", 2022-07-16 03:15:50, "Storage room 3", "Y", "Y", "Y"),
    (11115, "Blue whale skeleton", 2022-10-25 09:20:18, "Storage room 3", "N", "Y", "Y"),
    (11116, "Woolly mammoth replica", 2021-10-20 06:35:19, "Storage room 1", "N", "Y", "Y"),
    (11117, "IBM cheese slicer", 2023-02-09 12:00:12, "Storage room 1", "N", "N", "N"),
    (11118, "Jade Death Mask of Kinich Janaab Pakal", 2017-03-25 14:40:49, "Storage room 3", "Y", "Y", "Y"), 
    (11119, "Ancient Egyptian flint arrowhead", 2023-11-08 18:45:05, "Storage room 2", "Y", "Y", "Y"),
    (11120, "Neolithic Painted Pottery", 2018-07-06 08:05:56, "Storage room 3", "Y", "Y", "Y"),
    (11121, "Ancient Sumerian chisel", 2021-09-13 03:25:59, "Storage room 3", "Y", "Y", "Y"),
    (11122, "Ammonite fossil", 2019-07-20 12:30:15, "Storage room 1", "N", "Y", "Y"),
    (11123, "T. rex skeleton", 2017-03-25 14:40:49, "Storage room 5", "N", "Y", "Y"),
    (11124, "Ancient Mosquito in Burmese amber", 2011-04-26 18:30:37, "Storage room 5", "N", "Y", "Y"),
    (11125, "Mona Lisa", 2021-09-13 03:25:59, "Storage room 1", "Y", "Y", "Y"),
    (11126, "Starry Night", 2020-07-02 12:30:15, "Storage room 1", "Y", "Y", "Y"),
    (11127, "The Scream", 2023-03-12 14:40:49, "Storage room 2", "Y", "Y", "Y"),
    (11128, "The Thinker replica", 2015-03-15 18:30:37, "Storage room 5", "N", "N", "N"), 
    (11129, "The Vancouver Court House under construction", 2018-08-17 14:12:32, "Storage room 1", "Y", "Y", "Y"),
    (11130, "The terminus of the Canadian Pacific Railway", 2018-08-17 14:12:32, "Storage room 1", "Y", "Y", "Y"),
    (11131, "A picnic in newly opened Stanley Park", 2018-08-17 14:12:32, "Storage room 1", "Y", "Y", "Y"),
    (11132, "Rebuilding Cordova Street after the Great Vancouver Fire", 2018-08-17 14:12:32, "Storage room 1", "Y", "Y", "Y"),
    (11133, "UBC's Main Library - now Irving K. Barber Learning Centre - under construction", 2018-08-17 14:12:32, "Storage room 1", "Y", "Y", "Y"),
    (11134, "Papyrus of Ani - Book of the Dead", 2022-07-16 03:15:50, "Storage room 5", "Y", "Y", "Y"),
    (11135, "Original Manuscript of Alice in Wonderland", 2013-08-07 18:55:41, "Storage room 2", "Y", "Y", "Y"),
    (11136, "Gutenberg Bible - The Earliest Printed Book", 2014-09-28 01:25:26, "Storage room 2", "Y", "Y", "Y"),
    (11137, "Magna Carta Libertatum - Latin for “Great Charter of Freedoms”", 2019-05-22 11:15:09, "Storage room 5", "Y", "Y", "Y"),
    (11138, "Common Sense", 2016-03-09 10:05:08, "Storage room 2", "Y", "Y", "Y");

INSERT 
INTO Artwork(articleID, artist, yearMade, medium)
VALUES 
    (11111, "Emily Carr", 1931, "Oil on canvas"),
    (11125, "Leonardo da Vinci", 1503, "Oil on poplar panel"), 
    (11111, "Vincent van Gogh", 1889, "Oil on canvas"), 
    (11111, "Edvard Munch", 1893, "Tempera and pastels on cardboard"), 
    (11111, "Auguste Rodin", 1904, "Bronze cast");

INSERT 
INTO Text(articleID, author, yearPublished)
VALUES 
    (11112, "William Shakespeare", 1623),
    (11135, "Lewis Carroll (Charles Lutwidge Dodgson)", 1864),
    (11136, NULL, 1450),
    (11137, "Archbishop of Canterbury", 1215),
    (11138, "Thomas Paine", 1766);

INSERT 
INTO Photo(articleID, yearTaken, locationTaken)
VALUES
    (11129, 1907, "Vancouver, BC"),
    (11130, 1889, "Vancouver, BC"),
    (11131, 1888, "Vancouver, BC"),
    (11132, 1886, "Vancouver, BC"),
    (11133, 1925, "Vancouver, BC");

INSERT 
INTO Artifact(articleID, estimatedYear, regionOfOrigin, material)
VALUES 
    (11114, "1323 BCE", "Tomb of Tutankhamun, Valley of the Kings, Luxor, Egypt", "Gold inlay of colored glass and gemstones"),
    (11118, "683 CE", "Palenque, Mexico", "Carved jadeite"),
    (11119, "1200 BCE", "Nile River, Egypt", "flint"),
    (11120, "3000 BCE", "China, probably Gansu Province", "earthenware"),
    (11121, "2500 BCE", "Mesopotamia (present-day Iraq)", "copper"),
    (11134, "1250 BCE", "Tomb of Ani, Egypt", "Papyrus"),
    (11136, "1450", "Germany", "Paper and vellum"),
    (11137, "1215", "England", "Parchment made from sheepskin");

INSERT 
INTO NaturalSpecimen(articleID, speciesName, timePeriod)
VALUES 
    (11115, "Balaenoptera musculus", "Holocene epoch (Present)"),
    (11116, "Mammuthus primigenius", "Pleistocene epoch"),
    (11122, "Pleuroceras solare", "lower Jurassic, upper Pliensbachian period"),
    (11123, "Tyrannosaurus rex", "Late Cretaceous period"),
    (11124, "Burmaculex antiquus", "Cretaceous period");

INSERT 
INTO Species(speciesName, nativeTo)
VALUES 
    ("Mammuthus primigenius", "all major oceans"),
    ("Balaenoptera musculus", "Northern Eurasia and North America"),
    ("Pleuroceras solare", "Canada and Europe"),
    ("Tyrannosaurus rex", "North America"),
    ("Burmaculex antiquus", "Canada");

INSERT
INTO Owner(ownerID, name, buildingNum, street, postalOrZIPCode, country, phoneNum)
VALUES
    (1111, "Vancouver Art Gallery", 750, "Hornby St", "V6Z 2H7", "Canada", "(604) 662-4700"),
    (1112, "University of British Columbia: Library Special Collections Division", 1956, "Main Mall", "V6T 1Z2", "Canada", "(604) 822-2521"),
    (1113, "Egyptian Museum", NULL, "El-Tahrir Square", "4272083", "Egypt", "+20 2 25796948"),
    (1114, "Royal BC Museum", 675, "Belleville St", "V8W 9W2", "Canada", "(250) 356-7226"),
    (1115, "Burnaby Village Museum", 6501, "Deer Lake Ave", "V5G 3T6", "Canada", "(604) 297-4565"),
    (1116, "Richie Rich", 3085, "Point Grey Road", "V6K 1A7", "Canada", "(604) 987-6543"),
    (1117, "Louvre Museum", NULL, "Rue de Rivoli", "75001", "France", "+33 1 40 20 53 17"),
    (1118, "The Museum of Modern Art", 11, "W 53rd St", "10019", "United States", "+1 212-708-9400"),
    (1119, "The National Museum of Art", 675, "Belleville St", "V8W 9W2", "Canada", "(250) 356-7226");

INSERT
INTO PostalCode(postalOrZIPCode, city)
VALUES
    ("V6Z 2H7", "Vancouver"),
    ("V6T 1Z2", "Vancouver"),
    ("4272083", "Cario"),
    ("V8W 9W2", "Victoria"),
    ("V5G 3T6", "Burnaby"),
    ("V6K 1A7", "Vancouver"),
    ("75001", "Paris"),
    ("10019", "New York City"),
    ("V8W 9W2", "Victoria");

INSERT
INTO Contract(contractID, startDate, endDate, text)
VALUES
    (1000, 2021-10-25, 2024-10-25, "The Vancouver Art Gallery will loan Forest, British Columbia to the museum from 2021-10-25 to 2024-10-25."),
    (1001, 2022-01-03, 2025-01-23, "The University of British Columbia will loan William Shakespeare's Comedies, Histories, & Tragedies to the 
    museum from 2022-01-03 to 2025-01-23."),
    (1002, 2022-07-02, 2025-07-02, "The Egyptian Museum will loan Tutankhamun's Mask and Papyrus of Ani - Book of the Dead to the museum from 
    2022-07-02 to 2025-07-02."),
    (1003, 2021-10-15, 2026-10-15, "The Royal BC Museum will loan Woolly mammoth replica to the museum from 2021-10-15 to 2026-10-15."),
    (1004, 2023-02-04, 2026-02-04, "The Burnaby Village Museum will loan IBM cheese slicer to the museum from 2023-02-04 to 2026-02-04.")
    (1005, 2023-10-28, 2024-10-28, "Richie Rich will loan Ancient Egyptian flint arrowhead to the museum from 2023-10-28 to 2024-10-28."),
    (1006, 2021-09-07, 2025-09-07, "The Louvre Museum will loan Mona Lisa to the museum from 2021-09-07 to 2025-09-07."),
    (1007, 2020-06-20, 2024-06-20, "The Museum of Modern Art will loan Starry Night replica to the museum from 2020-06-20 to 2024-06-20."),
    (1008, 2023-03-05, 2027-03-15, "The National Museum of Art will loan The Scream replica to the museum from 2023-03-05 to 2027-03-15."),
    (1009, 2022-10-17, 2025-10-17, "The University of British Columbia will loan Blue whale skeleton to the museum from 2022-10-17 to 2025-10-17");

INSERT
INTO Writes(ownerID, contractID)
VALUES
    (1111, 1000),
    (1112, 1001),
    (1113, 1002),
    (1114, 1003),
    (1115, 1004),
    (1116, 1005),
    (1117, 1006),
    (1118, 1007),
    (1119, 1008),
    (1111, 1009);

INSERT
INTO Loans(ownerID, articleID)
VALUES
    (1111, 11111),
    (1112, 11112),
    (1112, 11115),
    (1113, 11114),
    (1113, 11134),
    (1114, 11116),
    (1115, 11117),
    (1116, 11119),
    (1117, 11125),
    (1118, 11126),
    (1119, 11127);

INSERT
INTO PertainsTo(contractID, articleID)
VALUES
    (1000, 11111),
    (1001, 11112),
    (1002, 11114),
    (1002, 11134),
    (1003, 11116),
    (1004, 11117)
    (1005, 11119),
    (1006, 11125),
    (1007, 11126),
    (1008, 11127),
    (1009, 11115);

-- Displays (depends on Exhibits TODO)