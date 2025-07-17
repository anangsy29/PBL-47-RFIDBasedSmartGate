from locust import HttpUser, task, between

class SmartGateEnduranceUser(HttpUser):
    wait_time = between(3, 10)  # simulasi kendaraan lewat setiap 3–10 detik

    def on_start(self):
        # Jika butuh login untuk token, aktifkan bagian ini dan sesuaikan payloadnya
        """
        response = self.client.post("/api/login", json={
            "email": "juunh2h@gmail.com",   # Ganti sesuai akun test
            "password": "juunjuun22"          # Ganti sesuai akun test
        })
        if response.status_code == 200:
            token = response.json().get("token")
            self.headers = {
                "Authorization": f"Bearer {token}",
                "Content-Type": "application/json"
            }
        else:
            print(f"Login failed: {response.text}")
            self.headers = {
                "Content-Type": "application/json"
            }
        """
        # Jika tidak perlu login, gunakan header kosong
        self.headers = {
            "Content-Type": "application/json"
        }

    @task
    def validate_rfid_tag(self):
        # Step 1: Validasi RFID tag
        payload = {
            "tag_uid": "DF2205JUN"  # Ganti dengan UID RFID test yang valid
        }
        with self.client.post("/api/validate-tag", json=payload, headers=self.headers, catch_response=True) as response:
            if response.status_code == 200:
                response.success()
                # Step 2: Log akses gate (opsional, jika ingin uji alur lengkap)
                log_payload = {
                    "user_id": 1,   # ID user test
                    "tag_uid": "DF2205JUN",
                    "status": "opened"
                }
                self.client.post("/api/log-access", json=log_payload, headers=self.headers)

                # Step 3: Store output gate (opsional, jika ingin uji kendali relay)
                output_payload = {
                    "message": "Gate opened for UID DF2205JUN"
                }
                self.client.post("/api/store-output", json=output_payload, headers=self.headers)
            else:
                response.failure(f"Validation failed: {response.status_code} {response.text}")
