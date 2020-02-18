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

@duet.route('/sqltest/', methods=['POST'])
def sql_test():
    # Open MySQL connection
    conn = mysql.connector.connect(host='db', database='hitch', password='helios')
    cursor = conn.cursor()

    # Get name from POST
    name = str(request.data.get('text', ''))

    # Execute query
    cursor.execute('INSERT INTO ADMIN_DATA (name, email, pass, employee_id) VALUES ("{}", "test", "pass", "id")'.format(name))

    # Get values and close connection
    #c = cursor.fetchall()
    conn.commit()
    cursor.close()
    conn.close()

    return '', status.HTTP_201_CREATED


# This is how the endpoint will receive data from controller applications
@duet.route('/upload/', methods=['POST'])
def upload_data():
    # Open MySQL connection
    conn = mysql.connector.connect(host='db',database='hitch',password='helios')
    cursor = conn.cursor()

    # Check if valid JSON
    if not request.is_json:
        logging.info('Missing JSON in request')
        return 'Missing JSON in request\n', status.HTTP_400_BAD_REQUEST

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
    cust_insert = customer_insert(cursor, conn, request.json['customer'])
    if not cust_insert[1]:
        logging.warning('Insertion of customer failed: {}'.format(cust_insert[0]))
        return '{}\n'.format(cust_insert[0]), status.HTTP_400_BAD_REQUEST

    # Insert truck test results
    if not truck_insert(cursor, conn, request.json):
        return 'Insertion of truck results failed', status.HTTP_400_BAD_REQUEST

    # Insert trailer test results
    if not trailer_insert(cursor, conn, request.json):
        return 'Insertion of trailer results failed', status.HTTP_400_BAD_REQUEST

    # TODO: do things
    # return jsonify({"msg": "Missing JSON in request"}), 400
    return '', status.HTTP_201_CREATED

# Returns test results for specified customer in JSON
@duet.route('/download/', methods=['POST'])
def download_data():
    return '', status.HTTP_202_ACCEPTED

def truck_insert(cursor, conn, data):
    try:
        # Convert UUID into usable values
        module_uuid = uuid.UUID(data['employee']['module_uuid']).bytes
        employee_uuid = uuid.UUID(data['employee']['uuid']).bytes

        testdata = data['results']['truck']
        query = ('INSERT INTO TRUCK_TEST_DATA '
            '(module_uuid, employee_uuid, cust_phone, cust_email, '
            'test1_result, test1_current, test2_result, test2_current, '
            'test3_result, test3_current, test4_result, test4_current)'
            'VALUES (UNHEX(REPLACE("{}", "-","")), UNHEX(REPLACE("{}", "-","")), "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}")'.format(
                data['employee']['module_uuid'], data['employee']['uuid'], data['customer']['phone'], data['customer']['email'],
                testdata[0]['value'], testdata[0]['current'],testdata[1]['value'], testdata[1]['current'],
                testdata[2]['value'], testdata[2]['current'],testdata[3]['value'], testdata[3]['current']
                ))

        cursor.execute(query)
        conn.commit()
        logging.info('{} record inserted.'.format(cursor.rowcount))

    except Exception as e:
        logging.warning('Error as: {}'.format(e))
        return False

    return True

def trailer_insert(cursor, conn, data):
    try:
        # Convert UUID into usable values
        module_uuid = data['employee']['module_uuid']
        employee_uuid = data['employee']['uuid']

        testdata = data['results']['trailer']
        query = ('INSERT INTO TRAILER_TEST_DATA '
            '(module_uuid, employee_uuid, cust_phone, cust_email, '
            'test1_result, test1_current, test2_result, test2_current, '
            'test3_result, test3_current, test4_result, test4_current)'
            'VALUES (UNHEX(REPLACE("{}", "-", "")), UNHEX(REPLACE("{}", "-", "")), "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}")'.format(
                data['employee']['module_uuid'], data['employee']['uuid'], data['customer']['phone'], data['customer']['email'],
                testdata[0]['value'], testdata[0]['current'],testdata[1]['value'], testdata[1]['current'],
                testdata[2]['value'], testdata[2]['current'],testdata[3]['value'], testdata[3]['current']
                ))

        cursor.execute(query)
        conn.commit()
        logging.info('{} record inserted.'.format(cursor.rowcount))

    except Exception as e:
        logging.warning('Error as: {}'.format(e))
        return False

    return True

