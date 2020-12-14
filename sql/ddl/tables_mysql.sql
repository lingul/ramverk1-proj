--
-- Table Answer
--
DROP TABLE IF EXISTS Answer;
CREATE TABLE Answer (
    `id` INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `userId` INTEGER NOT NULL,
    `text` TEXT NOT NULL,
    `questionid` INTEGER,
    `accepted` INTEGER DEFAULT 0,
    `votes` INTEGER DEFAULT 0,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME,
    `deleted` DATETIME
);

--
-- Table Comment
--
DROP TABLE IF EXISTS Comment;
CREATE TABLE Comment (
    `id` INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `userId` INTEGER NOT NULL,
    `text` TEXT,
    `questionid` INTEGER,
    `answerid` INTEGER,
    `votes` INTEGER DEFAULT 0,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME,
    `deleted` DATETIME
);

--
-- Table Question
--
DROP TABLE IF EXISTS Question;
CREATE TABLE Question (
    `id` INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `userId` INTEGER NOT NULL,
    `title` TEXT,
    `text` TEXT,
    `votes` INTEGER DEFAULT 0,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME,
    `deleted` DATETIME
);

--
-- Table TagQuestion
--
DROP TABLE IF EXISTS TagQuestion;
CREATE TABLE TagQuestion (
    `id` INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `text` TEXT,
    `questionid` INTEGER
);

--
-- Table User
--
DROP TABLE IF EXISTS User;
CREATE TABLE User (
    `id` INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `acronym` TEXT NOT NULL,
    `password` TEXT,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `score` INTEGER DEFAULT NULL,
    `updated` DATETIME,
    `deleted` DATETIME,
    `active` DATETIME,
    `email` VARCHAR(100)
);

--
-- Table UserVotes
--
DROP TABLE IF EXISTS UserVotes;
CREATE TABLE UserVotes (
    `id` INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `userId` INTEGER,
    `votedId` INTEGER,
    `votedType` TEXT,
    `voted` TEXT
);
