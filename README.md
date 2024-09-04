# Laravel SPA Comments with Vue.js
This project is a Single Page Application (SPA) built with Laravel and Vue.js, allowing users to post and manage comments with file uploads, user identification, and real-time updates. It is designed to be scalable, secure, and user-friendly, utilizing modern web development tools and practices.

## Features
User Authentication: Secure user identification.
Hierarchical Comments: Supports nested comments.
File Uploads: Allows users to upload images and text files.
Real-time Updates: Utilizes WebSockets for instant comment updates.
XSS & SQL Injection Protection: Ensures data security with thorough validation.
ElasticSearch Integration: Efficient search and filtering of comments.
Dockerized Environment: Easily set up and deploy the application using Docker.
Responsive Design: Simple and responsive UI with Tailwind CSS.
Asynchronous Processing: Background tasks handled via RabbitMQ.
Event-Driven Architecture: Leverages Laravel Events and Listeners.
GraphQL Integration: API endpoints with GraphQL support.
Requirements
* PHP 8.1+
* Laravel 10
* Vue.js 3
* MySQL 8
* Node.js 18+
* Docker & Docker Compose
* RabbitMQ
* ElasticSearch
* Redis
# Installation
1. Clone the Repository
```
bash
git clone https://github.com/yourusername/laravel-spa-comments.git
cd laravel-spa-comments
```
2. Set Up Environment
# Copy the .env.example file to .env and update the environment variables as needed:
```
cp .env.example .env
```
3. Install Dependencies
bash
Копировать код
composer install
npm install
npm run dev
4. Set Up Docker
Ensure Docker and Docker Compose are installed, then start the containers:

```
docker-compose up -d
```
5. Generate Application Key
```
php artisan key:generate
```
6. Run Migrations
```
php artisan migrate --seed
```
7. Set Up Frontend
Build the frontend assets:
```
npm run build
```
8. Run the Application
You can now run the application:

```
php artisan serve
```
### Usage
Post a Comment: Users can post comments on the main page.
Reply to Comments: Users can reply to existing comments.
Search Comments: Use the search bar to find comments.
File Uploads: Attach images (max 320x240 px) or text files (max 100 KB) to comments.
Real-time Updates: Comments will update in real time as others post.
Technologies Used
Backend: Laravel 10, MySQL, Redis, RabbitMQ, ElasticSearch, GraphQL
Frontend: Vue.js 3, Tailwind CSS, Axios, Pusher (WebSockets)
Development Tools: Docker, Composer, NPM, Git, Webpack/Vite
Deployment
Follow these steps to deploy the application:

Build the Docker Images:

```
docker-compose -f docker-compose.prod.yml build
```
Run the Containers:

```
docker-compose -f docker-compose.prod.yml up -d
```
Migrate the Database:
```
docker-compose exec app php artisan migrate --force
```
Cache Configuration:

```
docker-compose exec app php artisan config:cache
```
Queue Workers:

Ensure that RabbitMQ is running and start the queue workers:

```
docker-compose exec app php artisan queue:work
```
Contributing
If you'd like to contribute to this project, please fork the repository and submit a pull request. Contributions are welcome and encouraged!
