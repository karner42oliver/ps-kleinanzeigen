# 📊 CustomPress Dependency Analysis Report
**Datum:** April 2, 2026  
**Status:** ⚠️ **KRITISCH - Umstrukturierung erforderlich**

---

## 🎯 Executive Summary

Das ps-kleinanzeigen Plugin ist **zu stark von CustomPress abhängig** und hat folgende Probleme:

### ❌ Probleme der aktuellen Struktur

1. **Fehlende Eigenständigkeit**
   - CustomPress ist im Plugin gebündelt → Makes Plugin unklar
   - Plugin funktioniert NICHT ohne CustomPress
   - CustomPress-spezifische Options-Keys (`ct_*`)  sind hart codiert

2. **Inflexible Datenstruktur**
   - Feld-IDs sind Strings wie `selectbox_4cf582bd61fa4` (nicht sprechend)
   - Custom Fields speichern über CustomPress-Methode `save_custom_fields()`
   - Keine native WordPress Meta-Integration

3. **Code-Wartbarkeit**
   - Viele `global $CustomPress_Core` Zugriffe im Code
   - Abhängigkeit auf interne CustomPress Data Structures
   - Schwer zu erweitern/customizen ohne CustomPress zu verstehen

4. **Entwicklungs-Friction**
   - Muss CustomPress verstehen um das Plugin zu erweitern
   - CustomPress UI wird für Admin nicht benötigt (nur Datenstruktur)
   - Unnötige Komplexität in der Plugin-Architektur

---

## 📈 Analyse-Ergebnisse

### Abhängigkeits-Matrix

| Component | Current | Issue | Solution |
|-----------|---------|-------|----------|
| **PostType Registration** | `ct_custom_post_types` Option | Nicht native WP | → `register_post_type()` |
| **Taxonomy Registration** | `ct_custom_taxonomies` Option | Nicht native WP | → `register_taxonomy()` |
| **Custom Fields Defs** | `ct_custom_fields` Option | CustomPress speichert | → Programmatische Definition |
| **Field Value Storage** | `$CustomPress_Core->save_custom_fields()` | Nur CustomPress kann speichern | → `update_post_meta()` Loop |
| **Field Access** | `$CustomPress_Core->all_custom_fields[]` | Global-Zugriff | → Helper-Klasse Methoden |
| **Meta Prefix** | `_ct_*` | CustomPress-spezifisch | → `_cf_*` (plugin-spezifisch) |

### Impact-Analyse

```
🔴 KRITISCH (Must Fix):
   ├─ CustomPress Loader Include                     (6 Zeilen)
   ├─ Post Type Registrierung                        (50 Zeilen)
   ├─ Custom Fields Speichern                        (4 Stellen)
   └─ Custom Fields Abrufen                          (8 Stellen)

🟠 HOCH (Should Fix):
   ├─ Frontend Forms auf CustomPress-Fields warten  (5 Templates)
   ├─ Admin Dashboard Duration Field Access          (2 Stellen)
   └─ Deactivation Custom Cleanup                   (80 Zeilen)

🟡 MITTEL (Nice to Fix):
   ├─ Multisite Settings Handling                   (2 Zeilen)
   └─ Backwards Compatibility                       (Data Migration)
```

### Code Coverage

**Totale Abhängigkeits-Punkte:** ~12 Stellen im Code
- 1 Bootstrap (loader.php)
- 2 PostType/Taxonomy Setup (data.php) 
- 2 Field Speichern (core.php)
- 4 Field Abrufen UI (dashboard.php)
- 3 Frontend Templates Zugriff (ui-front/)

---

## 🔍 Detailed Findings

### Finding #1: Bootstrap/Loader Problem
**Severity:** 🔴 KRITICAL  
**File:** `loader.php:57-58`

```php
if(!class_exists('CustomPress_Core')) include_once 'core/custompress/loader.php';
```

**Issues:**
- Bundelt fremdes Plugin als interner Code
- Makes Plugin-Struktur unklar
- CustomPress wird als "required framework" behandelt

**Solution:** Entferne include komplett, verwende native WordPress APIs

---

### Finding #2: Post Type Registration via Options
**Severity:** 🔴 CRITICAL  
**File:** `core/data.php:75-120`

```php
$ct_custom_post_types['classifieds'] = $classifieds_default;
update_site_option( 'ct_custom_post_types', $ct_custom_post_types );
```

**Issues:**
- PostType wird als Option gespeichert, nicht via `register_post_type()`
- CustomPress liest diese Option und registriert dynamisch
- Wenn CustomPress nicht lädt → PostType existiert nicht

**Solution:** Rufe `register_post_type()` direkt in `init` Hook auf

---

### Finding #3: Custom Fields Hart-Codierte IDs
**Severity:** 🔴 CRITICAL  
**Files:** `data.php:197, data.php:221, ui-admin/dashboard.php:108`

```php
'field_id' => 'selectbox_4cf582bd61fa4',  // Duration
'field_id' => 'text_4cfeb3eac6f1f',       // Cost

// Später abrufen:
$durations = $CustomPress_Core->all_custom_fields['selectbox_4cf582bd61fa4']['field_options'];
```

**Issues:**
- Field IDs sind unverständliche Strings (4cf582bd61fa4)
- Nur über CustomPress-Objekt abrufbar
- Keine Versionskontrolle/Dokumentation dieser IDs

**Solution:** 
- Definiere Fields programmatisch
- Verwende sprechende Feld-Namen (`_cf_duration`, `_cf_cost`)
- Erstelle Konstanten oder Konfiguration

