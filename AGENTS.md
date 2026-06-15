# AGENTS.md — SiKunci Lab

## Tech stack
- Laravel 11 + PHP 8.2+, MySQL (XAMPP), Bootstrap 5 + Chart.js + Bootstrap Icons (CDN, no npm)
- **No frontend build step** — no package.json, no Vite. All CSS/JS from CDNs in `resources/views/layouts/app.blade.php`
- QR generation: `qrcode.js` CDN. QR scanning: `jsQR` CDN. Add both to the layout or push to `@stack('scripts')` per-view.

## Project structure
| Path | Purpose |
|---|---|
| `routes/web.php` | All routes, organized by role prefix |
| `app/Http/Controllers/Auth/` | Custom auth (no Breeze/Jetstream) |
| `app/Http/Controllers/Admin/` | Admin: dashboard, users, labs, reports |
| `app/Http/Controllers/Pengawas/` | Supervisor: key handover, violations |
| `app/Http/Controllers/Murid/` | Student: borrowing requests, checklists |
| `app/Http/Middleware/` | `RoleMiddleware` (checks role), `CheckBlocked` (blocks students with poin >= 5) |
| `app/Models/` | 6 models (+ new ones per feature below) |
| `resources/views/` | Blade templates per role, `layouts/app.blade.php` as shell |
| `database/migrations/` | Prefixed `2024_01_01_00000X` — run in strict numeric order |
| `database/seeders/` | Creates admin + 2 pengawas + 5 murid + 8 labs |

## Roles and routing
Three roles with route prefixes: `admin/`, `pengawas/`, `murid/`. Middleware aliases in `bootstrap/app.php`:
- `role:admin|pengawas|murid` — 403 on wrong role
- `check.blocked` — murid routes only; logs out if `is_blocked = true`

New middleware aliases must be registered here, not in `app/Http/Kernel.php` (Laravel 11 dropped Kernel).

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
| Murid | ahmad@labsekolah.com | password |

## Database
- Connection: `mysql`, database: `lab_key_system`, charset: `utf8mb4`
- Session: `.env` uses `file`, `.env.example` uses `database` — **difference is intentional**
- Queue: `sync` (no worker needed). Cache: `file`.
- `peminjaman.status` is a MySQL `ENUM` column — adding a new status value requires an `ALTER TABLE` migration, not just a PHP constant.

## Peminjaman status flow
`menunggu` → `disetujui` → `berlangsung` → `checklist_diisi` → `selesai` (or `terlambat`)
- Pengawas approves/rejects, hands over key, receives key back
- `ChecklistPengembalian` record is created at `checklist_diisi`; 5 boolean fields + `foto_path`
- Late returns: `jam_selesai_aktual > jam_selesai_rencana` (string comparison of HH:MM:SS)
- `Peminjaman` table name is `peminjaman` (not `peminjamans`) — set via `$table = 'peminjaman'` in model

## Violation system
- `poin_pelanggaran` assigned manually by Pengawas/Admin
- Auto-block when total poin >= 5 (checked at login in `AuthController` and on every murid request via `CheckBlocked`)
- Admin resets points and unblocks via user management

## Photo uploads
Stored at `storage/app/public/checklist-photos/`, served via `storage/` symlink. Run `php artisan storage:link`.

## Reports
Admin report routes have `print` variants returning A4 landscape layouts (`resources/views/admin/laporan/print/`).

## Tests
No tests exist.

## Config quirks
- Timezone: `Asia/Jakarta`, locale: `id`, faker locale: `id_ID`
- `APP_LOCALE=id` — Carbon dates use the `id` locale (Indonesian day/month names)
- App name: "SiKunci Lab"

---

## Implemented: Quick Borrow (murid dashboard)

`DashboardController` now passes `$labs` to the view. The dashboard (`resources/views/murid/dashboard.blade.php`) has a **Quick Borrow** card with clickable lab cards + session-time buttons that submits directly to `murid.peminjaman.store` via a hidden-field form (no page navigation). Only shown when student is not blocked and has no active peminjaman. Tujuan defaults to "Praktikum".

---

## Planned: Pass Kunci Langsung

Full spec for the next agent to implement. Read everything below before writing any code.

### What it does
Student A (holding key, status `berlangsung`) wants to hand the key directly to Student B without going through the Pengawas. Flow:
1. A fills checklist + uploads photo (reuses existing `ChecklistController` or a new dedicated one)
2. System generates a time-limited, single-use QR token → displayed on A's screen
3. B opens their dashboard, clicks "Ambil Kunci", scans QR from A's screen via camera
4. Server validates token (not used, not expired) → closes A's peminjaman, creates new `berlangsung` peminjaman for B, marks token used
5. Pengawas gets a DB-visible transfer log (no email: `MAIL_MAILER=log`)

### New database table: `pass_kunci_tokens`

Migration file name: `2024_01_01_000007_create_pass_kunci_tokens_table.php`

```php
Schema::create('pass_kunci_tokens', function (Blueprint $table) {
    $table->id();
    $table->foreignId('from_peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
    $table->foreignId('from_murid_id')->constrained('users')->onDelete('cascade');
    $table->string('token', 64)->unique();
    $table->enum('status', ['unused', 'used', 'expired'])->default('unused');
    $table->timestamp('expired_at');
    $table->timestamp('used_at')->nullable();
    $table->foreignId('used_by')->nullable()->constrained('users')->onDelete('set null');
    $table->foreignId('to_peminjaman_id')->nullable()->constrained('peminjaman')->onDelete('set null');
    $table->timestamps();
});
```

Model: `app/Models/PassKunciToken.php`, table name `pass_kunci_tokens`, casts `expired_at` and `used_at` as `datetime`, status as string.

