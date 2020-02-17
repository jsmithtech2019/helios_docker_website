from flask import request, url_for
from flask_api import FlaskAPI, status, exceptions
import mysql.connector
import json
import logging

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
        return 'Missing JSON in request', status.HTTP_400_BAD_REQUEST

    # Check that JSON is valid
    try:
        content = request.get_json()
    except Exception as e:
        return 'Malformed JSON in request', status.HTTP_400_BAD_REQUEST

    # Parse dealership information
    if not dealership_check(cursor, request.json['dealership']):
        return '', status.HTTP_403_FORBIDDEN

    # Parse Employee information
    if not employee_check(cursor, request.json['employee']):
        return '', status.HTTP_403_FORBIDDEN

    print("Everything worked")

    # Basic Mysql connection setup
    # import mysql.connector
    # conn = mysql.connector.connect(host='db',database='hitch',password='helios')
    # cursor = conn.cursor()
    # cursor.execute('SHOW TABLES;')
    # c = cursor.fetchall()
    # print(c)

    ## At this point everything is good, parse and evaluate
    # Parse customer information
    customer = request.json['customer']

    # Parse test information
    truck = request.json['results']['truck']
    trailer = request.json['results']['trailer']

    # TODO: do things
    # return jsonify({"msg": "Missing JSON in request"}), 400
    return '', status.HTTP_201_CREATED

# Returns test results for specified customer in JSON
@duet.route('/download/', methods=['POST'])
def download_data():
    return '', status.HTTP_202_ACCEPTED

def dealership_check(cursor, data):
    # Check UUID matches dealership UUID
    cursor.execute('SELECT BIN_TO_UUID(dealership_uuid) dealership_uuid FROM ADMIN_DATA')
    c = cursor.fetchall()

    # For all values fetched from MySQL, at 0, compare to received value
    if data['dealership_uuid'] not in (p[0] for p in c):
        logging.info('Received dealership_uuid does not match, received: {}'.format(data['dealership_uuid']))
        return False

    # Check dealership name matches stored dealership
    cursor.execute('SELECT dealership FROM ADMIN_DATA LIMIT 1')
    c = cursor.fetchone()

    # Validate match
    if data['dealership'] != c[0]:
        logging.info('Received dealership does not match, received: {}'.format(data['dealership']))
        return False

    return True

def employee_check(cursor, data):
    # Check employee id exists in database
    try:
        # Check employee exists
        cursor.execute('SELECT BIN_TO_UUID(employee_uuid) employee_uuid FROM ADMIN_DATA WHERE email="{}" AND pass="{}" AND phone="{}" LIMIT 1'.format(data['email'], data['password'], data['phone']))
        c = cursor.fetchone()

        # Validate matching UUID for the employee from server database
        if data['uuid'] != c[0]:
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
        print('Error as: {}'.format(e))
        return False

    return True

if __name__ == "__main__":
    # Setup logging
    logging.basicConfig(filename='duet.log', level=logging.DEBUG)
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

