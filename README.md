### Installation Instructions

> Requirements
> - Install [Docker Desktop](https://www.docker.com/products/docker-desktop/) which (includes both Docker Engine and Docker Compose)

OR

> - Install [Docker Engine](https://docs.docker.com/engine/install/) and [Docker Compose](https://docs.docker.com/compose/install/) separately.

**Step 1:** Clone the repository in your terminal using `https://github.com/victorive/user-service.git`

**Step 2:** Navigate to the project’s directory using `cd user-service`

**Step 3:** Run `cp .env.example .env` to create the .env file for the project’s configuration.

**Step 4:** Docker uses the values provided via environment variables to build and create the database.
Configure the `DATABASE_URL` and `MESSENGER_TRANSPORT_DSN` credentials in the `.env` files located in the
project’s root folder For example:

> DATABASE_URL = "mysql://root:root@mysql:3306/user-service?serverVersion=8.0.32&charset=utf8mb4"
>
> MESSENGER_TRANSPORT_DSN = amqp://user:password@rabbitmq:15672/%2f/messages
>

**Step 5:** Run `docker-compose up --build -d` to build and start your containers.

**Step 6:** Then visit `localhost:8321` to access the user-service API and `localhost:8322` to access the
notification-service API.

**Step 7:** Then run `docker logs -f notification-service` to access the notification-service logs.

