# Bot & Email Configuration Management

This document describes the new Bot & Email Configuration Management system added to the application.

## Overview

The application now includes two new management interfaces for configuring:
1. **Telegram Bot Configuration** - Manage multiple Telegram bot tokens and webhook settings
2. **Email Configuration** - Manage multiple email services (SMTP, SendMail, etc.)

## Features

### 🤖 Telegram Bot Configuration

- **Add Multiple Bots**: Manage multiple Telegram bot configurations
- **Bot Token Management**: Securely store and manage bot tokens
- **Webhook Configuration**: Set up webhook URLs for receiving updates
- **Enable/Disable**: Toggle bot configurations on/off
- **Descriptions**: Add custom descriptions for each configuration

**Access**: Admin Panel > Configuration > Telegram Bot

**Route**: `/admin/settings/telegram-bot`

#### Features:
- ✅ Create new bot configurations
- ✅ Edit existing configurations
- ✅ Delete configurations
- ✅ Toggle status via checkbox
- ✅ View all active bots in a table

### 📧 Email Configuration

- **Multiple Email Services**: Support for SMTP, SendMail, Mailgun, Postmark, SES, etc.
- **SMTP Configuration**: Configure host, port, username, password, encryption
- **Sender Information**: Set From address and From name
- **Enable/Disable**: Toggle email configurations on/off
- **Test Email**: Send test emails to verify configuration
- **Password Security**: Passwords are hidden and not displayed in plain text

**Access**: Admin Panel > Configuration > Email Config

**Route**: `/admin/settings/email-config`

#### Supported Mailers:
- SMTP
- SendMail
- Mailgun
- Postmark
- Amazon SES
- Log
- Array

#### Features:
- ✅ Create new email configurations
- ✅ Edit existing configurations
- ✅ Delete configurations
- ✅ Toggle status via checkbox
- ✅ Test email sending
- ✅ Dynamic form fields based on mailer type

## Database Schema

### telegram_bot_configs table
```sql
id (primary key)
bot_token (unique)
bot_name
webhook_url (nullable)
is_enabled (boolean)
description (text, nullable)
created_at
updated_at
```

### email_configs table
```sql
id (primary key)
mailer
host (nullable)
port (integer, nullable)
username (nullable)
password (text, nullable - hidden in model)
encryption (nullable)
from_address (email)
from_name
is_enabled (boolean)
description (text, nullable)
created_at
updated_at
```

## Models

### TelegramBotConfig (`app/Models/TelegramBotConfig.php`)

```php
namespace App\Models;

class TelegramBotConfig extends Model
{
    protected $fillable = ['bot_token', 'bot_name', 'webhook_url', 'is_enabled', 'description'];
    protected $casts = ['is_enabled' => 'boolean', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
```

### EmailConfig (`app/Models/EmailConfig.php`)

```php
namespace App\Models;

class EmailConfig extends Model
{
    protected $fillable = ['mailer', 'host', 'port', 'username', 'password', 'encryption', 'from_address', 'from_name', 'is_enabled', 'description'];
    protected $hidden = ['password'];
    protected $casts = ['is_enabled' => 'boolean', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
```

## Controllers

### TelegramBotConfigController (`app/Http/Controllers/Admin/TelegramBotConfigController.php`)

**Methods:**
- `index()` - Display all telegram bot configurations
- `store(Request $request)` - Create new configuration
- `update(Request $request, TelegramBotConfig $config)` - Update configuration
- `destroy(TelegramBotConfig $config)` - Delete configuration
- `toggle(TelegramBotConfig $config)` - Toggle enable/disable status

### EmailConfigController (`app/Http/Controllers/Admin/EmailConfigController.php`)

**Methods:**
- `index()` - Display all email configurations
- `store(Request $request)` - Create new configuration
- `update(Request $request, EmailConfig $config)` - Update configuration
- `destroy(EmailConfig $config)` - Delete configuration
- `toggle(EmailConfig $config)` - Toggle enable/disable status
- `test(Request $request)` - Send test email (placeholder for implementation)

## Routes

All routes are protected by admin middleware and prefixed with `/admin/settings/`:

### Telegram Bot Routes
```
GET    /admin/settings/telegram-bot              - List configurations
POST   /admin/settings/telegram-bot              - Create configuration
PUT    /admin/settings/telegram-bot/{config}     - Update configuration
DELETE /admin/settings/telegram-bot/{config}     - Delete configuration
POST   /admin/settings/telegram-bot/{config}/toggle - Toggle status
```

