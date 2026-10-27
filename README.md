# Simple Event Booking System

A simple PHP and MySQL app for creating events and booking tickets. The booking total is always calculated on the server from the price stored in the database, never from a value sent by the browser.

## Features

- Create events with a name, description and price
- List available events in a dropdown
- Book tickets for an event (customer name, event, quantity)
- Validate the event ID and ticket quantity
- Confirm the event exists before saving a booking
- View all bookings with the event name, total and date

## Tech Stack

- PHP 8+ (PDO with prepared statements)
- MySQL / MariaDB
- Tailwind CSS (via CDN)

## Project Structure

```
event-booking/
├── database.php      # PDO database connection
├── event.php         # Event functions (list, find, create, validate)
├── booking.php       # Booking functions (validate, calculate, save, list)
├── new-event.php     # Page: create an event
├── new-booking.php   # Page: book tickets
├── bookings.php      # Page: list all bookings
└── README.md
```

## Database Setup

Run this in phpMyAdmin or the MySQL console:

```sql
CREATE DATABASE event_booking;
USE event_booking;

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    event_id INT NOT NULL,
    ticket_quantity INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id)
);
```

## Installation

1. Install [XAMPP](https://www.apachefriends.org/) (or any PHP + MySQL stack).
2. Copy the project folder into `C:\xampp\htdocs\`.
3. Start **Apache** and **MySQL** from the XAMPP control panel.
4. Create the database using the SQL above.
5. Check the credentials in `database.php` and change them if yours are different:

   ```php
   $host = "localhost";
   $dbname = "event_booking";
   $username = "root";
   $password = "";
   ```

## Usage

Open these pages in your browser:

| Page | URL |
|---|---|
| Create an event | http://localhost/event-booking/new-event.php |
| Book tickets | http://localhost/event-booking/new-booking.php |
| All bookings | http://localhost/event-booking/bookings.php |

Replace `event-booking` with your own folder name.

## How a Booking Is Processed

1. The form sends only the customer name, event ID and ticket quantity.
2. `validateBooking()` checks the name, event ID and quantity.
3. `findEvent()` confirms the event exists and returns its real price.
4. `calculateTotal()` multiplies the database price by the quantity.
5. The booking is saved and the page redirects, so refreshing doesn't create a duplicate.

## Security Notes

- All queries use PDO prepared statements.
- All output is escaped with `htmlspecialchars()`.
- Prices are never accepted from the browser.
- Database errors are logged, not shown to users.

## Possible Improvements

- Edit and delete events
- Cancel a booking
- Limit tickets by event capacity
- Admin login
