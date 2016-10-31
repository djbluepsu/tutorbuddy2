CREATE TABLE users (
    id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(30) NOT NULL,
    password CHAR(32) NOT NULL,
    first_name VARCHAR(40) NOT NULL,
    last_name VARCHAR(40) NOT NULL,
    grade INT(8) NOT NULL,
    teacher INT(1) NOT NULL,
    num_slots INT(2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)
CREATE TABLE times (
	user_id INT(8) UNSIGNED NOT NULL,
	time VARCHAR(50) NOT NULL,
	CONSTRAINT user_time PRIMARY KEY (user_id, time)
)

CREATE TABLE tutored_hours (
	user_id INT(8) UNSIGNED NOT NULL,
	class VARCHAR(100) NOT NULL,
	time VARCHAR(30) NOT NULL,
	CONSTRAINT user_class PRIMARY KEY (user_id, class)
)

CREATE TABLE classes (
	user_id INT(8) UNSIGNED NOT NULL,
	class VARCHAR(100) NOT NULL,
	CONSTRAINT user_class PRIMARY KEY (user_id, class)
)
CREATE TABLE tutors (
	id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	tutor_id INT(8) NOT NULL,
	tutee_id INT(8) NOT NULL,
	time INT(8) NOT NULL,
	class VARCHAR(40) NOT NULL,
	archived BIT NOT NULL
)
CREATE TABLE handshakes (
	id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	relationship_id INT(8) NOT NULL,
	time INT(8) NOT NULL,
	accepted BIT NOT NULL,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
)

CREATE TABLE scheduled_times (
	relationship_id INT(8) NOT NULL,
	time VARCHAR(40) NOT NULL,
	CONSTRAINT rel_time PRIMARY KEY (relationship_id, time)
)
CREATE TABLE honorsocieties (
	id INT(8) NOT NULL,
	honorsociety VARCHAR(40) NOT NULL,
	minutes INT(8) NOT NULL,
	admin INT(1) DEFAULT 0,
	accepted BIT NOT NULL
	CONSTRAINT id_hs PRIMARY KEY (id, honorsociety)
)