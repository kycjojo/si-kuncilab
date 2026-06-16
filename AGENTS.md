# AGENTS.md — SiKunci Lab

## Tech stack
- Laravel 11 + PHP 8.2+, MySQL (XAMPP), Bootstrap 5 + Chart.js + Bootstrap Icons (CDN, no npm)
- **No frontend build step** — no package.json, no Vite. All CSS/JS from CDNs in `resources/views/layouts/app.blade.php`
- QR generation: `qrcode.js` CDN (`https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js`). QR scanning: `jsQR` CDN. Add via `@stack('scripts')` per-view, not the layout.

## Project structure
| Path | Purpose |
|---|---|
| `routes/web.php` | All routes, organized by role prefix |
| `app/Http/Controllers/Auth/` | Custom auth (no Breeze/Jetstream) |
| `app/Http/Controllers/Admin/` | Admin: dashboard, users, labs, reports |
| `app/Http/Controllers/Pengawas/` | Supervisor: key handover, violations |
| `app/Http/Controllers/Murid/` | Student: borrowing requests, checklists, pass kunci |
| `app/Http/Middleware/` | `RoleMiddleware` (checks role), `CheckBlocked` (blocks students with poin >= 5) |
| `app/Models/` | 7 models: User, Lab, Peminjaman, PenyerahanKunci, ChecklistPengembalian, Pelanggaran, PassKunciToken |
| `resources/views/` | Blade templates per role, `layouts/app.blade.php` as shell |
| `database/migrations/` | Prefixed `2024_01_01_00000X` — run in strict numeric order (001–008) |
| `database/seeders/` | Creates admin + 2 pengawas + 5 murid + 8 labs |

## Roles and routing
Three roles with route prefixes: `admin/`, `pengawas/`, `murid/`. Middleware aliases in `bootstrap/app.php`:
- `role:admin|pengawas|murid` — 403 on wrong role
- `check.blocked` — murid routes only; logs out if `is_blocked = true`

New middleware aliases must be registered in `bootstrap/app.php`, not in `app/Http/Kernel.php` (Laravel 11 dropped Kernel).

## Key commands
```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed                # or migrate:fresh --seed to reset
php artisan storage:link                  # required for photo uploads
php artisan serve
php artisan serve --host=0.0.0.0 --port=8000
php artisan route:list
php artisan cache:clear && php artisan config:clear && php artisan view:clear
php artisan tinker
```

## Default accounts (after seeder)
| Role | Email | Password |
|---|---|---|
| Admin | admin@labsekolah.com | password |
| Pengawas | pengawas@labsekolah.com | password |
| Pengawas 2 | pengawas2@labsekolah.com | password |
| Murid | ahmad@labsekolah.com | password |

## Database
- Connection: `mysql`, database: `lab_key_system`, charset: `utf8mb4`
- Session: `.env` uses `file`, `.env.example` uses `database` — **difference is intentional**
- Queue: `sync` (no worker needed). Cache: `file`. Mail: `log` (no real email).
- `peminjaman.status` is a MySQL `ENUM` column — adding a new status value requires an `ALTER TABLE` migration, not just a PHP constant.

## Peminjaman status flow
`menunggu` → `disetujui` → `berlangsung` → `checklist_diisi` → `selesai` (or `terlambat`)
- `ditolak` is also a valid terminal status (pengawas rejects)
- `ChecklistPengembalian` record is created at `checklist_diisi`; 5 boolean fields + `foto_path`
- Late returns: `jam_selesai_aktual > jam_selesai_rencana` (string comparison of HH:MM:SS)
- `Peminjaman` table name is `peminjaman` (not `peminjamans`) — set via `$table = 'peminjaman'` in model
- Active statuses used in guards: `['menunggu', 'disetujui', 'berlangsung', 'checklist_diisi']`

## Violation system
- `poin_pelanggaran` assigned manually by Pengawas/Admin
- Auto-block when total poin >= 5 (checked at login in `AuthController` and on every murid request via `CheckBlocked`)
- Admin resets points and unblocks via user management

## Photo uploads
Stored at `storage/app/public/checklist-photos/`, served via `storage/` symlink. Run `php artisan storage:link`.

## Tests
No tests exist.

## Config quirks
- Timezone: `Asia/Jakarta`, locale: `id`, faker locale: `id_ID`
- `APP_LOCALE=id` — Carbon dates use the `id` locale (Indonesian day/month names)
- App name: "SiKunci Lab"

## Realtime polling (layout)
`layouts/app.blade.php` includes a built-in polling mechanism. Pages opt in by adding `data-realtime-page` to a wrapper element; regions to refresh are marked with `data-realtime-region="key"`. The layout polls `window.location.href` every N ms (default 5000) and swaps changed regions. A `sikunci:content-refreshed` CustomEvent fires on changes, and `window.dispatchEvent(new CustomEvent('sikunci:refresh-now'))` triggers an immediate refresh.

---

## Implemented features

### Quick Borrow (murid dashboard)
`DashboardController` passes `$labs` to the view. The dashboard has a **Quick Borrow** card with clickable lab cards + session-time buttons that submits directly to `murid.peminjaman.store` via a hidden-field form. Only shown when student is not blocked and has no active peminjaman. Tujuan defaults to "Praktikum".

### Pass Kunci Langsung (fully implemented)
Student A (holding key) hands the key directly to Student B without going through the Pengawas.

**Status accepted by `showQr` and `generate`:** `['berlangsung', 'checklist_diisi']` — both are valid (the spec said only `berlangsung`, but the implementation accepts both).

**Extra route vs. spec:** `pass-kunci.status` (POST `/peminjaman/{peminjaman}/pass-kunci/status`) exists in addition to the four spec routes. The QR page polls this every 2 seconds to detect when B has scanned.

**`redeem` logic:** Wraps in `DB::transaction()`. Creates B's peminjaman with `jam_mulai = now()` (not copied from A). Sets `PenyerahanKunci.pengawas_id = A's user id`.

**Pengawas transfer log:** `pengawas.kunci.show` checks `isset($transferLogs)` — the controller must pass `$transferLogs` from a query like `PassKunciToken::where('from_peminjaman_id', ...)->orWhere('to_peminjaman_id', ...)->get()`.

**Model relationships on `Peminjaman`:**
- `passKunciKeluar()` — hasOne PassKunciToken via `from_peminjaman_id` where status=used
- `passKunciMasuk()` — hasOne PassKunciToken via `to_peminjaman_id` where status=used
- `statusLabelForDisplay()` / `statusBadgeForDisplay()` — use these instead of raw `status` for display, they show "Diserahkan ke [name]" when pass kunci was used.

**PassKunciToken model:** `app/Models/PassKunciToken.php`, table `pass_kunci_tokens`. Relationships: `fromMurid()`, `usedByUser()`, `fromPeminjaman()`, `toPeminjaman()`.

**Checklist gate in dashboard:** "Pass Kunci" button only appears if `$peminjaman_aktif->checklistPengembalian !== null`, otherwise shows "Isi Checklist" button instead.
