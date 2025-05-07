# Stock Center Application

A full-stack application for managing stock items with a Symfony backend and Vue.js frontend.

## Project Structure

The project is divided into two main parts:

- `backend/` - Symfony PHP application that provides the API
- `frontend/` - Vue.js application that provides the user interface

## Features

- Add new items with name, price, and date/time
- Validate input data on both frontend and backend
- Display error messages for invalid inputs
- Show a list of all items sorted by date and time
- Responsive design

## Requirements

- Docker and Docker Compose

## Installation and Setup

1. Clone the repository
2. Navigate to the project root directory
3. Run the following command to start the application:

```bash
docker-compose up -d
```

This will build and start all the required services:
- Backend API (Symfony) - accessible at http://localhost:8000
- Frontend (Vue.js) - accessible at http://localhost:8080
- MySQL Database

## API Endpoints

- `GET /api/items` - Get all items sorted by date and time
- `POST /api/items` - Add a new item

## Development

### Backend (Symfony)

The backend is built with Symfony 6.3 and uses:
- Doctrine ORM for database interactions
- Symfony Validator for input validation
- Symfony Serializer for JSON responses

### Frontend (Vue.js)

The frontend is built with Vue.js 3 and uses:
- Axios for API requests
- Custom CSS for styling

## Docker Configuration

Each part of the application has its own Docker configuration:
- Backend: Dockerfile and docker-compose.yml in the `backend/` directory
- Frontend: Dockerfile and docker-compose.yml in the `frontend/` directory
- Root docker-compose.yml to run all services together
