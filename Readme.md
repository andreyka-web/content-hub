# Content Hub

This project consists of a Vue.js frontend and a Laravel-based API for the backend. The setup is containerized using Docker and Docker Compose, making it easy to install and run in any environment without having to manually configure dependencies.


## Data Flow:
- Users interact with the frontend components.
- The frontend makes API requests to the backend.
- The backend processes the requests, interacts with the database and file storage, and returns responses to the frontend.
- The frontend updates the UI based on the backend responses.

                      +-------------------------------------------------+
                      |                    Hub-API (Backend)            |
                      +-------------------------------------------------+
                      |   Laravel PHP Framework                         |
                      |   Database (MySQL)                              |
                      |   File Storage (e.g., Local, S3)                |
                      |   API Endpoints (RESTful)                       |
                      |       - /file (CRUD operations)                 |
                      |       - /category (CRUD Operations)             |  
                      |       - /user (Authentication, Authorization)   |
                      +---------------------+---------------------------+
                                            |
                                            | API Requests
                                            |
                      +---------------------v---------------------------+
                      |                    Hub-FE (Frontend)            |
                      +-------------------------------------------------+
                      |   Vue.js Framework                              |
                      |   Components:                                   |
                      |       - FileList (Displays files)               |
                      |       - FileUpload (Uploads files)              |
                      |       - FileEditForm (Edits file)               |
                      |       - CategoryList (Displays Folders)         |
                      |       - CategoryForm (Adds/Edit folder)         |
                      |   HTTP Client (fetch API)                       |
                      +---------------------+---------------------------+
                                            |
                                            | User Interaction
                                            |
                      +---------------------v---------------------------+
                      |                    Users                        |
                      +-------------------------------------------------+
                      |   Access via Web Browser                        |
                      |   Authenticated/Authorized Access               |
                      |   Interact with FileList, FileUpload, etc.      |
                      +-------------------------------------------------+


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
docker-compose up -d --build 
```

Hub url [http://localhost:8000](http://localhost:8000) 