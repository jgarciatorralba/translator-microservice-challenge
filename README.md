# Code Challenge: Machine Translation Microservice

The repository consists on a proposed solution to a technical challenge raised during the selection process for a Backend Developer position.

---

## Content

This is a **Symfony** project for an **API** service, with a development environment configured in **Docker**.

## Installation

- Clone this repo: `git clone git@github.com:jgarciatorralba/translator-microservice-challenge.git`
- Navigate to the `/.docker` folder, then run `docker compose up -d` to download images and set up containers.
  - **Important**: the configuration is prepared to expose the server container's port on host's port 8000, the database container's port on host's 6432, and the message broker container's port on hosts' 5672 and 15672, so make sure they are available before running the above command.
- Once completed, open with VisualStudio and in the command palette (*"View > Command Palette"*) select the option *"Dev Containers: Reopen in Container"*.
- Inside the development container, install packages with `composer install`.
- Even though an empty database named **app_db** should have been created with the installation, you can still run `sf doctrine:database:create` for good measure.
- With the database created and the connection to the application successfully established, execute the existing migrations in folder `/etc/migrations` using the command `sf doctrine:migrations:migrate`.

---

## Tests

- Run the test suite by executing the command: `php bin/phpunit`
  - **Important**: make sure to clear Symfony's testing cache by running `sf cache:clear --env=test` before executing them.
  - Make sure the **test** database is created and ready by running the commands `sf doctrine:database:create --env=test` and `sf doctrine:migrations:migrate --env=test`.

---

## Scripts

- Run *PHPUnit* tests: `php bin/phpunit`
- Run *CodeSniffer* analysis: `php ./vendor/bin/phpcs <filename|foldername>`
  - Correct detected coding standard violations: `php ./vendor/bin/phpcbf <filename|foldername>`
- Run *PHPStan* analysis: `php ./vendor/bin/phpstan analyse <foldernames>`
- Delete existing database: `sf doctrine:database:drop --force`
- Run worker to consume messages queued in the message broker: `sf messenger:consume async -vv`

---

## Design Decisions

- In order to facilitate the development, only a handful of languages is supported: English, Spanish, French, German, Italian and Portuguese.
- Maximum of 255 characters to translate in each request, so the free tier limit is not reached too soon.
- Use of universally unique identifiers (UUIDs) for improved API security.
- Use of enumerations in translation status and in supported languages instead of plain text for better control of database input.

---

## Author

- **Jorge García Torralba** &#8594; [jorge-garcia](https://github.com/jgarciatorralba)
