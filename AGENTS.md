# Dashboard Skrining (CKG)

Laravel 12 + Blade + Tailwind CSS 4 + Vite 7 app for CKG (Cek Kesehatan Gratis) screening.

## Commands

```bash
composer setup    # install deps, create .env, key:generate, migrate, npm install && build
composer dev      # runs server + queue + logs + Vite concurrently
composer test     # config:clear then php artisan test
```

- `composer test` runs `config:clear` then `php artisan test`. Tests use SQLite `:memory:` (see `phpunit.xml`).
- `npm run build` / `npm run dev` — Vite only. No JS framework; plain Blade + Tailwind.
- Formatter: `./vendor/bin/pint` (Laravel Pint default style). No Pest (PHPUnit).
- Single test: `php artisan test --filter=ExampleTest`

## Architecture

| Path | Purpose |
|------|---------|
| `app/Http/Controllers/` | Auth, Profile, Screening, Operational, Dashboard, AccountManagement, Wilayah (API) |
| `app/Models/` | User, Respondent, ScreeningSession, ScreeningQuestion, ScreeningOption, ScreeningAnswer |
| `app/Support/CkgScreening/` | 9 screening question definition files (PHP arrays) |
| `app/Support/CkgScreeningCatalog.php` | Loads question files, provides `categoryOrder()`, `nextSlug()`, `firstSlug()` |
| `app/Support/TicketNumberGenerator.php` | Generates unique ticket numbers for sessions |
| `app/Http/Middleware/EnsureCanAccessDashboard.php` | Restricts `/dashboard/*` to `admin`/`super_admin` roles |
| `routes/web.php` | All routes (login, wilayah API, auth-protected operational + screening + dashboard) |
| `database/migrations/` | 15 migrations: users, cache, jobs, respondents, questions/options/answers, sessions |

## Domain structure

- **Screening wizard**: `/skrining/create` captures respondent data, then wizard at `/skrining/kuesioner/{kategori}` walks through categories. Session stores `respondent_id` + `session_id`.
- **Categories** (slug → code, from `CkgScreening/categories.php`): `demografi-dewasa` → `demografi_dewasa`, `faktor-risiko-kanker-usus` → `kanker_usus`, `faktor-risiko-tb` → `tb_dewasa_lansia`, `hati` → `hati`, `kesehatan-jiwa` → `kesehatan_jiwa`, `risiko-kanker-paru` → `kanker_paru`, `perilaku-merokok` → `perilaku_merokok`, `aktivitas-fisik` → `aktivitas_fisik`, `kanker-leher-rahim` → `kanker_leher_rahim`. Use slugs in URLs, codes in DB `screening_questions.category`.
- **Gender filtering**: Male respondents skip `kanker_leher_rahim` category entirely and `pregnancy_status` question in demografi.
- **Risk scoring**: Sum of `screening_answers.score` per session → 0-2 = Rendah, 3-4 = Sedang, ≥5 = Tinggi, stored on `ScreeningSession`.
- **Operational flow**: `/individu` (schedule) → `/pelayanan` (service: `belum_diperiksa` → `sedang_diperiksa` → `selesai_pemeriksaan` → send report). Nakes input stored as JSON in `session.service_notes`.
- **Dashboard**: `/dashboard/overview`, `/dashboard/faktor-risiko`, `/dashboard/kesehatan-mental`, `/dashboard/perilaku-merokok`, `/dashboard/aktivitas-fisik`, `/dashboard/risk-scoring` — all protected by `dashboard.access` middleware.
- **PDF reports** via `barryvdh/laravel-dompdf`: `/pelayanan/{session}/rapor/pdf`.

## Testing quirks

- Tests use `sqlite` + `:memory:`. No external database needed.
- Only 2 example tests (`tests/Unit/ExampleTest.php`, `tests/Feature/ExampleTest.php`).
- Run a single test: `php artisan test --filter=ExampleTest`

## Conventions

- `.editorconfig`: 4-space indent (2-space for yaml). LF line endings.
- `DB_CONNECTION=mysql` in local `.env` (dev), `sqlite` in `.env.example` and tests.
- `SESSION_DRIVER=file` in local `.env`, `database` in `.env.example`.
