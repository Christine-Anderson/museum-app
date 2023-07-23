CREATE TABLE Writes(
    ownerID INT(11),
    contractID INT(11),
    FOREIGN KEY(ownerID) REFERENCES(Owner),
    FOREIGN KEY(contractID) REFERENCES(Contract)
);