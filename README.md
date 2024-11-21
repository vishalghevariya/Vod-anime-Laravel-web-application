# Anime API Project

This project is designed to fetch and serve anime-related data from the [Jikan API](https://jikan.moe/). It provides an endpoint to retrieve detailed information about various anime using slugs.

---

## Prerequisites

Before getting started, ensure the following tools are installed and configured:

-   PHP: Install PHP (version 8.3).
-   Composer: Dependency manager for PHP.
-   Database: A database system such as MySQL or PostgreSQL.
-   Laravel: Ensure you have Laravel set up in your environment.
-   Environment File: Update the '.env' file with your database credentials and other required configurations.

---

## Setup Instructions

Follow the steps below to set up the project and start the application:

### Step 1: .env File Veriable Set

The project requires specific Jikan API for that set two ENV veriable in .env file

-   JIKAN_IMPORT_GENRES_API=https://api.jikan.moe/v4/genres/anime
-   JIKAN_IMPORT_TOP_ANIMES_API=https://api.jikan.moe/v4/top/anime

---

### Step 2: Create Database Tables

The project requires specific database tables to store anime and genre data. Run the following Artisan command to create the necessary tables:

-   php artisan migrate

This command will read the migration files and create tables in your database based on the structure defined in the migrations.

---

### Step 3: Import Data from Jikan API

After setting up the database, import anime genres and details from the Jikan API by running the following commands:

1. Import Genres  
   Run this command to fetch and store anime genres in your database:

-   php artisan jikan:import-genres

2. Import Anime Data  
   Run this command to fetch and store anime data:

-   php artisan jikan:import-animes

These commands use the Jikan API to populate your database with the necessary data for your application.

---

### Step 4: Start the Project

After importing the data, you can start the application using the built-in Laravel development server:

-   php artisan serve

This command will start the server at 'http://127.0.0.1:8000'. You can now access the API and other routes of your application.

---

## API Usage

### Fetch Anime Details by Slug

The project provides an endpoint to fetch detailed information about an anime using its slug.

-   Endpoint: '/api/anime/{animeSlug}'

-   Description: Replace '{animeSlug}' in the endpoint with the slug of the anime you want to fetch.

-   Example Slugs:

    -   'sousou-no-frieren'
    -   'one-piece'
    -   'odd-taxi'
    -   'natsume-yuujinchou-roku'

-   Example Request:  
    http
    GET /api/anime/sousou-no-frieren

-   Response: The API will return a JSON response containing details of the requested anime.

---

## Notes

-   Ensure your '.env' file is configured correctly before running any commands.
-   The imported data from Jikan API will be stored in your local database.
-   For advanced configurations or troubleshooting, refer to Laravel’s official documentation.

---
