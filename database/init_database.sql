-- drop tables
DROP TABLE pertainsto;
DROP TABLE writes;
DROP TABLE displays;
DROP TABLE sells;
DROP TABLE examines;
DROP TABLE contains;
DROP TABLE collection;
DROP TABLE attends;
DROP TABLE admits;
DROP TABLE ticket;
DROP TABLE visitor;
DROP TABLE ticketprice;
DROP TABLE frontdesk;
DROP TABLE archivist;
DROP TABLE activities;
DROP TABLE exhibit;
DROP TABLE curator;
DROP TABLE employee;
DROP TABLE artwork;
DROP TABLE text;
DROP TABLE photo;
DROP TABLE naturalspecimen;
DROP TABLE species;
DROP TABLE artifact;
DROP TABLE article;
DROP TABLE contract;
DROP TABLE owner;
DROP TABLE postalcode;

-- create tables
CREATE TABLE visitor (
    visitor_id INT PRIMARY KEY,
    name VARCHAR(50), 
    email VARCHAR(50)
);

CREATE TABLE ticketprice (
    ticket_type VARCHAR(50) PRIMARY KEY,
    price INT DEFAULT 25 NOT NULL
);

CREATE TABLE ticket (
    ticket_id INT,
    issue_date DATE,
    ticket_type VARCHAR(50) DEFAULT 'General Admission' NOT NULL,
    visitor_id INT NOT NULL,
    PRIMARY KEY(ticket_id),
    FOREIGN KEY(ticket_type) REFERENCES ticketprice,
    FOREIGN KEY(visitor_id) REFERENCES visitor
);

