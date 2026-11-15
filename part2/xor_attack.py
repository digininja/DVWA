#!/usr/bin/env python3
"""
BEFORE-test for the DVWA Cryptography module (Low level: repeating-key XOR).

Shows four things an attacker can do against the XOR cipher, using only the
standard library. Run:   python xor_attack.py

Optional: use values you captured from the real app (see the notes at the bottom).
"""
import base64
import time

# --- The cipher exactly as DVWA's vulnerabilities/cryptography/source/low.php does it ---
def xor_this(data: bytes, key: bytes) -> bytes:
    return bytes(b ^ key[i % len(key)] for i, b in enumerate(data))

SECRET_KEY = b"wachtwoord"  # hardcoded in low.php; the attacker does NOT know it in tests 2-4
INTERCEPTED_B64 = "Lg4WGlQZChhSFBYSEB8bBQtPGxdNQSwEHREOAQY="  # shown on the Low page
intercepted = base64.b64decode(INTERCEPTED_B64)


def test1_hardcoded_key():
    print("\n[1] Key read from the source code (CWE-321)")
    t = time.perf_counter()
    plain = xor_this(intercepted, SECRET_KEY)
    print("    plaintext :", plain.decode())
    print(f"    time      : {(time.perf_counter() - t) * 1000:.3f} ms")


def test2_chosen_plaintext():
    """The app's own 'Encode' box is an encryption oracle. Encrypt known text -> get the key."""
    print("\n[2] Key recovery WITHOUT the source code (the Encode box leaks the key)")
    chosen = b"A" * 30                                   # what the attacker types in the box
    oracle_ct = xor_this(chosen, SECRET_KEY)             # what the app returns (before base64)
    keystream = bytes(c ^ p for c, p in zip(oracle_ct, chosen))
    # the key repeats, so the first 10 bytes are the key; find the shortest repeating unit
    for n in range(1, len(keystream) + 1):
        if all(keystream[i] == keystream[i % n] for i in range(len(keystream))):
            recovered = keystream[:n]
            break
    print("    recovered key :", recovered)
    print("    decrypts intercepted message ->", xor_this(intercepted, recovered).decode())


def test3_two_time_pad():
    """Two messages under the same key: XOR of the ciphertexts removes the key completely."""
    print("\n[3] Two-time pad: c1 XOR c2 = p1 XOR p2 (the key cancels out)")
    p1, p2 = b"Meet me at the library at 9", b"Password for the lab is: 1234"
    c1, c2 = xor_this(p1, SECRET_KEY), xor_this(p2, SECRET_KEY)
    leak = bytes(a ^ b for a, b in zip(c1, c2))
    expected = bytes(a ^ b for a, b in zip(p1, p2))
    print("    c1^c2 == p1^p2 :", leak == expected)
    # crib-drag: if the attacker guesses a word in p1, the matching part of p2 appears
    crib = b"Meet"
    print("    crib 'Meet' at position 0 reveals p2[0:4] =", bytes(a ^ b for a, b in zip(leak, crib)))


def test4_malleability():
    """No integrity: flip bits in the ciphertext and the plaintext changes in a predictable way."""
    print("\n[4] Tampering is not detected (no integrity / authentication)")
    original = xor_this(intercepted, SECRET_KEY)
    pos = original.index(b"Olifant")
    tampered = bytearray(intercepted)
    for i, (old, new) in enumerate(zip(b"Olifant", b"Hackers")):
        tampered[pos + i] ^= old ^ new                    # attacker never needs the key
    print("    original :", original.decode())
    print("    tampered :", xor_this(bytes(tampered), SECRET_KEY).decode(), "  <- accepted, no error")


if __name__ == "__main__":
    test1_hardcoded_key()
    test2_chosen_plaintext()
    test3_two_time_pad()
    test4_malleability()

# HOW TO REPRODUCE TEST 2 ON THE REAL APP (good screenshot for the report):
#   1. Set DVWA Security to Low, open the Cryptography page.
#   2. In "Message" type 30 letter A's, choose Encode, Submit.
#   3. Copy the base64 output, decode it, XOR it with 'A' -> you get the repeating key.