---

### Finding #4: save_custom_fields() Abhängigkeit
**Severity:** 🔴 CRITICAL  
**Files:** `core/core.php:982-984, 2171-2173`

```php
if ( class_exists( 'CustomPress_Core' ) ) {
    global $CustomPress_Core;
    $CustomPress_Core->save_custom_fields( $post_id );
}
```

**Issues:**
- Nur CustomPress kann Custom Field Werte speichern
- Wenn CustomPress nicht aktiv → Custom Fields gehen verloren
- Keine Fallback auf `update_post_meta()`

**Solution:** Ersetze mit native `update_post_meta()` Loop über alle Felder

---

### Finding #5: Global CustomPress_Core Zugriffe
**Severity:** 🟠 HIGH  
**Files:** `ui-admin/dashboard.php:108-111, 176-179, ui-front/`

```php
global $CustomPress_Core;
if(isset($CustomPress_Core)){
    $durations = $CustomPress_Core->all_custom_fields['selectbox_4cf582bd61fa4']['field_options'];
}
```

**Issues:**
- 6+ Stellen wo `global $CustomPress_Core` verwendet wird
- Koppelt UI stark an CustomPress Datenstruktur
- Schwer zu testen/debuggen

**Solution:** Schreibe Wrapper-Methode die Field Options abstrahiert

---

## 📋 Complete File Impact List

### Modifying Required:
1. **loader.php** - Remove CustomPress include
2. **core/data.php** - Replace PostType/Taxonomy registration  
3. **core/core.php** - Replace save_custom_fields() calls
4. **core/core.php** - Update Meta key prefixes (_ct_ → _cf_)
5. **core/core.php** - save_expiration_date() Meta key updates
6. **ui-admin/dashboard.php** - 2 global CustomPress_Core accesses
7. **ui-front/general/page-my-classifieds.php** - 1 global access
8. **ui-front/general/page-update-classified.php** - 2 global accesses
9. **ui-front/buddypress/members/single/classifieds/my-classifieds.php** - 1 access
10. **ui-front/buddypress/members/single/classifieds/update-classified.php** - 2 accesses

### Creating Required:
1. **core/class-cf-post-types.php** - Native PostType/Taxonomy registration
2. **core/class-cf-fields.php** - Field definition & management
3. **core/class-cf-metabox.php** - Field UI in admin/frontend

### Deleting:
1. **core/custompress/** - Entire CustomPress bundle directory

---

## 🎁 Benefits nach Migration

| Benefit | Impact |
|---------|---------|
| **Eigenständigkeit** | Plugin funktioniert unabhängig |
| **Simplicität** | Weniger Abhängigkeiten verstehen |
| **Native WP** | Verwendet Standards statt Frameworks |
| **Wartbarkeit** | Einfacher zu erweitern |
| **Performance** | Ein Handler weniger loaded |
| **Flexibilität** | Kann eigene PostType Definition anpassen |

---

## 💾 Data Compatibility

### Current CustomPress Storage:
```
Option Keys: ct_custom_post_types, ct_custom_taxonomies, ct_custom_fields
Meta Keys:   _ct_selectbox_4cf582bd61fa4, _ct_text_4cfeb3eac6f1f
Database:    wp_options, wp_postmeta
```

### After Migration:
```
Option Keys: classifieds_options (plugin-specific)
Meta Keys:   _cf_duration, _cf_cost (standardized)
Database:    wp_options, wp_postmeta
```

### Migration Path:
- Alte `_ct_*` Meta kann während Migration zu `_cf_*` konvertiert werden
- Oder: Beide Formats parallel unterstützen für Breaking-Change zu vermeiden
- Recommendation: Create Migration Script on first plugin load

---

## ⚙️ Technical Recommendations

### Short-term (Weeks 1-2):
1. ✅ Phase 1 implementieren: PostType/Taxonomy native registration
2. ✅ Phase 2: Field Speichern via Meta, nicht CustomPress
3. ✅ Testen: Plugin funktioniert ohne CustomPress
4. ✅ CustomPress Loader entfernen

### Mid-term (Weeks 3-4):
1. ✅ Phase 3: Frontend/Admin Refactoring
2. ✅ Phase 4: Data Migration & Cleanup
3. ✅ Multisite Testing
4. ✅ BuddyPress Integration Testing

### Long-term (Post-Migration):
1. ✅ Erweitere CustomPress-removed Feature Tests
2. ✅ Dokumentiere neue Field-API für Extensions
3. ✅ Berücksichtige weitere Custom Field Typen (Image, Checkbox, etc.)

---

## 🚨 Risk Assessment

### Migration Risks:
- **Data Loss Risk:** Low (Meta kopieren einfach)
- **Breaking Changes:** Medium (Alte Installationen müssen Migration laufen)
- **User Impact:** Low (UI bleibt gleich)
- **Development Risk:** Low (Clear scope, isolated changes)

### Mitigation:
- Schreibe Comprehensive Migration Script
- Teste mit Sample Data
- Provide Rollback Option wenn nötig
- Keep OLD Meta Keys für Compat Phase

---

## ✅ Sign-off

**Analysis Complete:** 100%  
**Recommendation:** ✅ **Proceed with Migration**  
**Estimated Timeline:** 4-5 weeks (full phases)  
**Effort:** ~20-25 hours developer time  

**Next Step:** Genehmigung für Phase 1 anfordern