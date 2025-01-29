
from cryptography.fernet import Fernet
from flask import Flask, request, jsonify
import logging

app = Flask(_name_)
# 
logging.basicConfig(filename='activity.log', level=logging.INFO,
                    format='%(asctime)s:%(levelname)s:%(message)s')

def log_activity(activity):
    logging.info(activity)

def generate_key():
    key = Fernet.generate_key()
    with open('secret.key', 'wb') as key_file:
        key_file.write(key)
    return key

def load_key():
    with open('secret.key', 'rb') as key_file:
        return key_file.read()


try:
    key = load_key()
except FileNotFoundError:
    key = generate_key()

cipher_suite = Fernet(key)

@app.route('/encrypt', methods=['POST'])
def encrypt():
    data = request.json['data']
    encrypted_data = cipher_suite.encrypt(data.encode())
    log_activity(f"Data encrypted: {encrypted_data.decode()}")
    return jsonify({'encrypted_data': encrypted_data.decode()})

@app.route('/decrypt', methods=['POST'])
def decrypt():
    data = request.json['data']
    decrypted_data = cipher_suite.decrypt(data.encode())
    log_activity(f"Data decrypted: {decrypted_data.decode()}")
    return jsonify({'decrypted_data': decrypted_data.decode()})

if _name_ == '_main_':
    app.run(host='0.0.0.0', port=5000)