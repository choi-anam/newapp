# Quick Start: Telegram Bot & Email Configuration

## 🚀 Quick Access

Both configuration pages are in the admin sidebar under **Configuration** section:

```
Admin Sidebar
└── Configuration
    ├── 📱 Telegram Bot
    └── 📧 Email Config
```

## 📱 Telegram Bot Configuration

### Getting a Telegram Bot Token

1. Open Telegram app and search for **@BotFather**
2. Send `/start` command
3. Send `/newbot` to create a new bot
4. Follow the prompts and give your bot a name
5. Copy the token (looks like: `123456789:ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefg`)

### Adding Configuration

1. Click **Add Configuration** button
2. Fill in:
   - **Bot Name**: Any friendly name (e.g., "Production Bot")
   - **Bot Token**: Paste your token from BotFather
   - **Webhook URL** (optional): Your webhook endpoint
   - **Description** (optional): Notes about this bot
3. Check **Enable this configuration**
4. Click **Save Configuration**

### Managing Configurations

- **Edit**: Click the pencil icon to modify settings
- **Delete**: Click trash icon (confirmation required)
- **Toggle**: Use the switch to quickly enable/disable without editing

---

## 📧 Email Configuration

### Gmail Setup (Recommended)

1. **Enable 2-Factor Authentication**
   - Go to myaccount.google.com
   - Click "Security" in sidebar
   - Enable 2-Step Verification

2. **Generate App Password**
   - In Security section, find "App passwords"
   - Select "Mail" and "Windows Computer" (or your device)
   - Google generates a 16-character password

3. **Configure in App**
   - **Mailer Type**: SMTP
   - **Host**: smtp.gmail.com
   - **Port**: 587
   - **Username**: your.email@gmail.com
   - **Password**: The 16-char app password
   - **Encryption**: TLS
   - **From Address**: your.email@gmail.com
   - **From Name**: Your App Name

### Other SMTP Providers

#### SendGrid
- **Host**: smtp.sendgrid.net
- **Port**: 587
- **Username**: apikey
- **Password**: Your SendGrid API key
- **Encryption**: TLS

#### Mailgun
- **Host**: smtp.mailgun.org
- **Port**: 587
- **Username**: Your Mailgun domain
- **Password**: Your Mailgun SMTP password
- **Encryption**: TLS

### Adding Configuration

1. Click **Add Configuration** button
2. Select **Mailer Type**: SMTP (most common)
3. Fill in SMTP details:
   - Host
   - Port
   - Username
   - Password
4. Set sender info:
   - **From Address**: Email to send from
   - **From Name**: Display name
5. Add description (optional)
6. Check **Enable this configuration**
7. Click **Save Configuration**

### Testing Configuration

1. Scroll to **Test Configuration** section
2. Enter test email address
3. Click **Send Test Email**
4. Check the test inbox for verification

### Managing Configurations

- **Edit**: Click pencil icon to modify
- **Delete**: Click trash icon
- **Toggle**: Use switch for quick enable/disable
- **Change Password**: Just paste new password when editing (leave empty to keep current)

---

## 📋 Common Issues

### Telegram Bot Token Invalid
- Ensure the token is copied exactly from @BotFather
- Check for extra spaces or line breaks
- Make sure token is enabled in BotFather

### Gmail Says Password Wrong
- You must use **App Password**, not your Gmail password
- Generate new app password in Google Account > Security > App passwords
- Gmail doesn't support 16-digit numeric-only passwords in apps

### Email Not Sending
- Check if configuration is **Enabled** (green toggle)
- Test with **Send Test Email** feature
- Verify port: 587 (TLS) or 465 (SSL)
- Check username and password are correct
- For Gmail, use app password not regular password

### Can't Connect to SMTP Server
- Verify host name is correct
- Check port number (usually 587 or 465)
- Make sure firewall isn't blocking the port
- Test connection from terminal: `telnet host port`

---

## 🔐 Security Tips

✅ **Do:**
- Use App Passwords for Gmail instead of regular password
- Keep bot tokens secret and secure
- Use strong, unique passwords for email accounts
- Enable 2FA on email accounts
- Regularly review and update configurations

❌ **Don't:**
- Don't share bot tokens publicly
- Don't commit credentials to git/GitHub
- Don't use test passwords in production
- Don't give unnecessary admin access to staff

---

## 📞 Support

For troubleshooting:
1. Check the configuration is **enabled**
2. Verify all required fields are filled
3. Try the **test email** feature for email configs
4. Review the **description** field in each config
5. Check application logs for error messages

Need help? Contact the development team or check `BOT_EMAIL_CONFIG_GUIDE.md` for detailed documentation.
