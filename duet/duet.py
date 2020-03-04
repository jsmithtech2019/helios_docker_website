from flask import request, url_for
from flask_api import FlaskAPI, status, exceptions
import json
import logging
import mysql.connector
import uuid

# Setup logging
logging.basicConfig(level=logging.DEBUG,
    format='%(asctime)s %(levelname)s %(message)s',
    handlers=[
        logging.FileHandler(filename='/var/log/duet.log'),
        logging.StreamHandler()
    ])

duet = FlaskAPI(__name__)

@duet.route('/hello-world')
def hello_world():
    return 'Hello world from DUET!', status.HTTP_200_OK


# This is how the endpoint will receive data from controller applications
@duet.route('/upload/', methods=['POST'])
def upload_data():
    # Open MySQL connection
    conn = mysql.connector.connect(host='db',database='hitch',password='helios')
    cursor = conn.cursor()

    # Check if valid JSON
    if not request.is_json:
        logging.info('Missing JSON in request')
        return 'Missing JSON in request\n', status.HTTP_406_NOT_ACCEPTABLE

    # Check that JSON is valid
    try:
        content = request.get_json()
    except Exception as e:
        logging.info('Malformed JSON in request')
        return 'Malformed JSON in request\n', status.HTTP_400_BAD_REQUEST

    # Parse dealership information
    if not dealership_check(cursor, request.json['dealership']):
        return 'TODO: REMOVE:: dealership no match\n', status.HTTP_403_FORBIDDEN

    # Parse Employee information
    if not employee_check(cursor, request.json['employee']):
        return 'TODO: REMOVE:: employee no match\n', status.HTTP_403_FORBIDDEN

    # Parse customer information and insert into database
    cust_insert = customer_insert(cursor, request.json['customer'])
    if not cust_insert[1]:
        logging.warning('Insertion of customer failed: {}'.format(cust_insert[0]))
        return '{}\n'.format(cust_insert[0]), status.HTTP_400_BAD_REQUEST

    # Insert truck test results
    if not truck_insert(cursor, request.json):
        return 'Insertion of truck results failed\n', status.HTTP_400_BAD_REQUEST

    # Insert trailer test results
    if not trailer_insert(cursor, request.json):
        return 'Insertion of trailer results failed\n', status.HTTP_400_BAD_REQUEST

    # TODO: if conflicting test or truck data return HTTP_409_CONFLICT

    # Commit the successfully staged changes to MySQL database
    conn.commit()

    return '', status.HTTP_201_CREATED

# Returns test results for specified customer in JSON
@duet.route('/download/', methods=['POST'])
def download_data():
    return '', status.HTTP_202_ACCEPTED

def truck_insert(cursor, data):
    try:
        # Convert UUID into usable values
        module_uuid = uuid.UUID(data['employee']['module_uuid']).bytes
        employee_uuid = uuid.UUID(data['employee']['employee_uuid']).bytes

        testdata = data['results']['truck']
        query = ('INSERT INTO TRUCK_TEST_DATA '
            '(module_uuid, employee_uuid, cust_phone, cust_email, '
            'test1_result, test1_current, test2_result, test2_current, '
            'test3_result, test3_current, test4_result, test4_current)'
            'VALUES (UNHEX(REPLACE("{}", "-","")), UNHEX(REPLACE("{}", "-","")), "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}")'.format(
                data['employee']['module_uuid'], data['employee']['employee_uuid'], data['customer']['phone'], data['customer']['email'],
                testdata[0]['value'], testdata[0]['current'],testdata[1]['value'], testdata[1]['current'],
                testdata[2]['value'], testdata[2]['current'],testdata[3]['value'], testdata[3]['current']))

        cursor.execute(query)
        logging.info('{} record inserted.'.format(cursor.rowcount))

    except Exception as e:
        logging.warning('Error as: {}'.format(e))
        return False

    return True

