# Initialize tables
CREATE TABLE ADMIN_DATA(name TEXT NOT NULL, phone TEXT, email TEXT NOT NULL, pass TEXT NOT NULL, employee_id TEXT NOT NULL);

CREATE TABLE CUSTOMER_DATA(id INTEGER PRIMARY KEY AUTO_INCREMENT, name TEXT NOT NULL, phone TEXT NOT NULL, email TEXT, addr1 TEXT, addr2 TEXT, city TEXT, state TEXT, zip INTEGER, truckplate TEXT NOT NULL, trailerplate TEXT NOT NULL, timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);

CREATE TABLE TRUCK_TEST_DATA(id INTEGER PRIMARY KEY NOT NULL, module_id TEXT NOT NULL, employee_id TEXT NOT NULL, cust_phone TEXT NOT NULL, cust_email TEXT NOT NULL, test1_result INTEGER, test1_current REAL, test2_result INTEGER, test2_current REAL, test3_result INTEGER, test3_current REAL, test4_result INTEGER, test4_current REAL, timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);

CREATE TABLE TRAILER_TEST_DATA(id INTEGER PRIMARY KEY NOT NULL, module_id TEXT NOT NULL, employee_id TEXT NOT NULL, cust_phone TEXT NOT NULL, cust_email TEXT NOT NULL, test1_result INTEGER, test1_current REAL, test2_result INTEGER, test2_current REAL, test3_result INTEGER, test3_current REAL, test4_result INTEGER, test4_current REAL, timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);

# Fill customer database with seed values
INSERT INTO CUSTOMER_DATA (name, phone, email, addr1, addr2, city, state, zip, truckplate, trailerplate) VALUES ('test', '+1 000 000 0000', 'test@mail.com', 'address 1', 'apt', 'city', 'texas', 00000, '000-AAA', 'ZZZ-111');
