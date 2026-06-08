# LeaseWeb Backend Challenge

A Symfony-based REST API built as part of a technical assignment for LeaseWeb. The goal was to create a filterable server listing API that helps customers quickly find and compare server specs and prices.

## 📋 The Challenge

The assignment required building a backend API in PHP/Symfony that:
- Ingests server data provided as an Excel spreadsheet (no database planned)
- Exposes a searchable, filterable API for server listings
- Supports filters for storage range, RAM, HDD type, and location
- Is unit and functionally tested
- Is maintainable and well-structured

## 🏗️ Technical Approach

### Data ingestion
The source data is an Excel file (`assets/LeaseWeb_servers_filters_assignment.csv`) with raw, denormalised server specs. Rather than hardcoding a DB schema, the data is parsed and normalised on import via a migration endpoint, making it straightforward to add new CSV files with the same structure.

### Architecture
The project follows a clean layered architecture:
- **Controllers** — thin HTTP layer, delegates to services
- **Services** — business logic, search and filter orchestration
- **Repositories** — data access layer, decoupled from controllers
- **Tests** — unit and functional coverage with PHPUnit

### Search & Filtering
The API supports combined filters:
- **Storage** — range slider (e.g. 250GB to 8TB)
- **RAM** — multi-select checkboxes (e.g. 4GB, 8GB, 24GB)
- **HDD type** — dropdown (SAS, SATA, SSD)
- **Location** — dropdown (multiple datacentre locations)

### Code Quality
- PHPCS (PHP CodeSniffer) for coding standards
- PHPMD (PHP Mess Detector) for static analysis
- PHPUnit for unit and functional tests
- Locust for load/performance testing

---

## 🚀 Getting Started

### Prerequisites
- Docker & Docker Compose

### Setup

Clone the repository into your docker4php container:

```bash
cd <docker4php folder path>
docker exec -it leaseWeb_php bash
git clone https://github.com/heldernunes/leaseWebChallenge.git .
composer install
exit
```

Add the local domain to your hosts file:

```bash
sudo vi /etc/hosts
```

Add this line:

```
127.0.0.1 php.docker.localhost
```

### Seed the database

```bash
curl --request GET 'http://php.docker.localhost:8000/migrate'
```

---

## 📡 API Usage

### Search servers

`GET /search`

All filter parameters are optional and can be combined.

**Filter by RAM and HDD type:**
```bash
curl --request GET 'http://php.docker.localhost:8000/search' \
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

**Filter by storage range:**
```bash
curl --request GET 'http://php.docker.localhost:8000/search' \
--header 'Content-Type: application/json' \
--data-raw '{
    "storage": [
        {
            "start": "250GB",
            "end": "8TB"
        }
    ],
    "ram": [],
    "hdd": "",
    "location": ""
}'
```

A Postman collection and environment are available in the [`documentation/`](./documentation) folder.

---

## 🧪 Tests & Quality

Run all tests and linters:

```bash
composer code-quality-check
```

Reports are generated in the `storage/` folder.

### Performance testing

Load testing is done with [Locust](https://locust.io/):

```bash
cd tests/performance/locust
locust -f locustfile.py
```

A pre-generated report is available at `tests/performance/locust/reports/leaseWeb_locust_report.html`.

---

## 📁 Project Structure

```
├── assets/          # Source data (CSV)
├── config/          # Symfony configuration
├── documentation/   # Postman collection & environment
├── public/          # Entry point
├── src/
│   ├── Controller/  # HTTP layer
│   ├── Service/     # Business logic
│   └── Repository/  # Data access
├── tests/
│   ├── Unit/        # Unit tests
│   ├── Functional/  # Functional tests
│   └── performance/ # Locust load tests
├── phpcs.xml.dist   # CodeSniffer config
├── phpmd.xml        # Mess Detector config
└── phpunit.xml      # PHPUnit config
```

---

## 📄 Assignment Brief

The original assignment brief is available in [`documentation/PROG-TechnicalAssignmentBackend.pdf`](./documentation/PROG-TechnicalAssignmentBackend.pdf).
