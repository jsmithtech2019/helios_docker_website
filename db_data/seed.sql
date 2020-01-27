# Initialize tables
CREATE TABLE CUSTOMERS (customer_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, name TEXT, phone_number TEXT, license_plate TEXT NOT NULL, vehicle_type TEXT, vehicle_year INT, date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);

CREATE TABLE TRUCK_TESTS (test_number INT NOT NULL AUTO_INCREMENT PRIMARY KEY, customer_id INT NOT NULL, license_plate TEXT NOT NULL, completed BOOLEAN NOT NULL DEFAULT false, test_result TEXT, passfail BOOLEAN, date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);

CREATE TABLE TRAILER_TESTS (test_number INT NOT NULL AUTO_INCREMENT PRIMARY KEY, customer_id INT NOT NULL, license_plate TEXT NOT NULL, completed BOOLEAN NOT NULL DEFAULT false, test_result TEXT, passfail BOOLEAN, date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);

CREATE TABLE TEST_RESULTS (customer_id INT NOT NULL PRIMARY KEY, license_plate TEXT NOT NULL, truck_result TEXT NOT NULL, truck_num_failed INT NOT NULL DEFAULT 0, trailer_result TEXT NOT NULL, trailer_num_failed INT NOT NULL DEFAULT 0, date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);

# Fill customer database with seed values
# INSERT INTO CUSTOMERS (name, phone_number, license_plate, vehicle_type, vehicle_year) VALUES ('test', '+1 000 000 0000', '000-AAA', 'Toyota Tacoma TRD Off-Road', 2019);