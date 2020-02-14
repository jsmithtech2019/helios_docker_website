from flask import request, url_for
from flask_api import FlaskAPI, status, exceptions
import mysql.connector

app = FlaskAPI(__name__)

@app.route("/hello-world")
def hello_world():
    return 'Hello world from DUET!'

# This is how the endpoint will receive data from controller applications
@app.route('/upload/', methods=['POST'])
def upload_data():
    # Parse data from body of request
    post_body = request.json




# Returns test results for specified customer in JSON
@app.route('/download/', methods=['POST'])
def download_data():


if __name__ == "__main__":
    app.run(debug=True)



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

