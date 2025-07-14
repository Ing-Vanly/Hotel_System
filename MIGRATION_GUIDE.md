# Migration Guide - Hotel System Fixes

## ✅ Current Status
The system has been fixed to work with your current database structure. All errors should now be resolved.

## 🔧 To Activate Advanced Features

When you're ready to activate the enhanced Room and Booking features, run these commands in order:

### Step 1: Run the migrations
```bash
php artisan migrate
```

This will run these migrations:
1. `2025_07_14_031700_update_leaves_status_enum.php` - Fix leave status ENUM
2. `2025_07_14_040000_update_rooms_table_structure.php` - Add room management fields  
3. `2025_07_14_041000_update_bookings_table_structure.php` - Add booking management fields
4. `2025_07_14_042000_update_room_types_table_structure.php` - Add room type fields

### Step 2: Activate Model Features

After migrations are successful, uncomment these sections:

**In `app/Models/Room.php`:**
- Uncomment all the code inside the `/* */` block (lines 44-105)

**In `app/Models/Booking.php`:**
- Uncomment all the code inside the `/* */` blocks (lines 58-74 and 78-165)

**In `app/Http/Controllers/RoomsController.php`:**
- Update line 17: Change `orderBy('name')` to `orderBy('room_number')`
- Update line 24: Change `DB::table('room_types')->get()` to `RoomType::where('is_active', true)->get()`
- Update line 31: Change `DB::table('room_types')->get()` to `RoomType::where('is_active', true)->get()`

### Step 3: Update Room Creation Form

Update your room creation form to include these new fields:
- Room Number (required, unique)
- Floor Number
- Max Occupancy  
- Room Status (available/maintenance/out_of_order)

## 🚀 New Features After Migration

### Room Management:
- ✅ Room status tracking (available/occupied/maintenance/dirty/out_of_order)
- ✅ Room availability checking for specific dates
- ✅ Floor and occupancy management
- ✅ Proper room type relationships

### Booking Management:
- ✅ Link bookings to specific rooms
- ✅ Booking status workflow (pending → confirmed → checked_in → checked_out)
- ✅ Payment status tracking
- ✅ Guest information management
- ✅ Automatic pricing calculation
- ✅ Prevent double-booking

## ⚠️ Important Notes

1. **Backup your database** before running migrations
2. **Test in development** environment first
3. **Run migrations during low-traffic hours**
4. The system works fine with current structure, only migrate when ready for advanced features

## 🛠️ If Migration Fails

If any migration fails, you can:
1. Fix the specific issue
2. Run `php artisan migrate:rollback` to undo last batch
3. Re-run `php artisan migrate`

## 📞 Need Help?

The current system works perfectly without migrations. Only run them when you want to activate the advanced hotel management features.