def customer_insert(cursor, conn, data):
    # Check that there are no duplicate entries
    cursor.execute('SELECT COUNT(id) FROM CUSTOMER_DATA WHERE testtime="{}"'.format(data['timestamp']))
    c = cursor.fetchall()

    # If anything was returned exit with a failure and log duplicate
    # List<Tuple<Val,_>> where Val = int
    if c[0][0] != 0:
        logging.warning('Duplicate entry found at timestamp: {}'.format(data['timestamp']))
        return ('Duplicate entry found', False)

    # No duplicates found, go ahead and insert
    query = ('INSERT INTO CUSTOMER_DATA '
        '(name, phone, email, addr1, addr2, city, state, zip, truckplate, trailerplate, testtime)'
        ' VALUES ("{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}", "{}")'.format(
            data['name'], data['phone'], data['email'], data['addr1'], data['addr2'],
            data['city'], data['state'], data['zip'], data['truck'], data['trailer'], data['timestamp']))
    cursor.execute(query)
    conn.commit()
    logging.info('{} record inserted.'.format(cursor.rowcount))

    return ('', True)

def dealership_check(cursor, data):
    # Check UUID matches dealership UUID
    cursor.execute('SELECT BIN_TO_UUID(dealership_uuid) dealership_uuid FROM ADMIN_DATA')
    c = cursor.fetchall()

    # For all values fetched from MySQL, at 0, compare to received value
    if data['dealership_uuid'] not in (p[0] for p in c):
        logging.warning('Received dealership_uuid does not match, received: {}'.format(data['dealership_uuid']))
        return False

    # Check dealership name matches stored dealership
    cursor.execute('SELECT dealership FROM ADMIN_DATA LIMIT 1')
    c = cursor.fetchone()

    # Validate match
    if data['dealership'] != c[0]:
        logging.warning('Received dealership does not match, received: {}'.format(data['dealership']))
        return False

    return True

def employee_check(cursor, data):
    # Check employee id exists in database
    try:
        # Check employee exists
        cursor.execute('SELECT BIN_TO_UUID(employee_uuid) employee_uuid FROM ADMIN_DATA WHERE email="{}" AND pass="{}" AND phone="{}" LIMIT 1'.format(data['email'], data['password'], data['phone']))
        c = cursor.fetchone()

        # Validate matching UUID for the employee from server database
        if c:
            if data['uuid'] != c[0]:
                logging.info('Employee UUID not found, received: {}'.format(data['uuid']))
                return False
        else:
            logging.info('Employee UUID not found, received: {}'.format(data['uuid']))
            return False

        # Check module exists
        #cursor.execute('SELECT module_uuid FROM ADMIN_DATA WHERE module_uuid=UUID_TO_BIN("{}") LIMIT 1'.format(data['module_uuid']))
        cursor.execute('SELECT BIN_TO_UUID(module_uuid) module_uuid FROM ADMIN_DATA')
        c = cursor.fetchall()

        # For all values fetched from MySQL, at 0, compare to received value
        if data['module_uuid'] not in (p[0] for p in c):
            logging.info('Module not found, received: {}'.format(data['module_uuid']))
            return False

    except Exception as e:
        # Malformed SQL query
        logging.warning('Error as: {}'.format(e))
        return False

    return True

if __name__ == "__main__":
    duet.run(debug=True, port=5000)



# def note_repr(key):
#     return {
#         'url': request.host_url.rstrip('/') + url_for('notes_detail', key=key),
#         'text': notes[key]
#     }

# @duet.route("/", methods=['GET', 'POST'])
# def notes_list():
#     """
#     List or create notes.
#     """
#     if request.method == 'POST':
#         note = str(request.data.get('text', ''))
#         idx = max(notes.keys()) + 1
#         notes[idx] = note
#         return note_repr(idx), status.HTTP_201_CREATED

#     # request.method == 'GET'
#     return [note_repr(idx) for idx in sorted(notes.keys())]


# @duet.route("/<int:key>/", methods=['GET', 'PUT', 'DELETE'])
# def notes_detail(key):
#     """
#     Retrieve, update or delete note instances.
#     """
#     if request.method == 'PUT':
#         note = str(request.data.get('text', ''))
#         notes[key] = note
#         return note_repr(key)

#     elif request.method == 'DELETE':
#         notes.pop(key, None)
#         return '', status.HTTP_204_NO_CONTENT

#     # request.method == 'GET'
#     if key not in notes:
#         raise exceptions.NotFound()
#     return note_repr(key)