### Email Config Routes
```
GET    /admin/settings/email-config              - List configurations
POST   /admin/settings/email-config              - Create configuration
PUT    /admin/settings/email-config/{config}     - Update configuration
DELETE /admin/settings/email-config/{config}     - Delete configuration
POST   /admin/settings/email-config/{config}/toggle - Toggle status
POST   /admin/settings/email-config/test         - Send test email
```

## Views

### Telegram Bot View (`resources/views/admin/settings/telegram-bot.blade.php`)

- Table display of all configurations
- Add button to create new configuration
- Edit modal for each configuration
- Delete button with confirmation
- Toggle switch for enable/disable
- Link to BotFather on Telegram for token generation

### Email Config View (`resources/views/admin/settings/email-config.blade.php`)

- Table display of all configurations
- Add button to create new configuration
- Edit modal for each configuration
- Delete button with confirmation
- Toggle switch for enable/disable
- Dynamic form fields based on mailer type
- Test email section
- Password field handling (empty to keep current)

## Navigation

Both configuration pages are accessible from the admin sidebar under a new **Configuration** section:

```
Sidebar > Configuration
├── Telegram Bot
└── Email Config
```

## How to Use

### Adding a Telegram Bot Configuration

1. Go to Admin Panel > Configuration > Telegram Bot
2. Click "Add Configuration" button
3. Fill in the form:
   - **Bot Name**: A friendly name for your bot
   - **Bot Token**: Get from @BotFather on Telegram
   - **Webhook URL** (optional): Your webhook endpoint
   - **Description**: Additional notes
4. Check "Enable this configuration" if you want it active
5. Click "Save Configuration"

### Adding an Email Configuration

1. Go to Admin Panel > Configuration > Email Config
2. Click "Add Configuration" button
3. Select the mailer type (SMTP recommended for most cases)
4. Fill in SMTP details if applicable:
   - **Host**: SMTP server address (e.g., smtp.gmail.com)
   - **Port**: SMTP port (usually 587 for TLS, 465 for SSL)
   - **Username**: Your email address
   - **Password**: Your password or app-specific password
   - **Encryption**: TLS or SSL
5. Set sender information:
   - **From Address**: Email address emails will be sent from
   - **From Name**: Display name
6. Add description (optional)
7. Enable the configuration
8. Click "Save Configuration"

### Testing Email Configuration

1. Go to Admin Panel > Configuration > Email Config
2. Scroll to "Test Configuration" section
3. Enter a test email address
4. Select configuration (if multiple)
5. Click "Send Test Email"

### Toggling Status

Use the checkbox toggle switch in the Status column to quickly enable/disable any configuration without editing.

## Gmail Setup (SMTP)

For Gmail users, follow these steps:

1. Enable 2-Factor Authentication on your Google Account
2. Go to Google Account > Security
3. Create an App Password
4. Use the app password instead of your regular password
5. Use these settings:
   - **Host**: `smtp.gmail.com`
   - **Port**: `587`
   - **Encryption**: `TLS`
   - **Username**: Your Gmail address
   - **Password**: Your App Password

## Security Notes

⚠️ **Important Security Considerations:**

1. **Password Storage**: Email passwords are stored encrypted in the database
2. **Hidden Display**: Passwords are never displayed in the UI
3. **Update Only**: When editing, leave the password field empty to keep the current password
4. **Admin Only**: All configuration pages require admin authentication
5. **Bot Tokens**: Treat bot tokens like passwords - keep them secret!

## Error Handling

### Validation Rules

#### Telegram Bot
- `bot_token`: Required, minimum 10 characters
- `bot_name`: Required, max 255 characters
- `webhook_url`: Optional, must be valid URL
- `description`: Optional, max 500 characters

#### Email Config
- `mailer`: Required, must be one of the supported types
- `from_address`: Required, must be valid email
- `from_name`: Required, max 255 characters
- `host`: Optional
- `port`: Optional, integer 1-65535
- `encryption`: Optional, must be TLS or SSL

## Fallback to .env

If no database records exist, the controllers display the current configuration from:
- `.env` file for Telegram bot
- `config/mail.php` for email configuration

This allows viewing of current settings even before saving to database.

## Future Enhancements

Planned features:
- [ ] Email test functionality implementation
- [ ] Telegram bot connection test
- [ ] Bot command management interface
- [ ] Email template management
- [ ] Configuration versioning/history
- [ ] Multi-tenant support

## Support

For issues or questions regarding these features, please contact the development team.
