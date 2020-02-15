from flask import request, url_for
from flask_api import FlaskAPI, status, exceptions
import mysql.connector
import json

duet = FlaskAPI(__name__)

@duet.route('/hello-world')
def hello_world():
    return 'Hello world from DUET!'

@duet.route('/sqltest/', methods=['POST'])
def sql_test():
    # Open MySQL connection
    conn = mysql.connector.connect(host='db',database='hitch',password='helios')
    cursor = conn.cursor()

    # Get name from POST
    name = str(request.data.get('text', ''))

    # Execute query
    cursor.execute('INSERT INTO ADMIN_DATA (name, email, pass, employee_id) VALUES ("{}", "test", "pass", "id");'.format(name))

    # Get values and close connection
    #c = cursor.fetchall()
    conn.commit()
    cursor.close()
    conn.close()

    return '', status.HTTP_201_CREATED


# This is how the endpoint will receive data from controller applications
@duet.route('/upload/', methods=['POST'])
def upload_data():
    # Check if valid JSON
    if not request.is_json:
        return 'Missing JSON in request', status.HTTP_400_BAD_REQUEST
        #return '', status.HTTP_400_BAD_REQUEST

    # With valid JSON, parse data from body of request
    content = request.get_json()

    # Parse dealership information
    dealership_check(request.json['dealership'])

    # Parse Employee information
    employee_check(request.json['employee'])

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

def dealership_check(data):
    # Check ID matches dealership
    dealership_id = data['id']
    #if not mysql.checkexists(dealership_id):
    #    return '', status.HTTP_401_UNAUTHORIZED

    # Check name matches dealership
    dealership_name = data['name']
    #if not mysql.checkexists(dealership_name):
    #    return '', status.HTTP_401_UNAUTHORIZED

def employee_check(data):
    # Check employee id exists in database
    employee_id = data['id']
    #if not mysql.checkexists(employee_id):
    #    return '', status.HTTP_401_UNAUTHORIZED

    employee_email = data['']
    #if not mysql.checkexists(employee_email):
    #    return '', status.HTTP_401_UNAUTHORIZED

    employee_password = data['password']
    #if not mysql.checkexists(employee_password):
    #    return '', status.HTTP_401_UNAUTHORIZED

    employee_phone = data['phone']
    #if not mysql.checkexists(employee_phone):
    #    return '', status.HTTP_401_UNAUTHORIZED

    module_id = data['module']
    #if not mysql.checkexists(module_id):
    #    return '', status.HTTP_401_UNAUTHORIZED

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

