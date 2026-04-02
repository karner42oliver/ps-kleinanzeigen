# 📁 FILE IMPACT MAP - CustomPress Dependency Locations

```
ps-kleinanzeigen/
├── 🔴 LOADER.PHP                          [CRITICAL]
│   ├─ Line 57-58: include 'custompress'   ← REMOVE
│   └─ Lines 65-98: Deactivation cleanup   ← REWRITE
│
├── 🔴 CORE/
│   │
│   ├─ 🔴 DATA.PHP                          [CRITICAL]  
│   │   ├─ Line 175: get ct_custom_fields  ← MIGRATE
│   │   ├─ Line 178-197: Duration Field    ← REFACTOR
│   │   │   └─ selectbox_4cf582bd61fa4 ID ← RENAME
│   │   ├─ Line 209-228: Cost Field        ← REFACTOR
│   │   │   └─ text_4cfeb3eac6f1f ID      ← RENAME
│   │   ├─ Line 201-205: Option Updates    ← REPLACE
│   │   ├─ Line 233-237: Multisite Setup   ← ADJUST
│   │   └─ Line 241: flush_rewrite_rules() ← KEEP
│   │
│   ├─ 🔴 CORE.PHP                          [CRITICAL]
│   │   ├─ Line 418-419: do_action 'activated_plugin' ← CLEAN UP
│   │   ├─ Line 982-984: save_custom_fields() ← REPLACE
│   │   ├─ Line 2171-2173: save_custom_fields() ← REPLACE
│   │   ├─ Line 2094: _meta_ct_selectbox   ← RENAME
│   │   ├─ Method: save_expiration_date()  ← UPDATE
│   │   └─ Meta key handling: _ct_* → _cf_ ← MIGRATE
│   │
│   ├─ ✅ ADMIN.PHP                         [OK - No CP deps]
│   ├─ ✅ BUDDYPRESS.PHP                    [OK - No CP deps]
│   ├─ ✅ PAYMENTS.PHP                      [OK - No CP deps]
│   └─ ✅ OTHER FILES                       [OK - No CP deps]
│
├── 🟠 UI-ADMIN/
│   │
│   ├─ 🟠 DASHBOARD.PHP                     [HIGH]
│   │   ├─ Line 108-111: global $CustomPress_Core ← REMOVE
│   │   │   └─ selectbox_4cf582bd61fa4 access
│   │   ├─ Line 176-179: global $CustomPress_Core ← REMOVE
│   │   │   └─ selectbox_4cf582bd61fa4 access
│   │   └─ Replace with: Classifieds_Fields::get_field_options('duration')
│   │
│   ├─ ✅ SETTINGS-*.PHP                    [OK - Option usage OK]
│   └─ ✅ OTHER FILES                       [OK - No CP deps]
│
├── 🟠 UI-FRONT/
│   │
│   ├─ 🟠 GENERAL/
│   │   │
│   │   ├─ 🟠 PAGE-MY-CLASSIFIEDS.PHP       [HIGH]
│   │   │   ├─ Line 161-164: global $CustomPress_Core ← REMOVE
│   │   │   │   └─ selectbox_4cf582bd61fa4 access
│   │   │   └─ Replace: Classifieds_Fields::get_field_options('duration')
│   │   │
│   │   ├─ 🟠 PAGE-UPDATE-CLASSIFIED.PHP    [HIGH]
│   │   │   ├─ Line 10: Include comment/code? ← VERIFY
│   │   │   ├─ Line 214: CustomPress access? ← VERIFY
│   │   │   └─ Form handling: Check save logic
│   │   │
│   │   ├─ 🟡 CUSTOM-FIELDS.PHP             [MEDIUM]
│   │   │   └─ Check for CP field rendering
│   │   │
│   │   └─ ✅ OTHER FILES                   [OK]
│   │
│   └─ 🟠 BUDDYPRESS/
│       └─ MEMBERS/SINGLE/CLASSIFIEDS/
│           │
│           ├─ 🟠 UPDATE-CLASSIFIED.PHP     [HIGH]
│           │   ├─ Line 10: Include? ← VERIFY
│           │   ├─ Line 224: CustomPress access? ← VERIFY
│           │   └─ Form handling logic
│           │
│           └─ 🟠 MY-CLASSIFIEDS.PHP        [HIGH]
│               ├─ Line 191-193: global $CustomPress_Core ← REMOVE
│               │   └─ selectbox_4cf582bd61fa4 access
│               └─ Replace: Classifieds_Fields::get_field_options('duration')
│
├── 🔴 CORE/CUSTOMPRESS/                    [DELETE ENTIRELY]
│   ├─ LOADER.PHP
│   ├─ README.md
│   ├─ CORE/
│   ├─ DATEPICKER/
│   ├─ LANGUAGES/
│   ├─ PSOURCE/
│   ├─ UI-ADMIN/
│   └─ ... (entire directory)
│
└── ✅ OTHER DIRECTORIES                    [OK - No CP deps]
    ├─ AU-ADMIN/             [No CP]
    ├─ MU-PLUGINS/           [No CP]
    ├─ SAMPLES/              [No CP]
    └─ ...
```