def trailer_insert(cursor, data):
    try:
        # Convert UUID into usable values
        module_uuid = data['employee']['module_uuid']
        employee_uuid = data['employee']['employee_uuid']

        testdata = data['results']['trailer']
        query = ('INSERT INTO TRAILER_TEST_DATA '
            '(module_uuid, employee_uuid, cust_phone, cust_email, '
            'test1_result, test1_current, test2_result, test2_current, '
            'test3_result, test3_current, test4_result, test4_current)'
            'VALUES (UNHEX(REPLACE("{}", "-", "")), UNHEX(REPLACE("{}", "-", "")), "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}")'.format(
                data['employee']['module_uuid'], data['employee']['employee_uuid'], data['customer']['phone'], data['customer']['email'],
                testdata[0]['value'], testdata[0]['current'],testdata[1]['value'], testdata[1]['current'],
                testdata[2]['value'], testdata[2]['current'],testdata[3]['value'], testdata[3]['current']))

        cursor.execute(query)
        logging.info('{} record inserted.'.format(cursor.rowcount))

    except Exception as e:
        logging.warning('Error as: {}'.format(e))
        return False

    return True

def customer_insert(cursor, data):
    # Check that there are no duplicate entries
    cursor.execute('SELECT * FROM CUSTOMER_DATA WHERE testtime="{}"'.format(data['timestamp']))
    cursor.fetchall()

    # If anything was returned exit with a failure and log duplicate
    if cursor.rowcount > 0:
        logging.warning('Duplicate entry found for timestamp: {}'.format(data['timestamp']))
        return ('Duplicate entry found', False)

    # No duplicates found, go ahead and insert
    query = ('INSERT INTO CUSTOMER_DATA '
        '(name, phone, email, addr1, addr2, city, state, zip, truckplate, trailerplate, testtime)'
        ' VALUES ("{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}")'.format(
            data['name'], data['phone'], data['email'], data['addr1'], data['addr2'],
            data['city'], data['state'], data['zip'], data['truck'], data['trailer'], data['timestamp']))
    cursor.execute(query)
    logging.info('{} record inserted.'.format(cursor.rowcount))

    return ('', True)

def dealership_check(cursor, data):
    # Check UUID matches dealership UUID
    #cursor.execute('SELECT BIN_TO_UUID(dealership_uuid) dealership_uuid FROM ADMIN_DATA')
    cursor.execute('SELECT dealership FROM ADMIN_DATA WHERE dealership_uuid=UNHEX(REPLACE("{}", "-", ""))'.format(data['dealership_uuid']))
    cursor.fetchall()

    # For all values fetched from MySQL, at 0, compare to received value
    if cursor.rowcount == 0:
        logging.warning('Received dealership_uuid does not match, received: {}'.format(data['dealership_uuid']))
        return False

    # Check dealership name matches stored dealership
    cursor.execute('SELECT dealership FROM ADMIN_DATA WHERE dealership="{}"'.format(data['dealership']))
    cursor.fetchall()

    # Validate match
    if cursor.rowcount == 0:
        logging.warning('Received dealership does not match, received: {}'.format(data['dealership']))
        return False

    return True

def employee_check(cursor, data):
    # Check employee id exists in database
    try:
        # Check employee exists
        cursor.execute('SELECT dealership FROM ADMIN_DATA WHERE employee_uuid=UNHEX(REPLACE("{}", "-", "")) AND email="{}" AND pass="{}" AND phone="{}" LIMIT 1'.format(data['employee_uuid'], data['email'], data['password'], data['phone']))
        cursor.fetchall()

        # Validate matching UUID for the employee from server database
        if cursor.rowcount == 0:
            logging.info('Employee UUID not found, received: {}'.format(data['employee_uuid']))
            return False

        # Check module exists
        cursor.execute('SELECT dealership FROM ADMIN_DATA WHERE module_uuid=UNHEX(REPLACE("{}", "-", ""))'.format(data['module_uuid']))
        cursor.fetchall()

        # For all values fetched from MySQL, at 0, compare to received value
        if cursor.rowcount == 0:
            logging.info('Module not found, received: {}'.format(data['module_uuid']))
            return False

    except Exception as e:
        # Malformed SQL query
        logging.warning('Error as: {}'.format(e))
        return False

    return True

if __name__ == "__main__":
    duet.run(debug=True, port=5000)
