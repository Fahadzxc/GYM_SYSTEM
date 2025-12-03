# RFID Attendance System - Quick Start Guide

## 🚀 Quick Setup (5 Minutes)

### Step 1: Create Database Table

**Option A - Using Migration (Recommended):**
```bash
cd F:\xammp\htdocs\GYM_SYSTEM
php spark migrate
```

**Option B - Manual SQL:**
1. Open phpMyAdmin
2. Select your database (`gym`)
3. Run the SQL from `database_rfid_attendance.sql`

### Step 2: Access the Scanner

Open your browser and go to:
```
http://localhost/GYM_SYSTEM/public/rfid
```

### Step 3: Test It

1. Connect your USB RFID reader
2. Click the input field (or it auto-focuses)
3. Scan an RFID card
4. Watch the magic happen! ✨

---

## 📍 File Locations

| File | Location |
|------|----------|
| Controller | `app/Controllers/RfidController.php` |
| Model | `app/Models/RfidModel.php` |
| View | `app/Views/rfid_scanner.php` |
| Migration | `app/Database/Migrations/2024-01-01-000003_CreateRfidAttendanceTable.php` |
| SQL Script | `database_rfid_attendance.sql` |
| Routes | `app/Config/Routes.php` (already added) |

---

## 🔧 Configuration

### Change Member ID Field

If your RFID codes are stored in a different field (e.g., `rfid_code`):

1. Open `app/Controllers/RfidController.php`
2. Find line ~50 (Option 1/Option 2 section)
3. Comment Option 1, uncomment Option 2
4. Update field name if needed

### Change Table/Field Names

**Table Name:** Edit `app/Models/RfidModel.php` line 15
```php
protected $table = 'rfid_attendance'; // Change this
```

**Field Names:** Edit `app/Models/RfidModel.php` lines 22-25
```php
protected $allowedFields = [
    'member_id',    // Change if needed
    'scan_time',    // Change if needed
];
```

---

## 🎯 Routes

| URL | Method | Purpose |
|-----|--------|---------|
| `/rfid` | GET | Scanner page |
| `/rfid/scan` | POST | AJAX endpoint for scanning |
| `/rfid/attendance` | GET | View attendance history (optional) |

---

## ✅ How It Works

1. **RFID Reader** → Types code into input field (like a keyboard)
2. **JavaScript** → Detects input change
3. **AJAX** → Sends POST to `/rfid/scan`
4. **Controller** → Validates and checks member exists
5. **Model** → Inserts attendance record
6. **Response** → Shows success/error message

---

## 🐛 Troubleshooting

| Problem | Solution |
|---------|----------|
| "RFID code not found" | Check member exists in `gym_members` table with status='active' |
| AJAX error | Check browser console (F12), verify route exists |
| Page not loading | Verify XAMPP Apache is running, check URL |
| Database error | Run migration or SQL script, check table exists |

---

## 📝 Important Notes

- ✅ **No existing code modified** - All new files
- ✅ **No existing tables modified** - Only creates new table
- ✅ **Fully modular** - Easy to remove if needed
- ✅ **Backward compatible** - Won't break existing features

---

## 🔗 Full Documentation

See `RFID_SETUP_GUIDE.md` for complete documentation.

---

**That's it! You're ready to scan! 🎉**

