from locust import HttpUser, task, between

class UserLoginTest(HttpUser):
    wait_time = between(1, 3)

    @task
    def login(self):
        payload = {
            "email": "juunh2h@gmail.com",
            "password": "juunjuun22"
        }
        with self.client.post("/api/login", json=payload, catch_response=True) as response:
            print(response.status_code, response.text)
            if response.status_code == 200 and '"success":true' in response.text:
                response.success()
            else:
                response.failure(f"Failed login: {response.status_code}, {response.text}")