### No new `peminjaman.status` value needed

When B scans successfully:
- A's peminjaman: `status = 'selesai'`, `jam_selesai_aktual = now()->format('H:i:s')`
- New peminjaman created for B: copy `lab_id`, `tanggal_pinjam`, `jam_mulai`, `jam_selesai_rencana` from A; set `murid_id = B->id`, `status = 'berlangsung'`, `tujuan = 'Pass kunci dari [A name]'`
- New `PenyerahanKunci` for B's peminjaman: `pengawas_id = A->id` (use A as the "serah" party), `waktu_serah = now()`

This avoids adding a new ENUM value to `peminjaman.status` (which requires `ALTER TABLE`).

### Routes to add (in `routes/web.php`, inside murid prefix group)

```php
// Pass Kunci Langsung
Route::get('/peminjaman/{peminjaman}/pass-kunci', [PassKunciController::class, 'showQr'])->name('pass-kunci.qr');
Route::post('/peminjaman/{peminjaman}/pass-kunci/generate', [PassKunciController::class, 'generate'])->name('pass-kunci.generate');
Route::get('/ambil-kunci', [PassKunciController::class, 'scanPage'])->name('pass-kunci.scan');
Route::post('/ambil-kunci/redeem', [PassKunciController::class, 'redeem'])->name('pass-kunci.redeem');
```

All under `['auth', 'role:murid', 'check.blocked']`. No new middleware needed.

### Controller: `app/Http/Controllers/Murid/PassKunciController.php`

**`generate(Peminjaman $peminjaman)`** — POST
- Guard: `$peminjaman->murid_id !== auth()->id()` → 403
- Guard: `$peminjaman->status !== 'berlangsung'` → error
- Guard: `$peminjaman->checklistPengembalian === null` → error ("Isi checklist dulu")
- Invalidate all old tokens: `PassKunciToken::where('from_peminjaman_id', $peminjaman->id)->where('status', 'unused')->update(['status' => 'expired'])`
- Generate token: `Str::random(48)` (URL-safe, fits in the 64-char column)
- Create `PassKunciToken`: status=`unused`, `expired_at = now()->addMinutes(5)`
- Return JSON `{ token, expired_at }` — the view calls this via `fetch()` and renders QR in JS

**`showQr(Peminjaman $peminjaman)`** — GET
- Returns a Blade view with a big QR canvas; JS calls `generate` endpoint on load, re-generates on timeout
- QR encodes the token string (not a URL — scanning returns the raw token, sent to `redeem` by JS)

**`scanPage()`** — GET
- Returns a Blade view with a camera feed (`<video>` + `<canvas>`) using `jsQR` CDN library
- On successful decode, JS POSTs token to `redeem`

**`redeem(Request $request)`** — POST
- `$request->validate(['token' => 'required|string'])`
- Find token: `PassKunciToken::where('token', $token)->where('status', 'unused')->first()`
- If not found → JSON `{ error: 'Token tidak valid atau sudah digunakan' }` 422
- If `expired_at < now()` → update status to `expired` → JSON `{ error: 'QR sudah kadaluarsa' }` 422
- B cannot be the same user as A: `$token->from_murid_id === auth()->id()` → 422
- B must not have an active peminjaman (same check as `PeminjamanController::create`)
- **Wrap in `DB::transaction()`**:
  - Close A's peminjaman: `update(['status' => 'selesai', 'jam_selesai_aktual' => now()->format('H:i:s')])`
  - Create B's peminjaman (copy fields, `murid_id = auth()->id()`, `status = 'berlangsung'`)
  - Create `PenyerahanKunci` for B's peminjaman
  - Update token: `status = 'used'`, `used_at = now()`, `used_by = auth()->id()`, `to_peminjaman_id = B's new peminjaman id`
- Return JSON `{ success: true, peminjaman_id: B->id }`
- JS on B's scan page redirects to `murid.peminjaman.show` on success

### QR rendering (A's screen)

Use `qrcode.js` from CDN — no npm:
```html
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
```
```js
QRCode.toCanvas(document.getElementById('qr-canvas'), token, { width: 300 });
```

### QR scanning (B's screen)

Use `jsQR` from CDN:
```html
<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
```
```js
// tick() reads video frame → canvas → jsQR(imageData) → sends token to redeem
```

### Token expiry enforcement

`generate` sets `expired_at = now()->addMinutes(5)`. `redeem` checks `$token->expired_at < now()`. A background cleanup job is NOT needed (sync queue, no scheduler configured) — expired tokens stay in DB as audit log, just set status=`expired` lazily on redeem attempt.

A's QR page should also auto-refresh before expiry: JS tracks countdown, calls `generate` again at T-0 and re-renders the canvas.

### Checklist gate before QR

The "Pass Kunci" button in the `berlangsung` dashboard card should only link to `pass-kunci.qr` if `$peminjaman_aktif->checklistPengembalian !== null`. Otherwise, direct to `murid.checklist.create` first. Do not skip the existing checklist flow — reuse it as-is.

### Pengawas visibility

Add a "Transfer Log" section to `pengawas.kunci.show` view: query `PassKunciToken::where('from_peminjaman_id', $peminjaman->id)->orWhere('to_peminjaman_id', $peminjaman->id)->get()` and display `from_murid`, `used_by` (to_murid), `used_at`.

### Atomic safety note

`redeem` MUST use `DB::transaction()`. A race condition is possible if two users scan simultaneously; the `unique` index on `token` plus the `status = 'unused'` guard inside the transaction is sufficient — the second transaction will find `status != 'unused'` and abort.
