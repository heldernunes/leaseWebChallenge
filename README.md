# leaseWebChallenge

get mariaDb ip address:
```
docker inspect leaseWeb_mariadb | grep IPAddress
```

get API ip address for Integration test:
```
docker inspect leaseWeb_nginx | grep IPAddress
```