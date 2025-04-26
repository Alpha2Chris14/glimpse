# Glimpse 33 Media - Scheduling System API

This project is a simple Event and Participant management API built with Laravel.  
It supports event creation, participant registration, and soft-deletion features.

---

## Setup Instructions

1. **Clone the repository**
   git clone https://github.com/Alpha2Chris14/glimpse.git
   cd glimpse

2. **Open Laragon** and ensure Apache, MySQL are running.

3. **Configure `.env`**

-   Copy `.env.example` to `.env`
-   Set up database credentials:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=glimpse
    DB_USERNAME=root
    DB_PASSWORD=
    ```

-   Set up mailtrap or your mail provider credentials:
    ```
    MAIL_MAILER=smtp
    MAIL_HOST=sandbox.smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=##############
    MAIL_PASSWORD=##############
    MAIL_SCHEME=null
    MAIL_FROM_ADDRESS="admin@glimps.com"
    MAIL_FROM_NAME="${APP_NAME}"
    ```

4. **Install dependencies**
   composer install

5. **Generate application key**
   php artisan key:generate

6. **Run migrations**
   php artisan migrate

7. **Serve the application**
   php artisan serve

    API will be available at `http://127.0.0.1:8000/api` or since we are using laragon
    you can access the url using `http://glimpse.test/` make sure you see the default
    laravel sample welcome page

---

## Assumptions Made

-   A participant can register for multiple events.
-   An event has a maximum number of participants (`max_participants`) enforced manually during registration.
-   Events and participants support **soft delete** and **force delete**.
-   No authentication is required for now (public API).
-   Participant registration assumes the participant already exists.
-   Registration is done by attaching participants to events using a pivot table (`event_participant`).

---

## API Routes and Usage

### Event Routes

Remember this endpoint should be prefix with the url in my case `http://glimpse.test/`

| Method | Endpoint                           | Description                              |
| :----- | :--------------------------------- | :--------------------------------------- |
| GET    | `/api/events/`                     | List all events with available slots.    |
| POST   | `/api/events/`                     | Create a new event.                      |
| DELETE | `/api/events/{id}`                 | Soft delete an event.                    |
| POST   | `/api/events/{id}/restore`         | Restore a soft-deleted event.            |
| DELETE | `/api/events/{id}/force`           | Permanently delete an event.             |
| GET    | `/api/events/trashed`              | List all soft-deleted events.            |
| GET    | `/api/events/{event}/participants` | List all participants for a given event. |

### Participant Routes

| Method | Endpoint                              | Description                         |
| :----- | :------------------------------------ | :---------------------------------- |
| POST   | `/api/participants/`                  | Create a participant.               |
| POST   | `/api/participants/register`          | Register a participant to an event. |
| DELETE | `/api/participants/{id}`              | Soft delete a participant.          |
| GET    | `/api/participants/trashed/list`      | List all soft-deleted participants. |
| POST   | `/api/participants/{id}/restore`      | Restore a soft-deleted participant. |
| DELETE | `/api/participants/force-delete/{id}` | Permanently delete a participant.   |

---

## Sample API Data

### Create Event

**POST** `/api/events/`

```json
{
    "name": "Tech Conference 2025",
    "start_time": "2025-06-01 09:00:00",
    "end_time": "2025-06-01 17:00:00",
    "max_participants": 100
}
```

### Create Participant

**POST** `/api/participants/`

```json
{
    "name": "John Doe",
    "email": "john@example.com"
}
```

### Register Participant to Event

**POST** `/api/participants/register`

```json
{
    "participant_id": 1,
    "event_id": 1
}
```

### Testing Instructions

**Use Postman or any API tool.**

**Set Content-Type: application/json for all requests.**

**Follow the sample data examples to test creating, registering, soft deleting, restoring, and force deleting.**

**Notes**
Soft deletes are implemented using Laravel's SoftDeletes trait.

The pivot table event_participant automatically records participant registrations.

Error responses include appropriate status codes (404 for not found, 422 for validation errors, etc.)

In addition i have also attached the postman collection

If you still don't understand feel free to reach out
