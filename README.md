# leaseWebChallenge

## Setting up and running the project
Next we need to download and configure the symfony project inside the php container.

```
cd <docker4php folder path>
docker exec -it leaseWeb_php bash

git clone git@github.com:heldernunes/leaseWebChallenge.git .
composer install
exit
```

### testing the Project:
to test this, you can make requests to "http://127.0.0.1:8000/index", or you can add the following url 'http://php.docker.localhost:8000/index' to the end of your hosts file.

Edit using some text editor.
```
sudo vi /etc/hosts
```
add the following line in the end of the file.
```
127.0.0.1 php.docker.localhost
```

### Make a test call to your project:

```
curl --location --request GET 'http://php.docker.localhost:8000/index'
```

#### Setting up the db.
For the challenge file, we have it already on the assets folder "assets/LeaseWeb_servers_filters_assignment_1.csv", you can add more csv files, as long as it as the same structure.

```
Model;RAM;HDD;Location;Price
Dell R210Intel Xeon X3440;16GBDDR3;2x2TBSATA2;AmsterdamAMS-01;€49.99
```
Database is already created when the container was started, we just need to fill the Products table.

run the following endpoint:

```
curl --location --request GET 'http://php.docker.localhost:8000/migrate'
```

After this you shoul dbe abble to start making search calls, some examples:
```
curl --location --request GET 'http://php.docker.localhost:8000/search' \
--header 'Content-Type: application/json' \
--data-raw '{
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
}'
```
or
```
curl --location --request GET 'http://php.docker.localhost:8000/search' \
--header 'Content-Type: application/json' \
--data-raw '{
    "storage": [], 
    "ram": [
        {"value": "4GB"},
        {"value": "8GB"},
        {"value": "24GB"}
    ],
    "hdd": "SATA",
    "location": "AmsterdamAMS-01"
}'
```
or
```
curl --location --request GET 'http://php.docker.localhost:8000/search' \
--header 'Content-Type: application/json' \
--data-raw '{
    "storage": [], 
    "ram": [],
    "hdd": "SATA",
    "location": "AmsterdamAMS-01"
}'

```

## Running API calls on Postman:

You can find both the request collection and environment setup in the [documentation](https://github.com/heldernunes/leaseWebChallenge/tree/main/documentation) folder on the root of the project. 