---

## 📊 SUMMARY BY SEVERITY

### 🔴 CRITICAL (Must Change)
- **5 FILES** | **7 LOCATIONS**
  1. `loader.php` - CustomPress include + deactivation
  2. `core/data.php` - PostType/Taxonomy/Field registration
  3. `core/core.php` - Field saving + field handling

**Total Refactoring Needed:** ~150 lines of code

---

### 🟠 HIGH (Should Change)  
- **4 FILES** | **6 LOCATIONS**
  1. `ui-admin/dashboard.php` - Field options access (2 places)
  2. `ui-front/general/page-my-classifieds.php` - Field options (1 place)
  3. `ui-front/general/page-update-classified.php` - Field handling (2 places)
  4. `ui-front/buddypress/members/single/classifieds/my-classifieds.php` - Field options (1 place)
  5. `ui-front/buddypress/members/single/classifieds/update-classified.php` - Field handling (2 places)

**Total Refactoring Needed:** ~50 lines of code

---

### 🟡 MEDIUM (Nice to Change)
- **1 DIRECTORY** | **80 LINES**
  1. `core/custompress/` - Entire directory can be deleted after Phase 4

**Total Cleanup Needed:** Delete entire `core/custompress/` folder

---

### ✅ OK (No Changes)
- `core/admin.php`
- `core/buddypress.php`  
- `core/payments.php`
- `core/functions.php`
- `core/main.php`
- `ui-admin/settings-*.php`
- `ui-admin/message.php`
- `ui-front/general/single-classifieds.php`
- `ui-front/general/page-classifieds.php`
- All other non-CustomPress files

---

## 🎯 CODE PATTERNS TO REPLACE

### Pattern 1: Global CustomPress Access
**Before:**
```php
global $CustomPress_Core;
if(isset($CustomPress_Core)){
    $durations = $CustomPress_Core->all_custom_fields['selectbox_4cf582bd61fa4']['field_options'];
}
```

**After:**
```php
$durations = Classifieds_Fields::get_field_options('duration');
```

**Locations:** 6 places in code (dashboard, ui-front)

---

### Pattern 2: Custom Field Saving
**Before:**
```php
if ( class_exists( 'CustomPress_Core' ) ) {
    global $CustomPress_Core;
    $CustomPress_Core->save_custom_fields( $post_id );
}
```

**After:**
```php
$this->save_custom_fields_native( $post_id, $_POST );
```

**Locations:** 2 places in `core.php`

---

### Pattern 3: Meta Key Migration
**Before:**
```php
update_post_meta( $post_id, '_ct_selectbox_4cf582bd61fa4', 0 );
```

**After:**
```php
update_post_meta( $post_id, '_cf_duration', 0 );
```

**Locations:** Multiple in `core.php`

---

## 🗑️ CLEANUP CHECKLIST

Before Migration:
- [ ] Backup database
- [ ] Test current CustomPress setup

After Phase 1:
- [ ] Verify PostType registering without CP
- [ ] Check WP Dashboard shows Classifieds
- [ ] Test basic CRUD

After Phase 2:
- [ ] Verify Fields save to Meta
- [ ] Verify Fields load from Meta
- [ ] Test with Sample Data

After Phase 3:
- [ ] Test Admin forms save
- [ ] Test Frontend forms save
- [ ] Test BuddyPress forms

After Phase 4:
- [ ] Delete `core/custompress/` directory
- [ ] Remove CP include from loader.php
- [ ] Update deactivation hook

After Phase 5:
- [ ] Run full test suite
- [ ] Verify Backward Compatibility
- [ ] Check for PHP Errors
- [ ] Performance test

---

## 📈 STATISTICS

| Metric | Value |
|--------|-------|
| Total Files in Plugin | ~80 |
| Files with CustomPress Deps | 11 |
| % Files Affected | 13.75% |
| Lines to Remove/Modify | ~200 |
| Lines to Add | ~300 |
| Net Code Change | ~100 lines |
| Directories to Delete | 1 |
| Classes to Create | 3 |
| Methods to Add | ~15 |

---

## 🔗 RELATED CLASSES/FUNCTIONS

CustomPress verwendet intern:
```
CustomPress_Core {}
  - save_custom_fields($post_id)
  - all_custom_fields[] array
  - Various internal methods
```

Wir implementieren stattdessen:
```
Classifieds_PostTypes {}
  - register_classifieds_post_type()
  - register_classifieds_taxonomies()

Classifieds_Fields {}
  - get_field_options($field_name)
  - get_field($field_name, $post_id)
  - get_all_fields()
  - FIELDS constant (field definitions)

Classifieds_MetaBox {}
  - register()
  - render()
  - save()
```