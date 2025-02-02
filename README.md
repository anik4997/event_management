# Event Management System

A fully functional **Event Management System** built with **PHP, MySQL, jQuery, and Bootstrap 5**. This system allows users to **register, manage events, handle attendees, and generate reports** with **role-based access control (Admin & User)**.

## 📌 Features

### 🔐 User Authentication
- **User Registration**: Users can sign up with **Full Name, Phone No, Email, Password, and Retype Password**.
- **Hashed Password Storage**: Passwords are stored securely in the database using **bcrypt hashing**.
- **Login with Email & Password**: Users can log in after passing **Google reCAPTCHA** to prevent brute force attacks.
- **Prevent Multiple Accounts**: Same **Email** or **Phone Number** cannot be used for multiple accounts.
- **AJAX-based Authentication**: Registration and Login are handled via **AJAX requests**.

### 🏆 Role-Based Access Control
- **Admin Privileges**:
  - Access to **Event Dashboard**.
  - **CRUD (Create, Read, Update, Delete)** operations on **Events & Attendees**.
  - **Download Reports** (All events, all attendees, specific event attendees).
  - **Custom Search, Sorting, Pagination, and Filtering** on the Event List.
- **Normal User**:
  - Can **register for events** (but cannot create/edit/delete events).
  - Cannot access admin-specific features like **report downloads or event management**.

### 📅 Event Management
- **Event List with Search, Sort & Filter**:
  - Events are displayed with details: **Event Name, Place, Description, Date, Attendees Count, Actions**.
  - Searchable, sortable (ascending/descending), and paginated.
- **Event Registration**:
  - Users can register for an event only once.
  - If already registered, **"Already Registered"** will be shown.
  - If maximum capacity is reached, **"Max Capacity Exceeded"** will be shown.
- **Admin Controls**:
  - **Add Event**: Admins can create events with validation.
  - **Edit Event**: Update event details with restrictions (max capacity cannot be lower than current attendees).
  - **Delete Event**: Secure delete operation with confirmation.
  - **Download CSV Reports**:
    - **All Events**
    - **All Attendees**
    - **Attendees of a Specific Event**
  - **Security Restrictions**:
    - Only admins can see and download reports.
    - Backend validation prevents unauthorized downloads.

### 📊 Reports & API
- **CSV Reports**:
  - Download event details and attendee lists in CSV format.
  - Admin-only access with backend validation.
- **Public API Endpoint**:
  - Fetch event details programmatically: http://your-server-url/event_api_json.php?event_id=EVENT_ID

  - Replace `EVENT_ID` with the event number (e.g., `1`, `2`, `3`).

---

## 🛠 Installation Guide (XAMPP)

1. **Download & Install XAMPP** (https://www.apachefriends.org/index.html).
2. **Clone the Repository**:
 ```sh
 git clone https://github.com/anik4997/event-management.git

mv event-management C:\xampp\htdocs\

Import the Database:
Open phpMyAdmin (http://localhost/phpmyadmin).
Create a database named event-management.
Import database/event_management.sql.
Configure Database Connection:
Open classes/database.php and update database credentials:

private $host = "localhost";
private $user = "root"; // Change if needed
private $password = ""; // Change if needed
private $database = "event-management";

Start XAMPP:
Start Apache & MySQL from the XAMPP Control Panel.
Run the Application:
Open browser and go to: http://localhost/event-management/

Project Structure

/event-management
│-- /classes
│   ├── AddEvent.php
│   ├── AttendeeRegistration.php
│   ├── database.php
│   ├── DeleteEvent.php
│   ├── FetchEvent.php
│   ├── FetchUser.php
│   ├── Login.php
│   ├── Search.php
│   ├── UpdateEvent.php
│   ├── UserRegistration.php
│-- /database
│   ├── event_management.sql
│-- /dist
│-- /plugins
│-- /styles
│   ├── style.css
│-- /views
│   ├── add_user.php
│   ├── addevent_ajax.php
│   ├── attendee_registration_ajax.php
│   ├── delete_event.php
│   ├── download_attendee_report.php
│   ├── download_report.php
│   ├── download_specific_attendee_report.php
│   ├── editevent_ajax.php
│   ├── event_api_json.php
│   ├── event_list.php
│   ├── event_list_guest.php
│   ├── index.php
│   ├── login_ajax.php
│   ├── logout.php
│   ├── README.md
│   ├── singleton.php
│   ├── user_registration_ajax.php


## 🔒 Security Measures

- **Google reCAPTCHA**: Prevents brute force attacks on login.  
- **Prepared Statements**: Prevents SQL injection.  
- **AJAX Requests**: Securely handles all user interactions.  
- **Client-Side & Server-Side Validation**:  
  - Ensures all required fields are filled.  
  - Prevents bypassing validation via developer tools.  
- **Unauthorized Access Prevention**:  
  - Normal users **cannot** access admin features.  
  - Even if buttons are manually enabled, backend validation blocks unauthorized actions.  

---

## 💡 Future Enhancements

- **Multiple User Roles**: Introduce event managers with restricted admin access.  

---

## 💻 Tech Stack

- **PHP** (v8.2.12)  
- **MariaDB** (10.4.32)  
- **jQuery** (3.6.0)  
- **Bootstrap 5**  
- **AJAX**  
- **Database is properly formatted for specific fields**


Contact
For any queries, reach out via 'oliahammed02@gmail.com' or open an issue in the repository.


---

This README is fully formatted for GitHub and provides a **clear, structured** overview of your project! 🚀 Let me know if you need modifications. 😊