CREATE TABLE employee (
    sin INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE frontdesk (
    sin INT PRIMARY KEY,
    FOREIGN KEY (sin) REFERENCES employee(sin)
        ON DELETE CASCADE
);

CREATE TABLE archivist (
    sin INT PRIMARY KEY,
    FOREIGN KEY (sin) REFERENCES employee(sin)
        ON DELETE CASCADE
);

CREATE TABLE curator (
    sin INT PRIMARY KEY,
    FOREIGN KEY (sin) REFERENCES employee(sin)
        ON DELETE CASCADE
);

CREATE TABLE exhibit (
    exhibit_id INT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    start_date DATE,
    end_date DATE,
    sin INT NOT NULL,
    FOREIGN KEY(sin) REFERENCES curator(sin)
);

CREATE TABLE activities (
    exhibit_id INT,
    name VARCHAR(50),
    schedule VARCHAR(255),
    PRIMARY KEY(exhibit_id, name),
    FOREIGN KEY(exhibit_id) REFERENCES exhibit(exhibit_id)
        ON DELETE CASCADE
);

CREATE TABLE article (
    article_id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date_aquired TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    condition VARCHAR(50) NOT NULL,
    storage_location VARCHAR(50) NOT NULL,
    uv_protection CHAR(1) NOT NULL,
    temp_control CHAR(1) NOT NULL,
    humidity_control CHAR(1) NOT NULL
);

CREATE TABLE artwork (
    article_id INT PRIMARY KEY,
    artist VARCHAR(50),
    year_made INT,
    medium VARCHAR(50),
    FOREIGN KEY(article_id) REFERENCES article(article_id)
        ON DELETE CASCADE
);

CREATE TABLE text (
    article_id INT PRIMARY KEY,
    author VARCHAR(50),
    year_published INT,
    FOREIGN KEY(article_id) REFERENCES article(article_id)
        ON DELETE CASCADE
);

CREATE TABLE photo (
    article_id INT PRIMARY KEY,
    year_taken INT,
    location_taken VARCHAR(50),
    FOREIGN KEY(article_id) REFERENCES article(article_id)
        ON DELETE CASCADE
);

CREATE TABLE artifact (
    article_id INT PRIMARY KEY,
    estimated_year VARCHAR(50),
    region_of_origin VARCHAR(60),
    material VARCHAR(50),
    FOREIGN KEY(article_id) REFERENCES article(article_id)
        ON DELETE CASCADE
);

CREATE TABLE species (
    species_name VARCHAR(50) PRIMARY KEY,
    native_to VARCHAR(50) NOT NULL
);

CREATE TABLE naturalspecimen (
    article_id INT PRIMARY KEY,
    species_name VARCHAR(50),
    time_period VARCHAR(50),
    FOREIGN KEY(article_id) REFERENCES article(article_id)
        ON DELETE CASCADE,
    FOREIGN KEY(species_name) REFERENCES species(species_name)
);

CREATE TABLE contract (
    contract_id INT PRIMARY KEY,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    text VARCHAR(200) NOT NULL
);

CREATE TABLE postalcode (
    postal_ZIP_Code CHAR(7) PRIMARY KEY,
    city VARCHAR(50) NOT NULL
);

CREATE TABLE owner (
    owner_id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    building_num INT,
    street VARCHAR(50) NOT NULL,
    postal_ZIP_Code CHAR(7) NOT NULL,
    country VARCHAR(50) NOT NULL,
    phone_num VARCHAR(20) NOT NULL,
    FOREIGN KEY(postal_ZIP_Code) REFERENCES postalcode(postal_ZIP_Code)
);

CREATE TABLE admits (
    ticket_id INT,
    exhibit_id INT,
    PRIMARY KEY(ticket_id, exhibit_id),
    FOREIGN KEY(ticket_id) REFERENCES ticket(ticket_id),
    FOREIGN KEY(exhibit_id) REFERENCES exhibit(exhibit_id)
);

CREATE TABLE attends (
    visitor_id INT,
    name VARCHAR(50),
    exhibit_id INT,
    PRIMARY KEY(visitor_id, name, exhibit_id),
    FOREIGN KEY(visitor_id) REFERENCES visitor(visitor_id),
    FOREIGN KEY(exhibit_id, name) REFERENCES activities(exhibit_id, name)
);

CREATE TABLE collection (
    collection_id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    sin INT NOT NULL,
    FOREIGN KEY (sin) REFERENCES curator(sin)
);

CREATE TABLE contains (
    article_id INT,
    collection_id INT,
    PRIMARY KEY (article_id, collection_id),
    FOREIGN KEY(article_id) REFERENCES article(article_id),
    FOREIGN KEY(collection_id) REFERENCES collection(collection_id)
);

CREATE TABLE examines (
    article_id INT,
    sin INT,
    PRIMARY KEY (article_id, sin),
    FOREIGN KEY(article_id) REFERENCES article(article_id),
    FOREIGN KEY(sin) REFERENCES archivist(sin)
);

CREATE TABLE sells (
    ticket_id INT,
    sin INT,
    PRIMARY KEY (ticket_id, sin),
    FOREIGN KEY(ticket_id) REFERENCES ticket(ticket_id),
    FOREIGN KEY(sin) REFERENCES frontdesk(sin)
);

CREATE TABLE displays (
    exhibit_id INT,
    article_id INT,
    FOREIGN KEY(exhibit_id) REFERENCES exhibit(exhibit_id),
    FOREIGN KEY(article_id) REFERENCES article(article_id)
);

CREATE TABLE writes (
    owner_id INT,
    contract_id INT,
    FOREIGN KEY(owner_id) REFERENCES owner(owner_id),
    FOREIGN KEY(contract_id) REFERENCES contract(contract_id)
);

CREATE TABLE pertainsto (
    contract_id INT,
    article_id INT,
    FOREIGN KEY(contract_id) REFERENCES contract(contract_id),
    FOREIGN KEY(article_id) REFERENCES article(article_id)
);

-- populate tables
INSERT INTO visitor(visitor_id, name, email)
VALUES (1010, 'Bruce Wayne', 'batman@gmail.com');

INSERT INTO visitor(visitor_id, name, email)
VALUES (1011, 'Clark Kent', 'superman@gmail.com');

INSERT INTO visitor(visitor_id, name, email)
VALUES (1012, 'Lois Lane', 'lois@outlook.com');

INSERT INTO visitor(visitor_id, name, email)
VALUES (1013, 'Diana Prince', 'wonderwoman@gmail.com');

INSERT INTO visitor(visitor_id, name, email)
VALUES (1014, 'Barry Allen', 'flash@gmail.com');

INSERT INTO visitor(visitor_id, name, email)
VALUES (1015, 'Arthur Curry', 'aquaman@gmail.com');

INSERT INTO visitor(visitor_id, name, email)
VALUES (1016, 'Lex Luthor', 'lex@outlook.com');

INSERT INTO visitor(visitor_id, name, email)
VALUES (1017, 'Alfred Pennyworth', 'alfred@outlook.com');

INSERT INTO visitor(visitor_id, name, email)
VALUES (1018, 'Wally West', 'kidflash@gmail.com');

INSERT INTO visitor(visitor_id, name, email)
VALUES (1019, 'Dick Grayson', 'robin@gmail.com');


INSERT INTO ticketprice(ticket_type, price) VALUES ('General Admission', 25);
INSERT INTO ticketprice(ticket_type, price) VALUES ('Family', 57);
INSERT INTO ticketprice(ticket_type, price) VALUES ('Child', 15);
INSERT INTO ticketprice(ticket_type, price) VALUES ('Staff', 12);
INSERT INTO ticketprice(ticket_type, price) VALUES ('Senior', 17);


INSERT INTO ticket(ticket_id, issue_date, ticket_type, visitor_id)
VALUES (2001, TO_DATE('2023-05-31', 'YYYY-MM-DD'), 'General Admission', 1010);

INSERT INTO ticket(ticket_id, issue_date, ticket_type, visitor_id)
VALUES (2002, TO_DATE('2023-05-31', 'YYYY-MM-DD'), 'General Admission', 1011);

INSERT INTO ticket(ticket_id, issue_date, ticket_type, visitor_id)
VALUES (2003, TO_DATE('2023-05-31', 'YYYY-MM-DD'), 'General Admission', 1012);

INSERT INTO ticket(ticket_id, issue_date, ticket_type, visitor_id)
VALUES (2004, TO_DATE('2023-06-01', 'YYYY-MM-DD'), 'Staff', 1013);

INSERT INTO ticket(ticket_id, issue_date, ticket_type, visitor_id)
VALUES (2005, TO_DATE('2023-06-02', 'YYYY-MM-DD'), 'General Admission', 1014);

INSERT INTO ticket(ticket_id, issue_date, ticket_type, visitor_id)
VALUES (2006, TO_DATE('2023-06-02', 'YYYY-MM-DD'), 'General Admission', 1015);

INSERT INTO ticket(ticket_id, issue_date, ticket_type, visitor_id)
VALUES (2007, TO_DATE('2023-06-03', 'YYYY-MM-DD'), 'General Admission', 1016);

INSERT INTO ticket(ticket_id, issue_date, ticket_type, visitor_id)
VALUES (2008, TO_DATE('2023-06-04', 'YYYY-MM-DD'), 'Senior', 1017);

INSERT INTO ticket(ticket_id, issue_date, ticket_type, visitor_id)
VALUES (2009, TO_DATE('2023-06-05', 'YYYY-MM-DD'), 'Child', 1018);

INSERT INTO ticket(ticket_id, issue_date, ticket_type, visitor_id)
VALUES (2010, TO_DATE('2023-06-05', 'YYYY-MM-DD'), 'Child', 1019);


INSERT INTO employee (sin, name) VALUES (111111111, 'John Cena');
INSERT INTO employee (sin, name) VALUES (222222222, 'Roman Reigns');
INSERT INTO employee (sin, name) VALUES (333333333, 'Becky Lynch');
INSERT INTO employee (sin, name) VALUES (444444444, 'Seth Rollins');
INSERT INTO employee (sin, name) VALUES (555555555, 'Charlotte Flair');
INSERT INTO employee (sin, name) VALUES (666666666, 'Brock Lesnar');
INSERT INTO employee (sin, name) VALUES (777777777, 'Bayley');
INSERT INTO employee (sin, name) VALUES (888888888, 'AJ Styles');
INSERT INTO employee (sin, name) VALUES (999999999, 'Sasha Banks');
INSERT INTO employee (sin, name) VALUES (101010101, 'Drew McIntyre');
INSERT INTO employee (sin, name) VALUES (121212121, 'Asuka');
INSERT INTO employee (sin, name) VALUES (131313131, 'Randy Orton');
INSERT INTO employee (sin, name) VALUES (141414141, 'Alexa Bliss');
INSERT INTO employee (sin, name) VALUES (151515151, 'Braun Strowman');
INSERT INTO employee (sin, name) VALUES (161616161, 'Rhea Ripley');

INSERT INTO frontdesk (sin) VALUES (666666666);
INSERT INTO frontdesk (sin) VALUES (777777777);
INSERT INTO frontdesk (sin) VALUES (888888888);
INSERT INTO frontdesk (sin) VALUES (999999999);
INSERT INTO frontdesk (sin) VALUES (101010101);

INSERT INTO archivist (sin) VALUES (121212121);
INSERT INTO archivist (sin) VALUES (131313131);
INSERT INTO archivist (sin) VALUES (141414141);
INSERT INTO archivist (sin) VALUES (151515151);
INSERT INTO archivist (sin) VALUES (161616161);

INSERT INTO curator (sin) VALUES (111111111);
INSERT INTO curator (sin) VALUES (222222222);
INSERT INTO curator (sin) VALUES (333333333);
INSERT INTO curator (sin) VALUES (444444444);
INSERT INTO curator (sin) VALUES (555555555);


INSERT INTO exhibit(exhibit_id, name, start_date, end_date, sin) 
VALUES (1500, 'Ancient Egypt', TO_DATE('2021-02-27', 'YYYY-MM-DD'), TO_DATE('2024-06-28', 'YYYY-MM-DD'), 444444444);

INSERT INTO exhibit(exhibit_id, name, start_date, end_date, sin) 
VALUES (1501, 'The Invention of the Printing Press', TO_DATE('2022-11-27', 'YYYY-MM-DD'), TO_DATE('2023-06-20', 'YYYY-MM-DD'), 222222222);

INSERT INTO exhibit(exhibit_id, name, start_date, end_date, sin) 
VALUES (1502, 'Dinosaurs', TO_DATE('2022-12-26', 'YYYY-MM-DD'), TO_DATE('2023-08-14', 'YYYY-MM-DD'), 111111111);

INSERT INTO exhibit(exhibit_id, name, start_date, end_date, sin) 
VALUES (1503, 'History of Vancouver', TO_DATE('2023-02-03', 'YYYY-MM-DD'), TO_DATE('2023-10-05', 'YYYY-MM-DD'), 333333333);

INSERT INTO exhibit(exhibit_id, name, start_date, end_date, sin) 
VALUES (1504, 'The Ice Age', TO_DATE('2023-04-21', 'YYYY-MM-DD'), TO_DATE('2023-12-31', 'YYYY-MM-DD'), 111111111);

INSERT INTO exhibit(exhibit_id, name, start_date, end_date, sin) 
VALUES (1505, 'Famous Artwork', TO_DATE('2023-04-27', 'YYYY-MM-DD'), TO_DATE('2026-09-25', 'YYYY-MM-DD'), 555555555);

INSERT INTO exhibit(exhibit_id, name, start_date, end_date, sin) 
VALUES (1506, 'Ancient Maya', TO_DATE('2023-05-30', 'YYYY-MM-DD'), TO_DATE('2026-11-25', 'YYYY-MM-DD'), 555555555);

INSERT INTO exhibit(exhibit_id, name, start_date, end_date, sin) 
VALUES (1507, 'Sea Creatures', TO_DATE('2023-07-14', 'YYYY-MM-DD'), TO_DATE('2027-03-22', 'YYYY-MM-DD'), 333333333);


INSERT INTO activities(exhibit_id, name, schedule) 
VALUES (1500, 'Tour', 'Days: Monday, Wednesday, Friday; Times: 1030-1200, 1400-1530');

INSERT INTO activities(exhibit_id, name, schedule) 
VALUES (1502, 'Animated Video', 'Days: Monday, Tuesday, Wednesday, Thursday, Friday; Times: Hourly');

INSERT INTO activities(exhibit_id, name, schedule) 
VALUES (1503, 'Storytime', 'Days: Monday, Wednesday, Friday; Times: 0930-1030, 1230-1330, 1430-1530');

INSERT INTO activities(exhibit_id, name, schedule) 
VALUES (1505, 'Tour', 'Days: Tuesday, Thursday; Times: 0900-1030, 1230-1400, 1530-1700');

INSERT INTO activities(exhibit_id, name, schedule) 
VALUES (1506, 'Tour', 'Days: Monday, Wednesday, Friday; Times: 0900-1030, 1230-1400, 1530-1700');

INSERT INTO activities(exhibit_id, name, schedule) 
VALUES (1507, 'Puppet Show', 'Days: Tuesday, Thursday; Times: 0900-1030, 1230-1400, 1530-1700');


INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11111, 'Forest, British Columbia', TO_TIMESTAMP('2021-11-05 21:45:59', 'YYYY-MM-DD HH24:MI:SS'), 'Excellent', 'Storage room 1', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES (11112, 'William Shakespeares Comedies, Histories, and Tragedies', TO_TIMESTAMP('2022-01-12 13:45:31', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'Storage room 5', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11114, 'Tutankhamuns Mask', TO_TIMESTAMP('2022-07-16 03:15:50', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'on display', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11115, 'Blue whale skeleton', TO_TIMESTAMP('2022-10-25 09:20:18', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'on display', 'N', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11116, 'Woolly mammoth replica', TO_TIMESTAMP('2021-10-20 06:35:19', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'on display', 'N', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11117, 'IBM cheese slicer', TO_TIMESTAMP('2023-02-09 12:00:12', 'YYYY-MM-DD HH24:MI:SS'), 'Excellent', 'Storage room 1', 'N', 'N', 'N');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11118, 'Jade Death Mask of Kinich Janaab Pakal', TO_TIMESTAMP('2017-03-25 14:40:49', 'YYYY-MM-DD HH24:MI:SS'), 'Excellent', 'on display', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11119, 'Ancient Egyptian flint arrowhead', TO_TIMESTAMP('2023-11-08 18:45:05', 'YYYY-MM-DD HH24:MI:SS'), 'Fair', 'on display', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11120, 'Neolithic Painted Pottery', TO_TIMESTAMP('2018-07-06 08:05:56', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'Storage room 3', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11121, 'Ancient Sumerian chisel', TO_TIMESTAMP('2021-09-13 03:25:59', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'Storage room 3', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11122, 'Ammonite fossil', TO_TIMESTAMP('2019-07-20 12:30:15', 'YYYY-MM-DD HH24:MI:SS'), 'Excellent', 'on display', 'N', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11123, 'T. rex skeleton', TO_TIMESTAMP('2017-03-25 14:40:49', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'on display', 'N', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11124, 'Ancient Mosquito in Burmese amber', TO_TIMESTAMP('2011-04-26 18:30:37', 'YYYY-MM-DD HH24:MI:SS'), 'Excellent', 'on display', 'N', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11125, 'Mona Lisa', TO_TIMESTAMP('2021-09-13 03:25:59', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'on display', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11126, 'Starry Night', TO_TIMESTAMP('2020-07-02 12:30:15', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'on display', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11127, 'The Scream', TO_TIMESTAMP('2023-03-12 14:40:49', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'on display', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11128, 'The Thinker replica', TO_TIMESTAMP('2015-03-15 18:30:37', 'YYYY-MM-DD HH24:MI:SS'), 'Excellent', 'Storage room 5', 'N', 'N', 'N');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11129, 'The Vancouver Court House under construction', TO_TIMESTAMP('2018-08-17 14:12:32', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'on display', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11130, 'The terminus of the Canadian Pacific Railway', TO_TIMESTAMP('2018-08-17 14:12:32', 'YYYY-MM-DD HH24:MI:SS'), 'on display', 'Storage room 1', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11131, 'A picnic in newly opened Stanley Park', TO_TIMESTAMP('2018-08-17 14:12:32', 'YYYY-MM-DD HH24:MI:SS'), 'Fair', 'Fair', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11132, 'Rebuilding Cordova Street after the Great Vancouver Fire', TO_TIMESTAMP('2018-08-17 14:12:32', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'on display', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11133, 'UBCs Main Library - now Irving K. Barber Learning Centre - under construction', TO_TIMESTAMP('2018-08-17 14:12:32', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'Storage room 1', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11134, 'Papyrus of Ani - Book of the Dead', TO_TIMESTAMP('2022-07-16 03:15:50', 'YYYY-MM-DD HH24:MI:SS'), 'Fair', 'on display', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11135, 'Original Manuscript of Alice in Wonderland', TO_TIMESTAMP('2013-08-07 18:55:41', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'Storage room 2', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11136, 'Gutenberg Bible - The Earliest Printed Book', TO_TIMESTAMP('2014-09-28 01:25:26', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'on display', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11137, 'Magna Carta Libertatum - Latin for “Great Charter of Freedoms”', TO_TIMESTAMP('2019-05-22 11:15:09', 'YYYY-MM-DD HH24:MI:SS'), 'Fair', 'Storage room 5', 'Y', 'Y', 'Y');

INSERT INTO article(article_id, name, date_aquired, condition, storage_location, uv_protection, temp_control, humidity_control)
VALUES(11138, 'Common Sense', TO_TIMESTAMP('2016-03-09 10:05:08', 'YYYY-MM-DD HH24:MI:SS'), 'Good', 'Storage room 2', 'Y', 'Y', 'Y');


INSERT INTO artwork(article_id, artist, year_made, medium)
VALUES (11111, 'Emily Carr', 1931, 'Oil on canvas');

INSERT INTO artwork(article_id, artist, year_made, medium)
VALUES (11125, 'Leonardo da Vinci', 1503, 'Oil on poplar panel');

INSERT INTO artwork(article_id, artist, year_made, medium)
VALUES (11126, 'Vincent van Gogh', 1889, 'Oil on canvas');

INSERT INTO artwork(article_id, artist, year_made, medium)
VALUES (11127, 'Edvard Munch', 1893, 'Tempera and pastels on cardboard');

INSERT INTO artwork(article_id, artist, year_made, medium)
VALUES (11128, 'Auguste Rodin', 1904, 'Bronze cast');


INSERT INTO text(article_id, author, year_published) VALUES (11112, 'William Shakespeare', 1623);
INSERT INTO text(article_id, author, year_published) VALUES (11135, 'Lewis Carroll', 1864);
INSERT INTO text(article_id, author, year_published) VALUES (11136, NULL, 1450);
INSERT INTO text(article_id, author, year_published) VALUES (11137, 'Archbishop of Canterbury', 1215);
INSERT INTO text(article_id, author, year_published) VALUES (11138, 'Thomas Paine', 1766);

INSERT INTO photo(article_id, year_taken, location_taken) VALUES (11129, 1907, 'Vancouver, BC');
INSERT INTO photo(article_id, year_taken, location_taken) VALUES (11130, 1889, 'Vancouver, BC');
INSERT INTO photo(article_id, year_taken, location_taken) VALUES (11131, 1888, 'Vancouver, BC');
INSERT INTO photo(article_id, year_taken, location_taken) VALUES (11132, 1886, 'Vancouver, BC');
INSERT INTO photo(article_id, year_taken, location_taken) VALUES (11133, 1925, 'Vancouver, BC');


INSERT INTO artifact(article_id, estimated_year, region_of_origin, material)
VALUES (11114, '1323 BCE', 'Tomb of Tutankhamun, Valley of the Kings, Luxor, Egypt', 'Gold inlay of colored glass and gemstones');

INSERT INTO artifact(article_id, estimated_year, region_of_origin, material)
VALUES (11118, '683 CE', 'Palenque, Mexico', 'Carved jadeite');

INSERT INTO artifact(article_id, estimated_year, region_of_origin, material)
VALUES (11119, '1200 BCE', 'Nile River, Egypt', 'flint');

INSERT INTO artifact(article_id, estimated_year, region_of_origin, material)
VALUES (11120, '3000 BCE', 'China, probably Gansu Province', 'earthenware');

INSERT INTO artifact(article_id, estimated_year, region_of_origin, material)
VALUES (11121, '2500 BCE', 'Mesopotamia (present-day Iraq)', 'copper');

INSERT INTO artifact(article_id, estimated_year, region_of_origin, material)
VALUES (11134, '1250 BCE', 'Tomb of Ani, Egypt', 'Papyrus');

INSERT INTO artifact(article_id, estimated_year, region_of_origin, material)
VALUES (11136, '1450', 'Germany', 'Paper and vellum');

INSERT INTO artifact(article_id, estimated_year, region_of_origin, material)
VALUES (11137, '1215', 'England', 'Parchment made from sheepskin');


INSERT INTO species(species_name, native_to) VALUES ('Balaenoptera musculus', 'all major oceans');
INSERT INTO species(species_name, native_to) VALUES ('Mammuthus primigenius', 'Northern Eurasia and North America');
INSERT INTO species(species_name, native_to) VALUES ('Pleuroceras solare', 'Canada and Europe');
INSERT INTO species(species_name, native_to) VALUES ('Tyrannosaurus rex', 'North America');
INSERT INTO species(species_name, native_to) VALUES ('Burmaculex antiquus', 'Canada');


INSERT INTO naturalspecimen(article_id, species_name, time_period)
VALUES (11115, 'Balaenoptera musculus', 'Holocene epoch (Present)');

INSERT INTO naturalspecimen(article_id, species_name, time_period)
VALUES (11116, 'Mammuthus primigenius', 'Pleistocene epoch');

INSERT INTO naturalspecimen(article_id, species_name, time_period)
VALUES (11122, 'Pleuroceras solare', 'lower Jurassic, upper Pliensbachian period');

INSERT INTO naturalspecimen(article_id, species_name, time_period)
VALUES (11123, 'Tyrannosaurus rex', 'Late Cretaceous period');

INSERT INTO naturalspecimen(article_id, species_name, time_period)
VALUES (11124, 'Burmaculex antiquus', 'Cretaceous period');


INSERT INTO contract(contract_id, start_date, end_date, text) 
VALUES (1000, TO_DATE('2021-10-25', 'YYYY-MM-DD'), TO_DATE('2024-10-25', 'YYYY-MM-DD'), 'The Vancouver Art Gallery will loan Forest, British Columbia to the museum from 2021-10-25 to 2024-10-25.');

INSERT INTO contract(contract_id, start_date, end_date, text) 
VALUES (1001, TO_DATE('2022-01-03', 'YYYY-MM-DD'), TO_DATE('2025-01-23', 'YYYY-MM-DD'), 'The University of British Columbia will loan William Shakespeares Comedies, Histories, and Tragedies to the museum from 2022-01-03 to 2025-01-23.');

INSERT INTO contract(contract_id, start_date, end_date, text) 
VALUES (1002, TO_DATE('2022-07-02', 'YYYY-MM-DD'), TO_DATE('2025-07-02', 'YYYY-MM-DD'), 'The Egyptian Museum will loan Tutankhamuns Mask and Papyrus of Ani - Book of the Dead to the museum from 2022-07-02 to 2025-07-02.');

INSERT INTO contract(contract_id, start_date, end_date, text) 
VALUES (1003, TO_DATE('2021-10-15', 'YYYY-MM-DD'), TO_DATE('2026-10-15', 'YYYY-MM-DD'), 'The Royal BC Museum will loan Woolly mammoth replica to the museum from 2021-10-15 to 2026-10-15.');

INSERT INTO contract(contract_id, start_date, end_date, text) 
VALUES (1004, TO_DATE('2023-02-04', 'YYYY-MM-DD'), TO_DATE('2026-02-04', 'YYYY-MM-DD'), 'The Burnaby Village Museum will loan IBM cheese slicer to the museum from 2023-02-04 to 2026-02-04.');

INSERT INTO contract(contract_id, start_date, end_date, text) 
VALUES (1005, TO_DATE('2023-10-28', 'YYYY-MM-DD'), TO_DATE('2024-10-28', 'YYYY-MM-DD'), 'Richie Rich will loan Ancient Egyptian flint arrowhead to the museum from 2023-10-28 to 2024-10-28.');

INSERT INTO contract(contract_id, start_date, end_date, text) 
VALUES (1006, TO_DATE('2021-09-07', 'YYYY-MM-DD'), TO_DATE('2025-09-07', 'YYYY-MM-DD'), 'The Louvre Museum will loan Mona Lisa to the museum from 2021-09-07 to 2025-09-07.');

INSERT INTO contract(contract_id, start_date, end_date, text) 
VALUES (1007, TO_DATE('2020-06-20', 'YYYY-MM-DD'), TO_DATE('2024-06-20', 'YYYY-MM-DD'), 'The Museum of Modern Art will loan Starry Night replica to the museum from 2020-06-20 to 2024-06-20.');

INSERT INTO contract(contract_id, start_date, end_date, text) 
VALUES (1008, TO_DATE('2023-03-05', 'YYYY-MM-DD'), TO_DATE('2027-03-15', 'YYYY-MM-DD'), 'The National Museum of Art will loan The Scream replica to the museum from 2023-03-05 to 2027-03-15.');

INSERT INTO contract(contract_id, start_date, end_date, text) 
VALUES (1009, TO_DATE('2022-10-17', 'YYYY-MM-DD'), TO_DATE('2025-10-17', 'YYYY-MM-DD'), 'The University of British Columbia will loan Blue whale skeleton to the museum from 2022-10-17 to 2025-10-17.');


INSERT INTO postalcode(postal_ZIP_Code, city) VALUES ('V6Z 2H7', 'Vancouver');
INSERT INTO postalcode(postal_ZIP_Code, city) VALUES ('V6T 1Z2', 'Vancouver');
INSERT INTO postalcode(postal_ZIP_Code, city) VALUES ('4272083', 'Cairo');
INSERT INTO postalcode(postal_ZIP_Code, city) VALUES ('V8W 9W2', 'Victoria');
INSERT INTO postalcode(postal_ZIP_Code, city) VALUES ('V5G 3T6', 'Burnaby');
INSERT INTO postalcode(postal_ZIP_Code, city) VALUES ('V6K 1A7', 'Vancouver');
INSERT INTO postalcode(postal_ZIP_Code, city) VALUES ('75001', 'Paris');
INSERT INTO postalcode(postal_ZIP_Code, city) VALUES ('10019', 'New York City');


INSERT INTO owner(owner_id, name, building_num, street, postal_ZIP_Code, country, phone_num) 
VALUES (1111, 'Vancouver Art Gallery', 750, 'Hornby St', 'V6Z 2H7', 'Canada', '(604) 662-4700');

INSERT INTO owner(owner_id, name, building_num, street, postal_ZIP_Code, country, phone_num) 
VALUES (1112, 'University of British Columbia: Library Special Collections Division', 1956, 'Main Mall', 'V6T 1Z2', 'Canada', '(604) 822-2521');

INSERT INTO owner(owner_id, name, building_num, street, postal_ZIP_Code, country, phone_num) 
VALUES (1113, 'Egyptian Museum', NULL, 'El-Tahrir Square', '4272083', 'Egypt', '+20 2 25796948');

INSERT INTO owner(owner_id, name, building_num, street, postal_ZIP_Code, country, phone_num) 
VALUES (1114, 'Royal BC Museum', 675, 'Belleville St', 'V8W 9W2', 'Canada', '(250) 356-7226');

INSERT INTO owner(owner_id, name, building_num, street, postal_ZIP_Code, country, phone_num) 
VALUES (1115, 'Burnaby Village Museum', 6501, 'Deer Lake Ave', 'V5G 3T6', 'Canada', '(604) 297-4565');

INSERT INTO owner(owner_id, name, building_num, street, postal_ZIP_Code, country, phone_num) 
VALUES (1116, 'Richie Rich', 3085, 'Point Grey Road', 'V6K 1A7', 'Canada', '(604) 987-6543');

INSERT INTO owner(owner_id, name, building_num, street, postal_ZIP_Code, country, phone_num) 
VALUES (1117, 'Louvre Museum', NULL, 'Rue de Rivoli', '75001', 'France', '+33 1 40 20 53 17');

INSERT INTO owner(owner_id, name, building_num, street, postal_ZIP_Code, country, phone_num) 
VALUES (1118, 'The Museum of Modern Art', 11, 'W 53rd St', '10019', 'United States', '(212) 708-9400');

INSERT INTO owner(owner_id, name, building_num, street, postal_ZIP_Code, country, phone_num) 
VALUES (1119, 'The National Museum of Art', 675, 'Belleville St', 'V8W 9W2', 'Canada', '(250) 356-7226');

INSERT INTO admits(ticket_id, exhibit_id) VALUES (2001, 1502);
INSERT INTO admits(ticket_id, exhibit_id) VALUES (2002, 1504);
INSERT INTO admits(ticket_id, exhibit_id) VALUES (2003, 1500);
INSERT INTO admits(ticket_id, exhibit_id) VALUES (2004, 1500);
INSERT INTO admits(ticket_id, exhibit_id) VALUES (2005, 1500);
INSERT INTO admits(ticket_id, exhibit_id) VALUES (2006, 1502);
INSERT INTO admits(ticket_id, exhibit_id) VALUES (2007, 1500);
INSERT INTO admits(ticket_id, exhibit_id) VALUES (2008, 1500);
INSERT INTO admits(ticket_id, exhibit_id) VALUES (2009, 1507);
INSERT INTO admits(ticket_id, exhibit_id) VALUES (2010, 1507);

INSERT INTO attends(visitor_id, exhibit_id, name) VALUES (1012, 1500, 'Tour');
INSERT INTO attends(visitor_id, exhibit_id, name) VALUES (1013, 1500, 'Tour');
INSERT INTO attends(visitor_id, exhibit_id, name) VALUES (1014, 1500, 'Tour');
INSERT INTO attends(visitor_id, exhibit_id, name) VALUES (1010, 1502, 'Animated Video');
INSERT INTO attends(visitor_id, exhibit_id, name) VALUES (1015, 1502, 'Animated Video');
INSERT INTO attends(visitor_id, exhibit_id, name) VALUES (1018, 1507, 'Puppet Show');
INSERT INTO attends(visitor_id, exhibit_id, name) VALUES (1019, 1507, 'Puppet Show');

INSERT INTO collection (collection_id, name, sin) VALUES (1, 'Ancient Artifacts', 111111111);
INSERT INTO collection (collection_id, name, sin) VALUES (2, 'Natural History', 111111111);
INSERT INTO collection (collection_id, name, sin) VALUES (3, 'Modern Art Gallery', 222222222);
INSERT INTO collection (collection_id, name, sin) VALUES (4, 'Historical Documents', 222222222);
INSERT INTO collection (collection_id, name, sin) VALUES (5, 'Sculpture and Statue', 333333333);

INSERT INTO contains (article_id, collection_id) VALUES (11116, 1);
INSERT INTO contains (article_id, collection_id) VALUES (11115, 2);
INSERT INTO contains (article_id, collection_id) VALUES (11112, 3);
INSERT INTO contains (article_id, collection_id) VALUES (11111, 3);
INSERT INTO contains (article_id, collection_id) VALUES (11119, 3);

INSERT INTO examines (article_id, sin) VALUES (11121, 121212121);
INSERT INTO examines (article_id, sin) VALUES (11125, 121212121);
INSERT INTO examines (article_id, sin) VALUES (11122, 151515151);
INSERT INTO examines (article_id, sin) VALUES (11131, 151515151);
INSERT INTO examines (article_id, sin) VALUES (11129, 161616161);

INSERT INTO sells (ticket_id, sin) VALUES (2003, 666666666);
INSERT INTO sells (ticket_id, sin) VALUES (2004, 777777777);
INSERT INTO sells (ticket_id, sin) VALUES (2005, 888888888);
INSERT INTO sells (ticket_id, sin) VALUES (2006, 101010101);
INSERT INTO sells (ticket_id, sin) VALUES (2007, 101010101);

INSERT INTO writes(owner_id, contract_id) VALUES (1111, 1000);
INSERT INTO writes(owner_id, contract_id) VALUES (1112, 1001);
INSERT INTO writes(owner_id, contract_id) VALUES (1113, 1002);
INSERT INTO writes(owner_id, contract_id) VALUES (1114, 1003);
INSERT INTO writes(owner_id, contract_id) VALUES (1115, 1004);
INSERT INTO writes(owner_id, contract_id) VALUES (1116, 1005);
INSERT INTO writes(owner_id, contract_id) VALUES (1117, 1006);
INSERT INTO writes(owner_id, contract_id) VALUES (1118, 1007);
INSERT INTO writes(owner_id, contract_id) VALUES (1119, 1008);
INSERT INTO writes(owner_id, contract_id) VALUES (1111, 1009);

INSERT INTO pertainsto(contract_id, article_id) VALUES (1000, 11111);
INSERT INTO pertainsto(contract_id, article_id) VALUES (1001, 11112);
INSERT INTO pertainsto(contract_id, article_id) VALUES (1002, 11114);
INSERT INTO pertainsto(contract_id, article_id) VALUES (1002, 11134);
INSERT INTO pertainsto(contract_id, article_id) VALUES (1003, 11116);
INSERT INTO pertainsto(contract_id, article_id) VALUES (1004, 11117);
INSERT INTO pertainsto(contract_id, article_id) VALUES (1005, 11119);
INSERT INTO pertainsto(contract_id, article_id) VALUES (1006, 11125);
INSERT INTO pertainsto(contract_id, article_id) VALUES (1007, 11126);
INSERT INTO pertainsto(contract_id, article_id) VALUES (1008, 11127);
INSERT INTO pertainsto(contract_id, article_id) VALUES (1009, 11115);

INSERT INTO displays(exhibit_id, article_id) VALUES (1500, 11119);
INSERT INTO displays(exhibit_id, article_id) VALUES (1500, 11134);
INSERT INTO displays(exhibit_id, article_id) VALUES (1500, 11114);
INSERT INTO displays(exhibit_id, article_id) VALUES (1500, 11134);
INSERT INTO displays(exhibit_id, article_id) VALUES (1502, 11123);
INSERT INTO displays(exhibit_id, article_id) VALUES (1502, 11124);
INSERT INTO displays(exhibit_id, article_id) VALUES (1507, 11115);
INSERT INTO displays(exhibit_id, article_id) VALUES (1507, 11122);
INSERT INTO displays(exhibit_id, article_id) VALUES (1503, 11129);
INSERT INTO displays(exhibit_id, article_id) VALUES (1503, 11130);
INSERT INTO displays(exhibit_id, article_id) VALUES (1503, 11132);
INSERT INTO displays(exhibit_id, article_id) VALUES (1505, 11125);
INSERT INTO displays(exhibit_id, article_id) VALUES (1505, 11126);
INSERT INTO displays(exhibit_id, article_id) VALUES (1505, 11127);
INSERT INTO displays(exhibit_id, article_id) VALUES (1501, 11136);
INSERT INTO displays(exhibit_id, article_id) VALUES (1504, 11116);
INSERT INTO displays(exhibit_id, article_id) VALUES (1506, 11118);