# Password Reset Functionality Setup

## Overview

The password reset functionality has been implemented with the following components:

1. **Forgot Password Link** - Added to login.php
2. **Forgot Password Page** - `forgot-password.php` for email input
3. **Reset Password Page** - `reset-password.php` for password reset
4. **Database Columns** - Added reset_token and reset_token_expiry to users table

## Database Setup

### Option 1: Run SQL File

Execute the `config/add_password_reset_columns.sql` file in your database:

```sql
ALTER TABLE `users`
ADD COLUMN `reset_token` VARCHAR(255) NULL AFTER `profile_picture`,
ADD COLUMN `reset_token_expiry` DATETIME NULL AFTER `reset_token`;

ALTER TABLE `users` ADD INDEX `idx_reset_token` (`reset_token`);
```

### Option 2: Manual Execution

Run these queries in phpMyAdmin or your database management tool:

```sql
ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN reset_token_expiry DATETIME NULL;
ALTER TABLE users ADD INDEX idx_reset_token (reset_token);
```

## How It Works

1. **User clicks "Forgot Password?" on login page**
2. **User enters email on forgot-password.php**
3. **System generates reset token and shows reset link** (currently displayed on screen)
4. **User clicks reset link and enters new password**
5. **Password is updated and token is cleared**

## Production Considerations

### Email Integration

Currently, the reset link is displayed on the screen for testing. For production:

1. **Install PHPMailer or similar library**
2. **Configure SMTP settings in site settings**
3. **Modify forgot-password.php to send actual emails**

### Security Enhancements

- **Rate limiting** for password reset requests
- **Token expiration** (currently 1 hour)
- **Secure token generation** (using random_bytes)
- **HTTPS requirement** for production

## Files Modified/Created

- ✅ `login.php` - Added forgot password link
- ✅ `forgot-password.php` - New password reset request page
- ✅ `reset-password.php` - New password reset page
- ✅ `config/add_password_reset_columns.sql` - Database migration

## Testing

1. Go to login page and click "Forgot Password?"
2. Enter a valid email address
3. Copy the reset link displayed
4. Use the link to reset password
5. Try logging in with new password

The functionality is now ready for testing!
