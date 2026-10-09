# 🚗 CarConnect — Premier Digital Automotive Marketplace

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Apache](https://img.shields.io/badge/Apache-XAMPP-D22128?style=for-the-badge&logo=apache&logoColor=white)](https://www.apachefriends.org/)
[![PHPMailer](https://img.shields.io/badge/PHPMailer-OTP%20Auth-blue?style=for-the-badge)](https://github.com/PHPMailer/PHPMailer)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE)

**CarConnect** is a full-featured, secure, and modern web application engineered to transform how used and luxury vehicles are bought and sold. Designed to eliminate expensive dealer markups and untrustworthy middlemen, the platform establishes a transparent, direct peer-to-peer automotive trading network between buyers and sellers, safeguarded by centralized administrator oversight.

The system features a luxury-tier, responsive user interface inspired by modern supercar marques (dark aesthetic with champagne gold accents), end-to-end multi-role access control, automated email OTP authentication, real-time AJAX buyer-seller negotiations, and simulated checkout flows.

---

## 📌 Table of Contents

- [Key Highlights](#-key-highlights)
- [System Architecture & Modules](#-system-architecture--modules)
  - [1. Authentication & Security Engine](#1-authentication--security-engine)
  - [2. Buyer Experience](#2-buyer-experience)
  - [3. Seller Portal](#3-seller-portal)
  - [4. Administrative Command Center](#4-administrative-command-center)
- [Database Schema & Data Models](#-database-schema--data-models)
- [Project Directory Structure](#-project-directory-structure)
- [Getting Started & Installation](#-getting-started--installation)
  - [Prerequisites](#prerequisites)
  - [Step 1: Clone the Repository](#step-1-clone-the-repository)
  - [Step 2: Database Setup](#step-2-database-setup)
  - [Step 3: Email OTP Configuration (SMTP)](#step-3-email-otp-configuration-smtp)
  - [Step 4: Launch via XAMPP](#step-4-launch-via-xampp)
- [Demo Credentials](#-demo-credentials)
- [Security Implementations](#-security-implementations)
- [Future Roadmap](#-future-roadmap)
- [Contributing & License](#-contributing--license)

---

## 🌟 Key Highlights

* **Automotive Luxury Aesthetics:** Crafted with dark titanium backgrounds, gold linear gradients, refined glassmorphic cards, and custom SVG emblems with Lamborghini-inspired typography (*Barlow Condensed* and *Montserrat*).
* **Two-Factor Email OTP Registration:** Secures user sign-ups using timed 6-digit one-time passwords powered by PHPMailer over TLS/SMTP with retry backoff and brute-force safeguards.
* **Three-Tier Role Management:** Dedicated workflows, navigation controls, and session authorization for **Admins**, **Buyers**, and **Sellers**.
* **Listing Moderation Pipeline:** Vehicles submitted by sellers undergo administrative verification (Pending, Approved, Rejected) before going live to public inventory.
* **Direct Buyer–Seller Communication:** Integrated messaging system enabling prospective buyers to chat directly with verified sellers tied to specific car listings.
* **Full-Featured Filtering Engine:** Filter hundreds of listings by Make, Model, Body Style, Transmission, Fuel Type, Price Range, Mileage, and City Location.
* **Interactive Checkout & Invoice Flow:** Simulated multi-channel payment gateway (UPI, Credit/Debit Card, Net Banking) generating completed order records.

---

## ⚙️ System Architecture & Modules

```
                      ┌───────────────────────────────┐
                      │          CarConnect           │
                      │     Web Platform (Apache)     │
                      └──────────────┬────────────────┘
                                     │
         ┌───────────────────────────┼───────────────────────────┐
         ▼                           ▼                           ▼
┌───────────────────┐       ┌───────────────────┐       ┌───────────────────┐
│   Buyer Portal    │       │   Seller Portal   │       │   Admin Center    │
│  - Multi-Filter   │       │  - Post Listings  │       │  - Approve / Ban  │
│  - Wishlist       │       │  - Image Manager  │       │  - User Control   │
│  - In-App Chat    │       │  - Chat Inbox     │       │  - Orders & Pay   │
│  - Mock Checkout  │       │  - Sales Tracking │       │  - Analytics Dash │
│  - Reviews/Stars  │       │  - Review Stats   │       │  - Issue Reports  │
└─────────┬─────────┘       └─────────┬─────────┘       └─────────┬─────────┘
          │                           │                           │
          └───────────────────────────┼───────────────────────────┘
                                     │
                      ┌──────────────▼────────────────┐
                      │      MySQL Relational DB      │
                      │   (12 Normalized Tables)      │
                      └───────────────────────────────┘
```

### 1. Authentication & Security Engine
* **Registration with OTP Verification:** New buyers and sellers register with valid emails. A time-limited 6-digit code is dispatched to the user's inbox with countdown timer and resend restrictions.
* **Hashed Credentials:** All passwords are encrypted with PHP's `password_hash()` utilizing the Bcrypt algorithm (`PASSWORD_DEFAULT`).
* **Session Authorization Middleware:** Role-guarded route middleware (`core/middleware.php`) prevents privilege escalation and unauthorized access across portals.

### 2. Buyer Experience
* **Dynamic Inventory Search:** Filter vehicles in real-time across multiple parameters (Budget slider, Brands, Automatic/Manual, Petrol/Diesel/EV, Year, and Location).
* **Detailed Vehicle Dossier:** Inspect vehicle specifications (mileage, body type, seating capacity, owner history, condition report, and multiple photo views).
* **Live In-App Chat:** Instant messaging with car owners to discuss vehicle history, negotiate prices, or arrange physical test drives.
* **Curated Wishlist:** Save prospective cars to a personalized wishlist for comparison.
* **Simulated Checkout:** Select simulated payment options (UPI / Cards / Net Banking) to generate verified orders.
* **Customer Reviews & Ratings:** Submit verified 1-to-5 star ratings and written reviews after completed purchases.

### 3. Seller Portal
* **Listing Studio:** Publish car listings with detailed specifications, descriptions, pricing, color, seating capacity, and multi-file high-resolution vehicle photography.
* **Inventory Dashboard:** Monitor listings by status: `Pending Approval`, `Approved / Active`, `Rejected`, and `Sold`.
* **Lead Inquiries:** Communicate directly with interested buyers via the dedicated messages interface.
* **Transaction History:** Review sales figures, buyer details, and order timestamps.

### 4. Administrative Command Center
* **Analytical Dashboard:** High-level overview of total vehicle listings, active buyers, verified sellers, gross platform transactions, and pending reviews.
* **Listing Verification:** Inspect submitted car listings and approve or reject them with feedback to maintain inventory quality.
* **User Governance:** Inspect registered accounts; suspend or reactivate buyers and sellers violating terms of service.
* **Financial & Order Auditing:** Audit platform transactions, order statuses, and payment modes.
* **Inquiry & Report Handling:** View submitted customer complaints, bug reports, and contact inquiries.

---

## 🗄 Database Schema & Data Models

The relational database (`online_car_connect`) contains 12 normalized tables with foreign-key constraints:

| Table Name | Description | Key Attributes |
| :--- | :--- | :--- |
| `admins` | Platform administrators | `id`, `name`, `email`, `password`, `created_at` |
| `buyers` | Registered vehicle buyers | `id`, `name`, `email`, `phone`, `status`, `created_at` |
| `sellers` | Registered vehicle sellers | `id`, `name`, `email`, `phone`, `status`, `created_at` |
| `car_listings` | Primary vehicle inventory | `id`, `seller_id`, `make`, `model`, `year`, `price`, `mileage`, `fuel_type`, `transmission`, `image_path`, `status`, `location` |
| `car_categories`| Body types & vehicle segments | `id`, `name`, `created_at` |
| `car_views` | Traffic & view analytics | `id`, `car_id`, `buyer_id`, `viewed_at` |
| `wishlist` | Saved cars per buyer | `id`, `buyer_id`, `car_id` |
| `messages` | Buyer–seller chat exchanges | `id`, `car_id`, `sender_id`, `receiver_id`, `message`, `created_at` |
| `orders` | Vehicle purchase agreements | `id`, `buyer_id`, `seller_id`, `car_id`, `total_price`, `order_status`, `created_at` |
| `payments` | Transaction logs & payment records| `id`, `order_id`, `buyer_id`, `amount`, `payment_method`, `payment_status`, `created_at` |
| `reviews` | 1–5 star ratings & feedback | `id`, `car_id`, `user_id`, `rating`, `comment`, `created_at` |
| `reports` | Inquiries & incident reports | `id`, `user_id`, `issue`, `created_at` |

---

## 📁 Project Directory Structure

```plaintext
carconnect/
├── admin/                      # Administrative portal
│   ├── add_car.php             # Admin direct vehicle listing
│   ├── admin_dashboard.php     # Metrics, stats, and moderation queue
│   ├── car_details.php         # Admin vehicle review screen
│   ├── manage_cars.php         # Approve, reject, and delete vehicles
│   ├── manage_categories.php   # Vehicle category configuration
│   ├── manage_orders.php       # Platform order monitor
│   ├── manage_payments.php     # Payment logs
│   ├── manage_reviews.php      # User review moderation
│   ├── manage_users.php        # Buyer & seller account management
│   ├── view_contacts.php       # Contact form submissions
│   ├── view_messages.php       # System-wide communication audit
│   └── view_reports.php        # User dispute and issue reports
├── assets/                     # Front-end static assets
│   ├── css/
│   │   ├── style.css           # Luxury dark/gold design system
│   │   └── responsive.css      # Tablet & mobile layout adaptations
│   ├── js/
│   │   ├── main.js             # Navigation & UI interactions
│   │   └── validation.js       # Client-side form validation
│   └── images/                 # Brand emblems, badges, and banners
├── auth/                       # Authentication subsystem
│   ├── login.php               # Unified login for Admin, Buyer, Seller
│   ├── logout.php              # Session destruction & redirect
│   ├── register.php            # User registration with email validation
│   ├── resend_otp.php          # Timed OTP regeneration handler
│   └── verify_otp.php          # 6-digit OTP verification screen
├── buyer/                      # Buyer experience
│   ├── add_review.php          # Vehicle rating & feedback form
│   ├── car_details.php         # Vehicle gallery, specs & seller contact
│   ├── car_listings.php        # Search and browse directory
│   ├── chat.php                # Live AJAX chat with car seller
│   ├── checkout.php            # Simulated order completion & billing
│   ├── home.php                # Buyer personal dashboard
│   ├── messages.php            # Active conversation threads
│   ├── my_reviews.php          # Buyer submitted review history
│   ├── order_history.php       # Completed and pending vehicle orders
│   └── wishlist.php            # Saved bookmarks
├── config/                     # Configuration files
│   ├── constants.php           # Global application constants & paths
│   ├── database.php            # Database connection credentials
│   ├── mail_config.php         # SMTP credentials (git-safe)
│   └── mail_config.example.php # Reusable SMTP configuration template
├── core/                       # Core application utilities
│   └── middleware.php          # Session guard and RBAC route protectors
├── includes/                   # Reusable components & helpers
│   ├── car_image_helpers.php   # Image path resolver & fallbacks
│   ├── db_connect.php          # MySQLi initialization & charset setup
│   ├── fetch_conversations.php # AJAX endpoint for conversation list
│   ├── fetch_messages.php      # AJAX endpoint for live chat polling
│   ├── footer.php              # Modular global footer
│   ├── functions.php           # Sanitization and helper utilities
│   ├── header.php              # Global navigation bar & role badges
│   ├── otp_helpers.php         # OTP generation & validation routines
│   └── otp_mailer.php          # PHPMailer SMTP email dispatch
├── seller/                     # Seller workspace
│   ├── add_car.php             # Comprehensive vehicle submission form
│   ├── car_details.php         # Seller car preview screen
│   ├── chat.php                # Seller chat screen with buyers
│   ├── edit_car.php            # Edit pricing, details & availability
│   ├── manage_listings.php     # Inventory status tracker
│   ├── seller_dashboard.php    # Performance metrics & sales summary
│   ├── view_orders.php         # Received buyer orders
│   └── view_reviews.php        # Feedback received across inventory
├── uploads/                    # User-uploaded car photography
├── about.php                   # Platform mission and feature overview
├── cars.php                    # Public vehicle inventory catalog
├── composer.json               # Composer package definitions (PHPMailer)
├── contact.php                 # Customer contact & inquiry form
├── index.php                   # Homepage with hero slider & featured cars
└── online_car_connect.sql      # Complete sanitized MySQL database dump
```

---

## 🚀 Getting Started & Installation

Follow these steps to set up and run CarConnect locally on your workstation.

### Prerequisites
* **[XAMPP](https://www.apachefriends.org/)** (PHP 8.1+ and MySQL / MariaDB)
* **Web Browser** (Google Chrome, Mozilla Firefox, or Microsoft Edge)
* **Git** installed on your machine
* *(Optional)* **Composer** to manage PHP dependencies

---

### Step 1: Clone the Repository
Clone the project into your local XAMPP `htdocs` directory:

```bash
cd C:\xampp\htdocs
git clone https://github.com/aayushshetty15/CarConnect.git carconnect
```

---

### Step 2: Database Setup
1. Launch **XAMPP Control Panel** and start both **Apache** and **MySQL**.
2. Open your web browser and navigate to **[http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)**.
3. Create a new database named:
   ```sql
   online_car_connect
   ```
4. Select the `online_car_connect` database, click the **Import** tab at the top.
5. Choose the SQL file located at:
   ```
   C:\xampp\htdocs\carconnect\online_car_connect.sql
   ```
6. Click **Import** at the bottom to build all tables and seed sample data.

---

### Step 3: Email OTP Configuration (SMTP)
CarConnect uses Gmail SMTP via PHPMailer to deliver registration OTPs.

1. Navigate to `config/mail_config.php`.
2. Configure your Google Account with an **App Password** (requires Google 2-Step Verification):
   ```php
   define('CARCONNECT_MAIL_USERNAME', 'your_email@gmail.com');
   define('CARCONNECT_MAIL_APP_PASSWORD', 'xxxx xxxx xxxx xxxx'); // 16-character App Password
   define('CARCONNECT_MAIL_FROM_NAME', 'CarConnect');
   define('CARCONNECT_MAIL_HOST', 'smtp.gmail.com');
   define('CARCONNECT_MAIL_PORT', 587);
   ```

> **Note:** If you don't need live email sending during local development, existing accounts can log in directly without OTP verification.

---

### Step 4: Launch via XAMPP
Open your browser and visit:

👉 **`http://localhost/carconnect/`**

---

## 🔑 Demo Credentials

The database comes pre-seeded with test accounts for each role:

| Role | Email | Password | Access Area |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@carconnect.com` | `admin123` | `/carconnect/admin/admin_dashboard.php` |
| **Buyer** | `buyer@gmail.com` | `buyer123` | `/carconnect/buyer/home.php` |
| **Seller** | `seller@gmail.com` | `seller123` | `/carconnect/seller/seller_dashboard.php` |

---

## 🛡 Security Implementations

* **SQL Injection Prevention:** All SQL queries involving user inputs utilize prepared statements (`mysqli_prepare` with parameterized binding).
* **Cross-Site Scripting (XSS) Defense:** All dynamic content output is filtered through `htmlspecialchars()` to prevent script injection.
* **Brute-Force Rate Limiting:** Registration OTP verification enforces a maximum of 5 attempts and a 60-second cooldown period between resend requests.
* **Role-Based Access Control (RBAC):** Every internal portal strictly validates `$_SESSION['role']` against unauthorized access attempts.
* **Secure File Upload Sanitization:** Vehicle image uploads are validated for file extensions, MIME types, and file size limits (max 5MB) before moving to `/uploads/`.

---

## 🔮 Future Roadmap

- [ ] **Payment Gateway Integration:** Direct checkout via Razorpay, Stripe, or PayPal webhooks.
- [ ] **WebSocket Real-time Messaging:** Transition AJAX polling to native WebSockets with Ratchet / Socket.io for sub-millisecond chat delivery.
- [ ] **AI-Powered Vehicle Valuation:** Automated price estimation using machine learning based on historical condition, mileage, and make.
- [ ] **Car Inspection Certificate Uploads:** PDF verification reports certified by mechanics with downloadable inspection badges.
- [ ] **Geolocation-Based Radius Search:** Distance-based seller discovery powered by Google Maps API.

---

## 📜 License

This project is licensed under the **MIT License** — feel free to use, modify, and distribute this codebase for learning or production purposes.

---

<p align="center">
  <b>Built with passion by <a href="https://github.com/aayushshetty15">Aayush Shetty</a></b><br>
  <i>CarConnect — Engineering Trust and Performance into Modern Automotive Commerce.</i>
</p>