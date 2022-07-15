import logging
import random
from locust import HttpUser, task

urls = [
  '/search',
]

data={
    "storage": [
        {
            "start": "250GB",
            "end": "8TB"
        }
    ],
    "ram": [
        {"value": "4GB"},
        {"value": "8GB"},
        {"value": "24GB"}
    ],
    "hdd": "SATA",
    "location": "AmsterdamAMS-01"
}

headers = {'Content-type': 'application/json', 'Accept': 'text/plain'}

class User(HttpUser):
    @task
    def send_get_requests(self):
      random.shuffle(urls)
      for url in urls:
        self.client.get(url, json=data, headers=headers)
