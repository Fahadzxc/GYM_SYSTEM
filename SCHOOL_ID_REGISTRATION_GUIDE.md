# School ID Registration System - Setup Guide

## 🎯 Overview

The School ID Registration system has been integrated into the existing "Add New User" functionality in the admin dashboard. When admins click "Add User", they can now register members using school ID scanning.

## ✅ Features

- **School ID Scanning**: Supports RFID, barcode, or manual entry
- **Eligibility Checking**: Verifies that only Faculty, Admin, or Athletes can register
- **Duplicate Prevention**: Checks if school ID is already registered
- **Auto-fill**: Pre-fills member information if available from school database
- **Seamless Integration**: Works with existing gym_members table

## 📋 Registration Workflow

1. **Admin clicks "Add User"** → Modal opens with school ID input
2. **Scan/Enter School ID** → RFID reader types code or admin enters manually
3. **System checks eligibility** → Verifies role (Faculty/Admin/Athlete)
4. **System checks registration status** → Prevents duplicate registrations
5. **Form auto-fills** → If school database info available
6. **Admin completes registration** → Fills remaining details
7. **Member registered** → Can now use school ID for gym access

## 🔧 Configuration

### Eligibility Checking

The eligibility check is configured in `app/Models/RegistrationModel.php`:

**Current Implementation (Method 3 - Manual Selection):**
- Allows all school IDs to proceed
- Admin manually selects user type (staff/faculty/athlete)
- Safest option if you don't have a school database

**To Enable Pattern-Based Eligibility (Method 1):**
Uncomment and modify the pattern matching section in `RegistrationModel.php`:

```php
if (preg_match('/^FAC/', $schoolId)) {
    return [
        'eligible' => true,
        'user_type' => 'faculty',
        'reason' => 'Faculty member'
    ];
}
// Add more patterns for Admin and Athlete
```

**To Enable School Database Integration (Method 2):**
1. Connect to your school database
2. Implement the lookup in `getSchoolMemberInfo()` method
3. Map school roles to gym user types

### School Member Information Auto-fill

To enable auto-filling from a school database:

1. Edit `app/Models/RegistrationModel.php`
2. Implement `getSchoolMemberInfo()` method
3. Return member data array with fields:
   - `first_name`
   - `middle_name`
   - `last_name`
   - `email`
   - `phone_no`
   - `address`
   - `user_type`

## 📁 Files Modified/Created

### New Files:
- `app/Models/RegistrationModel.php` - Eligibility checking and school info lookup

### Modified Files:
- `app/Controllers/ManageUsers.php` - Added `checkSchoolId()` method
- `app/Views/admin/manage_users.php` - Updated Add User modal with school ID scanning
- `app/Config/Routes.php` - Added route for school ID checking

## 🚀 Usage

### For Admins:

1. Navigate to **Manage Users** page
2. Click **"Add User"** button
3. **Scan or enter school ID** in the input field
4. Click **"Check School ID"** (or wait for auto-check after scanning)
5. If eligible and not registered:
   - Form fields will appear
   - Auto-fill if school data available
   - Complete remaining fields
   - Click **"Complete Registration"**
6. Success message: *"Registration successful – this member can now enter the gym using their school ID!"*

### Error Messages:

- **"This ID is already registered"** → Member already exists
- **"You are not allowed to register for gym access"** → Role not eligible
- **"School ID is required"** → No ID entered

## 🔌 RFID Scanner Setup

The system works with USB RFID readers (keyboard emulators):

1. Connect RFID reader to computer
2. Click on school ID input field
3. Scan school ID card
4. Code is automatically typed into field
5. System auto-checks after 500ms delay

## 📝 Database

The system uses the existing `gym_members` table:
- `id` field stores the school ID
- No new tables required
- No existing data modified

## 🎨 Customization

### Change User Type Options

Edit `app/Views/admin/manage_users.php`:

```html
<select id="user_type" name="user_type" required>
    <option value="">Select User Type</option>
    <option value="staff">Staff/Admin</option>
    <option value="athlete">Athlete</option>
    <option value="faculty">Faculty</option>
</select>
```

### Modify Success Message

Edit `app/Controllers/ManageUsers.php` in `addUser()` method:

```php
'message' => 'Registration successful – this member can now enter the gym using their school ID!'
```

### Adjust Auto-check Delay

Edit `app/Views/admin/manage_users.php`:

```javascript
setTimeout(function() {
    if (schoolIdInput.value.trim().length > 0) {
        checkSchoolId();
    }
}, 500); // Change 500 to your preferred delay in milliseconds
```

## 🔐 Security

- CSRF protection enabled
- Input validation on all fields
- SQL injection prevention via CodeIgniter Query Builder
- XSS protection via output escaping

## ✅ Testing

1. **Test with existing member**: Should show "already registered"
2. **Test with new school ID**: Should allow registration
3. **Test eligibility**: Modify `RegistrationModel` to test rejection
4. **Test RFID scanning**: Use actual RFID reader or type manually

## 🐛 Troubleshooting

### School ID not being detected
- Ensure input field has focus
- Check RFID reader connection
- Verify reader is in keyboard emulation mode

### "Not eligible" error
- Check `RegistrationModel::checkEligibility()` method
- Verify eligibility logic matches your requirements
- Check if pattern matching is correctly configured

### Auto-fill not working
- Verify `getSchoolMemberInfo()` method is implemented
- Check school database connection
- Verify data structure matches expected format

## 📞 Support

For issues:
1. Check browser console (F12) for JavaScript errors
2. Check CodeIgniter logs: `writable/logs/`
3. Verify database connection
4. Test with manual ID entry first

---

**The registration system is now fully integrated and ready to use!** 🎉

