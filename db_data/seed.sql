# Initialize tables
CREATE TABLE ADMIN_DATA(
    dealership TEXT NOT NULL, 
    dealership_uuid BINARY(16), 
    module_uuid BINARY(16),
    # Employees
    name TEXT NOT NULL, 
    phone TEXT, 
    email TEXT NOT NULL, 
    pass TEXT NOT NULL, 
    employee_uuid BINARY(16) NOT NULL);

CREATE TABLE CUSTOMER_DATA(
    id INTEGER PRIMARY KEY AUTO_INCREMENT, 
    name TEXT NOT NULL, 
    phone TEXT NOT NULL, 
    email TEXT, 
    addr1 TEXT, 
    addr2 TEXT, 
    city TEXT, 
    state TEXT, 
    zip INTEGER, 
    truckplate TEXT NOT NULL, 
    trailerplate TEXT NOT NULL, 
    testtime TEXT NOT NULL, 
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);

CREATE TABLE TRUCK_TEST_DATA(id INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    module_uuid BINARY(16) NOT NULL, 
    employee_uuid BINARY(16) NOT NULL, 
    cust_phone TEXT NOT NULL, 
    cust_email TEXT NOT NULL, 
    truck_plate TEXT NOT NULL, 
    trailer_plate TEXT NOT NULL, 
    test1_result INTEGER, 
    test1_current REAL, 
    test2_result INTEGER, 
    test2_current REAL, 
    test3_result INTEGER, 
    test3_current REAL, 
    test4_result INTEGER, 
    test4_current REAL, 
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);

CREATE TABLE TRAILER_TEST_DATA(id INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    module_uuid BINARY(16) NOT NULL, 
    employee_uuid BINARY(16) NOT NULL, 
    cust_phone TEXT NOT NULL, 
    cust_email TEXT NOT NULL, 
    truck_plate TEXT NOT NULL, 
    trailer_plate TEXT NOT NULL, 
    test1_result INTEGER, 
    test1_current REAL, 
    test2_result INTEGER, 
    test2_current REAL, 
    test3_result INTEGER, 
    test3_current REAL, 
    test4_result INTEGER, 
    test4_current REAL, 
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);

# Fill admin database with seed values
INSERT INTO ADMIN_DATA (dealership, dealership_uuid, module_uuid, name, phone, email, pass, employee_uuid) VALUES ('admin', UUID_TO_BIN(UUID()), UUID_TO_BIN(UUID()), 'admin', 'admin', 'admin', 'admin', UUID_TO_BIN(UUID()));