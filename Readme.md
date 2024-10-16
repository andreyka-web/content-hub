# Content Hub

This project consists of a Vue.js frontend and a Laravel-based API for the backend. The setup is containerized using Docker and Docker Compose, making it easy to install and run in any environment without having to manually configure dependencies.

### Prerequisites
Before you start, ensure that you have the following installed on your system:

- Docker
- Docker Compose
- Git

1. Clone the project
```sh
git clone https://github.com/andreyka-web/content-hub.git
cd content-hub
```
2. Build and start containers
```sh
docker-compose up -d --build --force-recreate
```

## Front End
Fronten app will be available at [http://localhost:8000](http://localhost:8000) 
 
## API
Laravel API will be running at http://localhost:8080/



### post install
In hub-api container db migration has to be done after db container is ready. 

```sh
docker exec -it hub-api sh -c "php artisan migrate"
```