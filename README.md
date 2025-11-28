# Bible Quiz Game (PHP + MySQL + JS)

## Setup

1. Clone this repository.
2. Import `database/database.sql` into MySQL (phpMyAdmin).
3. Copy `config/config.php` and set DB credentials.
4. Place folder in your web server root (XAMPP `htdocs`, Laragon `www`, etc).
5. Open browser: `http://localhost/bible-quiz-game/public/login.php`
6. Sample admin: `admin@example.com` / `123456`.
7. Sample user: `test@example.com` / `123456` or register new account.

## Notes
- Questions are returned randomly.
- Current implementation exposes correct answer to client for simplicity (easy mode).
- To secure checking server-side, modify quiz flow to send question id + selected answer to API for verification.

## Sample Link
https://software.theholywrit.com/bible-quiz-game
