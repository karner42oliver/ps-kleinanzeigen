# CUSTOMPRESS MIGRATION ROADMAP (SIMPLIFIED)

## 🎯 Mission
Convert ps-kleinanzeigen from CustomPress dependency to **pure native WordPress**.

---

## 📍 Current Status: PHASE 1 ✅ COMPLETE

| Phase | Task | Status | Time | Start | End |
|-------|------|--------|------|-------|-----|
| **1** | Native Post Types, Taxonomies, Custom Fields | ✅ DONE | 2-3h | Apr 2 | Apr 2 |
| **2** | Frontend Templates Refactor | ⏳ TODO | 5-6h | Apr 3 | - |
| **3** | Admin Interface Updates | ⏳ TODO | 4-5h | Apr 4 | - |
| **4** | CustomPress Complete Removal | ⏳ TODO | 2-3h | Apr 5 | - |
| **5** | Testing & Release (v2.0.0) | ⏳ TODO | 3-4h | Apr 6 | - |

---

## ✅ PHASE 1: Native WordPress Foundation

**What was done:**
- 3 new PHP classes (natural WordPress only)
- Removed: Migration Handler (not needed)
- Removed: Compatibility Layer (not needed)
- Removed: CustomPress deactivation hooks
- Simplified loader.php (just 3 classes)

**Files Changed:**
- ✅ `core/class-native-post-types.php` - NEW
- ✅ `core/class-native-taxonomies.php` - NEW
- ✅ `core/class-native-custom-fields.php` - NEW
- ✅ `loader.php` - SIMPLIFIED
- ❌ `core/class-migration-handler.php` - DELETED (not needed)
- ❌ `core/class-compatibility-layer.php` - DELETED (not needed)

**Result:**
- Plugin can now be activated
- Native post type 'classifieds' works
- Native taxonomies work
- Custom fields work via post meta
- **Zero CustomPress dependency**

---

## ⏳ PHASE 2: Frontend Templates (5-6 hours)

Frontend pages that need refactoring:
- `ui-front/general/page-classifieds.php`
- `ui-front/general/single-classifieds.php`
- `ui-front/general/page-my-classifieds.php`
- `ui-front/general/page-update-classified.php`
- `ui-front/general/page-checkout.php`

**Tasks:**
1. Replace CustomPress field calls with `get_post_meta()`
2. Replace CustomPress loops with `WP_Query`
3. Update form submissions
4. Test on frontend

---

## ⏳ PHASE 3: Admin Interface (4-5 hours)

Files that need refactoring:
- `ui-admin/dashboard.php`
- `ui-admin/settings-*.php`
- `core/admin.php`

**Tasks:**
1. Replace CustomPress admin hooks
2. Create meta boxes for custom fields
3. Update admin UI
4. Test admin operations

---

## ⏳ PHASE 4: CustomPress Removal (2-3 hours)

Files to remove/cleanup:
- Remove any remaining CustomPress imports
- Remove CustomPress compatibility checks
- Clean up old post type definitions
- Remove old taxonomy definitions

**Files likely to change:**
- `core/core.php`
- `core/functions.php`
- `loader.php` (already done in Phase 1)

---

## ⏳ PHASE 5: Testing & Release (3-4 hours)

**Test Cases:**
- ✓ Create classified
- ✓ Edit classified
- ✓ Delete classified
- ✓ Add custom fields
- ✓ Assign taxonomies
- ✓ View on frontend
- ✓ Edit from frontend
- ✓ REST API endpoints

**Release:**
- Bump version to 2.0.0
- Update README
- Create Release Notes
- Deploy to production

---

## 📊 Timeline Overview

```
Phase 1: ████████████ 2h (DONE)
Phase 2: ⏳ 5-6h
Phase 3: ⏳ 4-5h
Phase 4: ⏳ 2-3h
Phase 5: ⏳ 3-4h
─────────────────────────
Total:   16-21 hours (3-4 weeks at 5h/week)
```

---

## 🎯 Next Step

Start **Phase 2** when ready:
- Refactor frontend templates
- Replace CustomPress calls with native WordPress
- Test all pages work

For now, Phase 1 is **DONE** ✅
