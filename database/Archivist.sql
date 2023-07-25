CREATE TABLE Archivist(
    sin INT(11) PRIMARY KEY,
    name VARCHAR(255),
    FOREIGN KEY(sin) REFERENCES(Employee)
        ON DELETE CASCADE
);