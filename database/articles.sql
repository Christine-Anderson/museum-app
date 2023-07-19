CREATE TABLE `articles` (
    `aid` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `condition` varchar(50) NOT NULL,
    `storage_location` varchar(50) NOT NULL,
    `storage_condition` varchar(50) NOT NULL,
    `date_aquired` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `native_to` varchar(50),
    `species` varchar(50),
    `material` varchar(50),
    `region_of_origin` varchar(50),
    `made_by` varchar(50),
    `estimated_date` timestamp DEFAULT CURRENT_TIMESTAMP,
    `location_taken` varchar(50),
    `date_taken` timestamp DEFAULT CURRENT_TIMESTAMP,
    `location_taken` varchar(50),
    `date_published` timestamp DEFAULT CURRENT_TIMESTAMP,
    `author` varchar(50),
    `medium` varchar(50),
    `artist` varchar(50),
    `date_made` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`aid`)
)