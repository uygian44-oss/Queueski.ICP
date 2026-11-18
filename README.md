# QUEUESKI — SYSTEM STUDY NOTES

## 1. What is QUEUESKI?

QUEUESKI is a student queue management system designed to organize student service requests and help school offices manage their queues.

Its main purpose is to make request processing more organized and allow authorized personnel to track the progress of student transactions.

## 2. Technologies Used

- **HTML** — Structures the web pages.
- **CSS** — Designs the system's appearance.
- **JavaScript** — Handles buttons, search, filters, dynamic tables, and API requests.
- **PHP** — Processes requests and communicates with the database.
- **MySQL** — Stores student accounts, enrollment records, queue tickets, and service-request information.
- **PDO** — Allows PHP to communicate with MySQL using prepared statements.
- **XAMPP** — Provides the local Apache and MySQL environment.
- **PHPMailer** — Supports email notifications.

## 3. Main System Features

### Admin Dashboard
Allows authorized administrators to view dashboard information, student accounts, and queue requests, and access the Accounting and Registrar sections.

### Student Accounts
Displays student information, including Student ID, name, grade level, section, email, and account creation date.

The planned Student Details feature will allow the Admin to click a Student ID to view the selected student's account and enrollment history.

### Queue Management
Allows the Admin to manage tickets using actions such as starting, completing, or skipping a ticket, subject to the implemented status rules.

### Accounting
Provides the interface for Accounting-related student transactions and requests. The exact completed functions should be confirmed using the final Accounting code.

### Registrar
Handles Registrar-related service requests. Completing a linked Registrar queue ticket updates the associated Registrar request through the documented Admin API workflow.

### Email Notifications
The system includes code that attempts to email a student when a relevant Registrar request is completed. Successful delivery depends on the mail configuration and must be tested.

### Theme and Settings
The Admin interface includes theme handling and shared settings-related functionality.

## 4. Important Source Files

| File | Purpose |
|---|---|
| `admin.html` | Structure of the Admin dashboard. |
| `js/admin.js` | Admin dashboard behavior, ticket loading, filters, counters, and queue actions. |
| `js/common.js` | Shared frontend functionality. |
| `js/accounting.js` | Accounting interface behavior. |
| `config/database.php` | Database connection configuration. |
| `api/admin.php` | Loads queue tickets and processes Admin queue actions. |
| `api/admin_students.php` | Retrieves student records for the Admin list. |
| `api/admin_student_details.php` | Intended to retrieve one student's details and enrollment history. |
| `student_profile.php` | Retrieves the authenticated student's profile and enrollment history. |
| `api/session.php` | Used by the Accounting interface for session-related information. |
| `api/accounting.php` | Used by the Accounting interface for Accounting-related operations. |

## 5. Database Tables

### `students`
Stores student account information, including Student ID, name, email, grade level, section, and password hash.

### `student_enrollments`
Stores enrollment records, including grade level, school year, semester, section, and strand. Keeping enrollment records separate allows the system to preserve a student's enrollment history.

### `queue_tickets`
Stores queue-ticket information, including the office, queue number, status, and relevant timestamps.

### `registrar_requests`
Stores Registrar service-request information, including its status and completion-related details.

Other Accounting-related tables and their exact structures should be reviewed in the actual database.

## 6. How the System Works

1. The user opens a page in the browser.
2. HTML and CSS display the interface.
3. JavaScript handles user interactions.
4. JavaScript sends a request to a PHP API.
5. PHP checks permissions and processes the request.
6. PHP uses PDO to retrieve or update MySQL records.
7. PHP returns a JSON response.
8. JavaScript displays the resulting information.

## 7. Queue Ticket Statuses

| Database status | Displayed status |
|---|---|
| `waiting` | Pending |
| `preparing` | In Progress |
| `serving` | In Progress |
| `completed` | Completed |
| `cancelled` | Skipped |

The displayed status is a user-friendly label. The backend uses the database status values to determine which actions are permitted.

## 8. Important Programming Concepts

**API:** Allows the frontend and backend to communicate.

**Database:** Stores information so that records can persist between page visits.

**PDO prepared statements:** Help protect SQL queries against SQL injection when used correctly.

**Session:** Maintains information about a user's authenticated session.

**Authentication:** Checks who is logged in.

**Authorization:** Checks what the logged-in user is allowed to access or do.

**JSON:** A format used to exchange data between JavaScript and PHP.

**`localStorage`:** Stores information in the browser, such as the selected theme. It is not the same as storing information in MySQL.

## 9. Important Things to Remember

- A queue ticket and a service request are related but different records.
- Completing a queue ticket does not automatically mean a student has paid.
- Password hashes must never be exposed to the browser.
- Protected PHP APIs must verify the user's session and role.
- Student details and enrollment history should come from the correct database records.
- Email notifications require proper mail configuration.
- A feature should only be described as completed after it has been implemented and tested.

## 10. What Every Member Should Understand

Every member should be able to explain:

1. What QUEUESKI is and what problem it addresses.
2. What the main system features do.
3. What HTML, CSS, JavaScript, PHP, and MySQL do in the project.
4. How the frontend communicates with the backend.
5. What the important database tables store.
6. How the queue-ticket statuses work.
7. How the Admin, Accounting, and Registrar sections relate to the system.
8. Which features are implemented and which are still being developed.

**Reminder:** These notes describe the documented project structure and behavior. Review the final source code to confirm the status of features that are still being developed.
