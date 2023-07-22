CREATE TABLE Artifact (
    articleID INT(11) PRIMARY KEY,
    estimatedYear INT(10),
    madeBy VARCHAR(50),
    regionOfOrigin VARCHAR(50),
    material VARCHAR(50),
    FOREIGN KEY articleID REFERENCES Article,
);
