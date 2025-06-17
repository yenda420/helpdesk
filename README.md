# Helpdesk Application

This is a web-based Helpdesk application developed collaboratively as a school project in Portugal under the Erasmus+ program for the company TechBase, and later continued as a final project for a 3rd-year web applications course. The application is built using HTML, CSS, PHP, and JavaScript, leveraging a MySQL database.

While initially developed with a procedural approach, the project was later refactored to an Object-Oriented Programming (OOP) paradigm. Despite its development history, the application remains clean, fully functional, and highly usable.

## Overview

The Helpdesk application provides a robust platform for users (clients) to submit complaints and problem requests. These requests are then automatically assigned to employees based on the linkage between the complaint category and the employee's department. Employees resolve these issues by engaging in a one-on-one conversation with the client who submitted the request.

## Key Features

* **Ticket Submission & Management:**
    * Clients can easily submit new complaints and requests.
    * Tickets have clear status tracking: `Waiting`, `Pending`, and `Resolved`.
* **Automated Ticket Assignment:**
    * Tickets are automatically assigned to relevant employees based on the department linked to the ticket's category.
* **Direct Client-Employee Communication:**
    * Employees can engage in direct, one-on-one conversations with clients to resolve issues.
* **User Role Management:**
    * The application supports distinct user roles with specific functionalities:
        * **Client:** Can submit tickets and communicate with assigned employees.
        * **Employee:** Receives assigned tickets and communicates with clients.
        * **Admin:** Manages users, departments, ticket types, and oversees the system.
        * **Super-Admin:** Holds the highest level of administrative privileges.
* **User Registration & Activation:**
    * Users register by providing an email and password.
    * Registrations require administrator approval to activate the user's account.
* **Intuitive User Interface:**
    * The UI is well-structured, utilizing a grid-based layout for consistent display of records across most pages.
* **Full-text Search Bar:**
    * A powerful search bar allows for full-text search across the entire system, including records within tickets and conversations.

## Technology Stack

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP (Mostly Object-Oriented)
* **Database:** MySQL

## Setup and Installation

To set up and run the Helpdesk application, follow these steps:

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/yenda420/helpdesk.git
    cd helpdesk
    ```
2.  **Web Server & PHP:**
    * Ensure you have a web server (e.g., Apache, Nginx) configured to serve PHP applications.
    * It is recommended to use the latest stable version of PHP.
3.  **Database Setup:**
    * Ensure a MySQL server is running.
    * Import the `helpdesk.sql` file into your MySQL database. This script contains the complete database schema and initial data (if any).
        ```bash
        mysql -u your_username -p your_database_name < sql/helpdesk.sql
        ```
        (Replace `your_username` and `your_database_name` accordingly.)
4.  **Configuration:**
    * Adjust database connection details within the PHP files (`src/classes/Database.php`) to match your MySQL setup.
5.  **Access the Application:**
    * Deploy the project files to your web server's document root.
    * Navigate to the application's URL in your web browser.

## Project Documentation

Detailed project documentation, including design specifications and an application manual (in Czech), is available within the `documentation` directory (`Helpdesk_dokumentace.docx`).

## Development Insights

This project provided valuable experience in web application development, team collaboration, and refactoring a procedural codebase to an OOP structure. While developed some time ago, it effectively showcases my coding progress and evolution since its creation. I would approach its architecture and implementation significantly differently today, yet the application stands as a testament to my foundational learning in building functional web services.
