import socket
import serial
import requests
from flask import Flask, request, jsonify
import threading

# --- Konfigurasi ---
READER_IP = '192.168.1.5'
READER_PORT = 6000

ARDUINO_PORT = 'COM3'
BAUD_RATE = 57600
arduino = serial.Serial(ARDUINO_PORT, BAUD_RATE, timeout=1)

VALIDATE_URL = "http://192.168.1.200:8000/api/validate-tag"
NOTIFY_URL = "http://192.168.1.200:8000/api/send-verification"
STORE_URL = "http://192.168.1.200:8000/api/store-output"

app = Flask(__name__)
last_verified_tag = None

# --- Fungsi bantu ---
def parse_tag(data_bytes):
    """Ubah byte array menjadi string hex tag UID."""
    return ''.join(f'{b:02X}' for b in data_bytes)

def send_to_arduino(command):
    arduino.write(f"{command}\n".encode())

def store_output(tag_uid, status, message):
    try:
        requests.post(STORE_URL, json={
            'tag_uid': tag_uid,  # ✅ konsisten pakai tag_uid
            'status': status,
            'message': message
        }, timeout=2)
    except Exception as e:
        print("Store log error:", e)

@app.route('/open-gate', methods=['POST'])
def open_gate():
    data = request.get_json()
    tag_uid = data.get('tag_uid')  # ✅ gunakan tag_uid
    user_id = data.get('user_id')
    action = data.get('action')

    if action == 'open':
        print(f"✅ Verifikasi user {user_id} untuk tag {tag_uid}: membuka palang")
        send_to_arduino("OPEN")
        store_output(tag_uid, 'Approved', 'Akses dibuka setelah verifikasi user')
        return jsonify({'success': True, 'message': 'Gate opened'}), 200

    return jsonify({'success': False, 'message': 'Invalid action'}), 400

# --- Fungsi untuk mendengarkan dari RFID Reader ---
def listen_rfid_reader():
    global last_verified_tag
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    sock.connect((READER_IP, READER_PORT))
    print("📡 Terhubung ke RFID reader via TCP/IP")

    while True:
        try:
            data = sock.recv(1024)
            print("📥 Raw data bytes:", list(data))

            if not data:
                continue

            if len(data) >= 8 and data[2] == 0xEE and data[3] == 0x00:
                tag_data = data[4:8]
                tag_uid = parse_tag(tag_data)
                print(f"🏷️ Tag terbaca: {tag_uid}")

                try:
                    res = requests.get(VALIDATE_URL, params={'tag_uid': tag_uid}, timeout=30)
                    if res.status_code == 200 and res.json().get('status') == 'success':
                        user_id = res.json().get('user_id')
                        print("✅ Tag valid, menunggu verifikasi user...")
                        store_output(tag_uid, 'Pending', 'Menunggu verifikasi user')
                        last_verified_tag = tag_uid

                        # 🚀 Kirim notifikasi ke Laravel
                        try:
                            notify_res = requests.post(NOTIFY_URL, json={
                                'user_id': user_id,
                                'tag_uid': tag_uid  # ✅ pakai tag_uid
                            }, timeout=30)
                            print("📨 Notifikasi dikirim:", notify_res.status_code, notify_res.text)
                        except Exception as e:
                            print("❌ Gagal kirim notifikasi:", e)

                    else:
                        print("❌ Tag tidak valid")
                        send_to_arduino("CLOSE")
                        store_output(tag_uid, 'Invalid', 'Tag tidak ditemukan')

                except Exception as e:
                    print("❌ Error HTTP:", e)
                    send_to_arduino("CLOSE")

            else:
                print("⚠️ Format data tidak cocok:", list(data))

        except Exception as e:
            print("❌ RFID Reader Error:", e)

# --- Menjalankan Flask dan Listener Bersamaan ---
if __name__ == '__main__':
    threading.Thread(target=listen_rfid_reader, daemon=True).start()
    app.run(host='0.0.0.0', port=8000)
