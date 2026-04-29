# DriveEase - Car Rental System

A complete car rental management system built with HTML, CSS, JavaScript, PHP, and MySQL.

## Features

### User Features
- User registration and login
- Browse available cars with filtering and search
- Book cars with date validation
- View booking history
- Cancel pending bookings

### Admin Features
- Admin dashboard with statistics
- Manage cars (add, edit, delete, upload images)
- Manage bookings (approve/reject)
- View and manage users

### Security Features
- Password hashing with password_hash()
- Prepared statements to prevent SQL injection
- Session-based authentication
- Input validation and sanitization

## Installation

1. **Database Setup**
   - Create a MySQL database named `car_rental`
   - Import the `database.sql` file to create tables and sample data

2. **Configuration**
   - Update database credentials in `config/database.php`
   - Make sure the `uploads/` directory is writable for image uploads

3. **Web Server**
   - Place the project in your web server's root directory
   - Make sure PHP and MySQL are installed and running

4. **Access**
   - Visit the site in your browser
   - Admin login: admin@driveease.com / admin123
   - Register new users or use the admin account

## File Structure

```
driveease/
├── config/
│   └── database.php          # Database configuration
├── auth/
│   ├── login.php            # User login
│   ├── register.php         # User registration
│   └── logout.php           # Logout
├── user/
│   ├── dashboard.php        # User dashboard
│   ├── book.php             # Car booking
│   └── cancel.php           # Cancel booking
├── admin/
│   ├── dashboard.php        # Admin dashboard
│   ├── cars.php             # Manage cars
│   ├── add_car.php          # Add new car
│   ├── edit_car.php         # Edit car
│   ├── delete_car.php       # Delete car
│   ├── bookings.php         # Manage bookings
│   ├── approve_booking.php  # Approve booking
│   ├── reject_booking.php   # Reject booking
│   ├── users.php            # Manage users
│   └── delete_user.php      # Delete user
├── assets/
│   ├── css/
│   │   └── style.css        # Main stylesheet
│   ├── js/
│   │   └── main.js          # JavaScript utilities
│   └── images/              # Static images
├── uploads/                 # Car images
├── index.php                # Home page
├── cars.php                 # Cars listing
├── database.sql             # Database schema
└── README.md                # This file
```

## Database Schema

### users
- id (Primary Key)
- name
- email (Unique)
- password (Hashed)
- role (user/admin)
- created_at

### cars
- id (Primary Key)
- name
- type
- price_per_day
- image
- availability
- created_at

### bookings
- id (Primary Key)
- user_id (Foreign Key)
- car_id (Foreign Key)
- pickup_date
- return_date
- total_price
- status (Pending/Approved/Cancelled)
- created_at

## Security Notes

- All database queries use prepared statements
- Passwords are hashed using PHP's password_hash()
- User input is sanitized
- Session management for authentication
- Admin role checking for protected pages

## Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 7+
- **Database**: MySQL
- **Security**: PDO, password_hash(), sessions

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## License

This project is for educational purposes. Feel free to modify and use as needed.