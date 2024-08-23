# AES helper class for pycrypto
# Copyright (c) Dennis Lee
# Date 22 Mar 2017

# Description:
# Python helper class to perform AES encryption, decryption with CBC Mode & PKCS7 Padding

# References:
# https://www.dlitz.net/software/pycrypto/api/2.6/
# http://japrogbits.blogspot.my/2011/02/using-encrypted-data-between-python-and.html

# Encoding seems to work odd in a virtual environment
# https://stackoverflow.com/questions/50302827/object-type-class-str-cannot-be-passed-to-c-code-virtual-environment

'''
Libraries

Package      Version
------------ -------
pip          23.0.1
pkcs         0.1.1
pkcs7        0.1.2
pycryptodome 3.20.0
setuptools   66.1.1
'''

import aes
from base64 import b64encode, b64decode
from Crypto.Cipher import AES
from pkcs7 import PKCS7Encoder

encoder = PKCS7Encoder()

def encrypt(plaintext, key, iv):
    global encoder
    key_length = len(key)
    if (key_length >= 32):
        k = key[:32]
    elif (key_length >= 24):
        k = key[:24]
    else:
        k = key[:16]

    aes = AES.new(k, AES.MODE_CBC, iv[:16])
    pad_text = encoder.encode(plaintext)
    return aes.encrypt(pad_text.encode())

def decrypt(ciphertext, key, iv):
    global encoder
    key_length = len(key)
    if (key_length >= 32):
        k = key[:32]
    elif (key_length >= 24):
        k = key[:24]
    else:
        k = key[:16]

    aes = AES.new(k, AES.MODE_CBC, iv[:16])
    pad_text = aes.decrypt(ciphertext)
    return encoder.decode(pad_text.decode())

def main():
    plaintext = "Hello World"

    # 32 byte key is aes-256
    key = b'your key 32bytesyour key 32bytes'

    # 16 byte key is aes-128
    key = b'your key 16bytes'
    iv = b'1234567812345678' # 16 bytes initialization vector
    print("Key: '%s'" % key)
    print("IV: '%s'" % iv)

    encrypted = b64encode(aes.encrypt(plaintext, key, iv))
    print("Encrypted: '%s'" % encrypted)

    encrypted = "2/QiRFDoA2O2Sk/U0PHZTg=="
    decrypted = aes.decrypt(b64decode(encrypted), key, iv)
    print("Decrypted: '%s'" % decrypted)

    print ("done")

if __name__ == '__main__':
    main()
