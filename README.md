# Symfony Test System with Fuzzy Logic

This project is a simple testing system built using Symfony and PostgreSQL. It supports fuzzy logic questions with multiple correct answer combinations. Users can take the test multiple times, and each test result will be saved in the database. After completing the test, users can see the list of questions they answered correctly and the ones they answered incorrectly.

## Features
- Fuzzy logic for questions with multiple possible correct answer combinations.
- Results are saved in the database after each test.
- Dockerized setup for easy deployment.

## Requirements
- Docker
- Docker Compose
- Symfony CLI (optional, for local development)

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/yourusername/test-system.git
    cd test-system
    ```

2. Start the project with Docker:

    ```bash
    docker compose up -d
    ```

3. Create database:

    ```bash
    docker compose exec app php bin/console doctrine:database:create
    ```

4. Run the database migrations:

    ```bash
    docker compose exec php bin/console doctrine:migrations:migrate
    ```
5. Access the application:

   Open your browser and go to `http://localhost:8080`.
   If port 8080 is already in use on you machine, navigate to [compose.override.yaml](compose.override.yaml) and change port from 8080 to something else.
   ```bash
    nginx:
      ports:
       - "8080:80"
    ```

## Usage

1. Start a new test.
2. Answer each question, choosing the correct or multiple correct answers based on the question.
3. Once you finish, you will see a summary of your results: questions you answered correctly and those you answered incorrectly and your score.
4. You can retake the test by clicking the "Retake Test" button.
5. Score for each question is based on answers given. You gain full score only if you chosen all right answers for the question.  

#### PS:
If you hate the design - it is okay, I hate it too.
