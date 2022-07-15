See the [Performance tests](../../../README.md#performance-tests) section in the root README.md file for details.

### Install locust
`pip3 install locust`

### *Run using configurations from locust.conf*

`locust -f {{DESIRED_LOCUSTFILE}}.py`

### *Run with proxy using --proxy command line option*

`locust -f {{DESIRED_LOCUSTFILE}}.py --proxy URL:PORT`