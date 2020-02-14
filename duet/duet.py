from flask import request, url_for
from flask_api import FlaskAPI, status, exceptions
import mysql.connector
import json

app = FlaskAPI(__name__)

@app.route("/hello-world")
def hello_world(dealership):
    return 'Hello world from DUET!'

# This is how the endpoint will receive data from controller applications
@app.route('/upload/', methods=['POST'])
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
@app.route('/download/', methods=['POST'])
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
    app.run(debug=True, port=5000)



# def note_repr(key):
#     return {
#         'url': request.host_url.rstrip('/') + url_for('notes_detail', key=key),
#         'text': notes[key]
#     }

# @app.route("/", methods=['GET', 'POST'])
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


# @app.route("/<int:key>/", methods=['GET', 'PUT', 'DELETE'])
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

