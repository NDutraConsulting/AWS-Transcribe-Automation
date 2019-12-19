CREATE TABLE sentences ( lecture_id VARCHAR(255), sentence_id VARCHAR(255), sentence TEXT,
starttime DECIMAL(19,7), endtime DECIMAL(19,7),  date DATETIME,
timestamp DATETIME, UNIQUE (lecture_id, sentence_id, starttime));
