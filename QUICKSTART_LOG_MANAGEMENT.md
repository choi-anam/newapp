# 🚀 Quick Start - Activity Log Management

## Accessing the Feature

### Web UI - Management Dashboard
```
URL: /admin/activities/management
Button: "Manage / Archive" (in Activity Log index)
```

### Artisan Commands
```bash
# Archive logs (default 90 days)
php artisan logs:archive

# Archive specific days
php artisan logs:archive --days=180

# Delete logs permanently
php artisan logs:cleanup

# Delete with force (skip confirmation)
php artisan logs:cleanup --days=365 --force
```

---

## Common Tasks

### 1️⃣ Archive Old Logs
**When:** Monthly cleanup to keep database performant
**How:**
1. Open `/admin/activities/management`
2. Scroll to "Arsipkan Log Lama" section
3. Enter days: `90` (default)
4. Click "Arsipkan Sekarang"
5. Logs older than 90 days move to archive table

**Or via command:**
```bash
php artisan logs:archive --days=90
```

### 2️⃣ View Archived Logs
**When:** Need to review old logs
**How:**
1. Click "Lihat Arsip" in Management Dashboard statistics
2. Or navigate to `/admin/activities-archives`
3. Browse with pagination
4. Click 👁️ icon for full details
5. Click ↩️ to restore if needed

### 3️⃣ Restore from Archive
**When:** Need to recover archived data
**How:**
1. Go to `/admin/activities-archives`
2. Find the log entry
3. Click "↩️ Restore" button
4. Confirm dialog
5. Log restored to main activities table

### 4️⃣ Delete Old Logs (Permanent)
**When:** Need disk space, cleanup old archived data
**How:**
1. Open `/admin/activities/management`
2. Scroll to "Hapus Log Lama" section
3. Enter days: `365` (1 year old)
4. Click "Hapus Sekarang"
5. Confirm deletion (2x confirmation)
6. Logs permanently deleted (can't restore!)

**Or via command:**
```bash
php artisan logs:cleanup --days=365 --force
```

### 5️⃣ Emergency: Free Up Disk Space
**When:** Database getting too large
**How:**
1. Open `/admin/activities/management`
2. Click "Arsipkan Sekarang" with `180` days
3. Wait for operation (progress bar shown)
4. Check statistics - should be much smaller
5. Data is safe in archive

---

## Statistics Dashboard

Shows you:
- **Total Log** - All logs in activities table
- **Log Hari Ini** - Logs created today
- **Log Bulan Ini** - Logs created this month
- **Log Terarsipkan** - Archived logs (click to view)

---

## Data Backup Format

Archives store complete JSON backup:
```json
{
  "description": "Dipperbarui oleh [User]",
  "subject_type": "App\\Models\\User",
  "subject_id": 5,
  "causer_type": "App\\Models\\User",
  "causer_id": 1,
  "properties": { ... },
  "created_at": "2025-11-20T10:30:00...",
  "event": "updated"
}
```

All data preserved even after archive!

---

## Safety Tips

✅ **Always archive before deleting**
- Archive keeps JSON backup
- You can restore anytime
- Safe to delete archived logs after review

✅ **Backup your database first**
- Before running cleanup commands
- Test restore before production
- Keep 6+ months of backups

✅ **Monitor regularly**
```sql
-- Check table sizes
SELECT 
  table_name,
  ROUND(((data_length + index_length) / 1024 / 1024), 2) as size_mb
FROM information_schema.TABLES
WHERE table_name IN ('activities', 'activity_log_archives');
```

---

## Automation (Optional)

Add to `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Archive weekly
    $schedule->command('logs:archive --days=90')
        ->weekly()
        ->at('02:00')
        ->name('archive-logs')
        ->withoutOverlapping();
    
    // Cleanup monthly
    $schedule->command('logs:cleanup --days=365 --force')
        ->monthlyOn(1, '03:00')
        ->name('cleanup-logs')
        ->withoutOverlapping();
}
```

Then run scheduler:
```bash
php artisan schedule:work
```

---

## Troubleshooting

### Command not found
```bash
# Ensure commands are registered
php artisan list | grep logs:

# If not showing, run
php artisan cache:clear
php artisan config:clear
```

### Archive not showing in UI
```bash
# Check if migration ran
php artisan migrate:status

# If not run, execute
php artisan migrate
```

### Can't delete logs
- Check user is admin
- Check table permissions
- Ensure foreign key constraints allow deletion

### Need to restore all archives
```bash
-- Manually restore (if UI button fails)
INSERT INTO activities 
SELECT 
  id,
  description,
  subject_type,
  subject_id,
  causer_type,
  causer_id,
  properties,
  created_at,
  batch_uuid,
  event,
  created_at as log_name
FROM activity_log_archives
WHERE log_type = 'scheduled';
```

---

## Performance Expectations

| Operation | Speed | Impact |
|-----------|-------|--------|
| Archive 10K logs | ~2-5 sec | ✅ Minimal |
| Archive 100K logs | ~20-30 sec | ✅ Minimal |
| Delete 10K logs | ~1-3 sec | ⚠️ Brief lock |
| View archives | ~instant | ✅ None |
| Restore 1 log | <1 sec | ✅ None |

---

## Questions?

See `ACTIVITY_LOG_MANAGEMENT.md` for detailed documentation.

**Ready to go! 🎉**
