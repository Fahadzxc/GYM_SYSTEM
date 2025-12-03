# RFID Attendance System - Setup Guide

This guide explains how to set up and use the RFID attendance scanning system in your CodeIgniter 4 project.

## 📋 Table of Contents

1. [Overview](#overview)
2. [How USB RFID Readers Work](#how-usb-rfid-readers-work)
3. [File Structure](#file-structure)
4. [Database Setup](#database-setup)
5. [Configuration](#configuration)
6. [Usage](#usage)
7. [Customization](#customization)
8. [Troubleshooting](#troubleshooting)

---

## 🎯 Overview

The RFID attendance system is a **plug-and-play** module that:
- ✅ Works with USB RFID readers (keyboard emulators)
- ✅ Automatically captures RFID codes
- ✅ Records attendance in a new `rfid_attendance` table
- ✅ Does NOT modify your existing tables or code
- ✅ Is fully modular and independent

---

## 🔌 How USB RFID Readers Work

USB RFID readers work as **keyboard emulators**. Here's the flow:

```
RFID Card → RFID Reader → USB → Computer
                                    ↓
                            Acts like keyboard
                                    ↓
                            Types RFID code
                                    ↓
                            Input field receives code
                                    ↓
                            JavaScript detects input
                                    ↓
                            AJAX POST to controller
                                    ↓
                            Controller processes scan
                                    ↓
                            Database records attendance
```

**Key Points:**
- The RFID reader **types** the code as if a keyboard was used
- No special drivers needed (works like a USB keyboard)
- The code appears in whatever input field has focus
- Our system auto-detects the input and processes it

---

## 📁 File Structure

All new files have been created in the following locations:

```
GYM_SYSTEM/
├── app/
│   ├── Controllers/
│   │   └── RfidController.php          ← NEW: Main controller
│   ├── Models/
│   │   └── RfidModel.php                ← NEW: Database model
│   ├── Views/
│   │   └── rfid_scanner.php             ← NEW: Scanner interface
│   ├── Database/
│   │   └── Migrations/
│   │       └── 2024-01-01-000003_CreateRfidAttendanceTable.php  ← NEW: Migration
│   └── Config/
│       └── Routes.php                    ← MODIFIED: Added RFID routes
├── database_rfid_attendance.sql         ← NEW: Manual SQL script
└── RFID_SETUP_GUIDE.md                  ← This file
```

**No existing files were modified or deleted!**

---

## 🗄️ Database Setup

You have **two options** to create the `rfid_attendance` table:

### Option 1: Using CodeIgniter Migrations (Recommended)

1. Open your terminal/command prompt
2. Navigate to your project root directory:
   ```bash
   cd F:\xammp\htdocs\GYM_SYSTEM
   ```
3. Run the migration:
   ```bash
   php spark migrate
   ```

### Option 2: Manual SQL Execution

1. Open phpMyAdmin or your MySQL client
2. Select your database (`gym` or your database name)
3. Open the SQL tab
4. Copy and paste the contents of `database_rfid_attendance.sql`
5. Click "Go" to execute

**The table structure:**
- `attendance_id` - Auto-increment primary key
- `member_id` - Foreign key to `gym_members.id`
- `scan_time` - Datetime of the scan

---

## ⚙️ Configuration

### 1. Member ID Field Configuration

The system currently checks if the RFID code matches the `id` field in your `gym_members` table.

**To change this behavior:**

Open `app/Controllers/RfidController.php` and find this section (around line 50):

```php
// Option 1: Using 'id' field (current setup)
$member = $userModel->where('id', $rfidCode)
                   ->where('status', 'active')
                   ->first();

// Option 2: Using separate 'rfid_code' field
// Uncomment this if you have a separate RFID field:
// $member = $userModel->where('rfid_code', $rfidCode)
//                    ->where('status', 'active')
//                    ->first();
```

**If you want to use a separate `rfid_code` column:**
1. Add a `rfid_code` column to your `gym_members` table
2. Update the controller to use Option 2 (uncomment the second option)
3. Comment out Option 1

### 2. Table/Field Name Customization

All table and field names can be easily changed:

**In `app/Models/RfidModel.php`:**
- Line 15: `protected $table = 'rfid_attendance';` - Change table name
- Line 16: `protected $primaryKey = 'attendance_id';` - Change primary key
- Lines 22-25: `$allowedFields` - Add/remove fields

**In `app/Controllers/RfidController.php`:**
- Line 50: Change `'id'` to your member ID field name
- Line 51: Change `'status'` to your status field name
- Line 52: Change `'active'` to your active status value

### 3. Route Configuration

Routes are defined in `app/Config/Routes.php`:

```php
$routes->get('/rfid', 'RfidController::index');           // Scanner page
$routes->post('/rfid/scan', 'RfidController::scan');     // AJAX endpoint
$routes->get('/rfid/attendance', 'RfidController::attendance'); // History (optional)
```

**To change routes:**
- Update the routes in `Routes.php`
- Update the `SCAN_ENDPOINT` constant in `app/Views/rfid_scanner.php` (line 38)

---

## 🚀 Usage

### Accessing the Scanner

1. Start your XAMPP server (Apache + MySQL)
2. Navigate to: `http://localhost/GYM_SYSTEM/public/rfid`
3. The scanner page will load with an input field

### Using the RFID Reader

1. **Connect your USB RFID reader** to your computer
2. **Click on the input field** to ensure it has focus (or it will auto-focus)
3. **Place an RFID card** near the reader
4. The RFID code will be **automatically typed** into the field
5. The system will **automatically process** the scan via AJAX
6. You'll see a **success or error message**

### Testing Without RFID Reader

You can manually type an RFID code and press Enter to test the system.

---

## 🎨 Customization

### 1. Styling the Scanner Page

Edit `app/Views/rfid_scanner.php` to change:
- Colors, fonts, layout
- Add your logo
- Change messages

### 2. Adding Additional Fields

**To track more information (e.g., location, device ID):**

1. **Add column to database:**
   ```sql
   ALTER TABLE `rfid_attendance` 
   ADD COLUMN `location` VARCHAR(100) NULL AFTER `scan_time`;
   ```

2. **Update the Model** (`app/Models/RfidModel.php`):
   ```php
   protected $allowedFields = [
       'member_id',
       'scan_time',
       'location',  // Add new field
   ];
   ```

3. **Update the Controller** (`app/Controllers/RfidController.php`):
   ```php
   $attendanceData = [
       'member_id' => $member['id'],
       'scan_time' => date('Y-m-d H:i:s'),
       'location' => 'Main Entrance',  // Add your logic here
   ];
   ```

### 3. Disabling Duplicate Scan Prevention

By default, the system prevents multiple scans per day. To allow multiple scans:

In `app/Controllers/RfidController.php`, comment out lines 65-78 (the duplicate check).

### 4. Changing Response Messages

Edit the messages in `app/Controllers/RfidController.php`:
- Line 44: "RFID code is required"
- Line 58: "RFID code not found or member is inactive"
- Line 72: "Attendance already recorded today"
- Line 87: "Attendance recorded successfully!"

---

## 🔧 Troubleshooting

### Problem: RFID code not being detected

**Solutions:**
1. Make sure the input field has focus (click on it)
2. Check that your RFID reader is connected and working (test in Notepad)
3. Some RFID readers add a newline - the system handles this automatically
4. Check browser console for JavaScript errors (F12)

### Problem: "RFID code not found" error

**Solutions:**
1. Verify the RFID code exists in your `gym_members` table
2. Check that the member's `status` is set to `'active'`
3. Verify the field name matches (see Configuration section)
4. Check the database connection

### Problem: AJAX request failing

**Solutions:**
1. Check browser console (F12) for errors
2. Verify the route exists: `http://localhost/GYM_SYSTEM/public/rfid/scan`
3. Check CodeIgniter logs: `writable/logs/log-YYYY-MM-DD.log`
4. Verify CSRF protection settings in `app/Config/Security.php`

### Problem: Database errors

**Solutions:**
1. Ensure the `rfid_attendance` table exists (run migration or SQL)
2. Check database connection in `app/Config/Database.php`
3. Verify table structure matches the migration
4. Check MySQL error logs

### Problem: Page not loading

**Solutions:**
1. Verify XAMPP Apache is running
2. Check URL: `http://localhost/GYM_SYSTEM/public/rfid`
3. Check `.htaccess` file in `public/` directory
4. Verify CodeIgniter base URL in `app/Config/App.php`

---

## 📝 Important Notes

1. **No Existing Code Modified**: All new functionality is in separate files
2. **Database Safety**: Only creates new table, never modifies existing ones
3. **Backward Compatible**: Your existing controllers, models, and views remain unchanged
4. **Modular Design**: Easy to remove if needed (just delete the new files and routes)

---

## 🔐 Security Considerations

1. **CSRF Protection**: CodeIgniter's CSRF protection is enabled by default
2. **Input Validation**: All inputs are validated and sanitized
3. **SQL Injection**: CodeIgniter's Query Builder prevents SQL injection
4. **XSS Protection**: Output is escaped in views

---

## 📞 Support

If you encounter issues:

1. Check the Troubleshooting section above
2. Review CodeIgniter logs: `writable/logs/`
3. Check browser console (F12) for JavaScript errors
4. Verify database connection and table structure

---

## ✅ Quick Checklist

- [ ] Database table created (`rfid_attendance`)
- [ ] Routes added to `Routes.php`
- [ ] Controller file exists (`RfidController.php`)
- [ ] Model file exists (`RfidModel.php`)
- [ ] View file exists (`rfid_scanner.php`)
- [ ] Tested with RFID reader or manual input
- [ ] Verified member IDs in database
- [ ] Checked browser console for errors

---

**You're all set!** The RFID attendance system is now ready to use. 🎉

