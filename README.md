# Helpdesk Application

This web-based Helpdesk app started as a collaborative Erasmus+ school project in Portugal and later became my final 3rd-year web dev assignment (back in 2024). We originally wrote it using procedural PHP but eventually refactored the entire codebase into an Object-Oriented (OOP) architecture. It’s a great snapshot of my transition into cleaner, more structured programming.

**Tech Stack:** HTML, CSS, JavaScript, PHP (OOP), and MySQL.

## Key Features

* **Smart Ticket Assignment:** Clients submit support tickets, which are automatically routed to the right employee based on the issue category and department.
* **Direct Communication:** Features a built-in 1-on-1 chat system so employees and clients can talk directly to resolve issues.
* **Role Management:** Four distinct user tiers (Client, Employee, Admin, Super-Admin). For security, all new account registrations require manual admin approval.
* **Status Tracking:** Clear and simple ticket lifecycles (`Waiting`, `Pending`, and `Resolved`).
* **Global Search:** A powerful full-text search bar that can query everything from ticket metadata to conversation histories.
