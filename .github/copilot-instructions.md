# App RPclinic - AI Coding Agent Instructions

## Project Overview
**App RPclinic** is a comprehensive clinic management system built with **Laravel 7** + **Vue 3** + **TailwindCSS**. It handles appointment scheduling, electronic patient records, reception management, billing, exams, and financial operations.

## Architecture Overview

### Multi-Module Structure
Routes are organized by business domain with separate prefixes:
- `/rpclinica/*` - Core clinic operations (appointments, patients, consultations)
- `/app_rpclinic/*` - Modern app-specific features
- `/faturamento/*` - Billing and financial management
- `/laudos/*` - Exam reports and lab results

**Key file**: [app/Providers/RouteServiceProvider.php](app/Providers/RouteServiceProvider.php#L27-L48) - Each module maps to different Controller namespaces.

### Model Organization
**Domain Models** (150+ models) organized in [app/Model/rpclinica](app/Model/rpclinica):
- **Agendamento** (Appointment) - Core domain with related models: `AgendamentoLog`, `AgendamentoSituacao`, `AgendamentoDocumentos`, `AgendamentoGuia`
- **Paciente** (Patient) - Medical history, documents
- **Profissional** (Professional/Staff) - Doctors, specialists with `ProfissionalEspecialidade`, `ProfissionalProcedimento`
- **Agenda** - Schedule management with `AgendaEscala`, `AgendaProcedimentos`, `AgendaProfissionais`
- **Oftalmologia** (Ophthalmology) - Specialized domain with 15+ models (`Oft_*`) for eye exams
- **Financeiro** - Billing, payments: `FaturamentoConta`, `CartaoCredito`, `DocumentoBoleto`
- **WhatsApp Integration** - 8+ models for WhatsApp messaging (`WhastApi`, `WhastSend`, `WhastReceive`, etc.)

**Pattern**: Models rarely have explicit relationships defined—joins are handled manually in controllers/libraries.

### External Libraries & APIs
[app/Bibliotecas](app/Bibliotecas) contains specialized business logic:
- **apiAgendamento.php** - Complex appointment scheduling logic (622 lines)
- **ApiWaMe.php**, **WhatsApp.php** - WhatsApp messaging integrations
- **EnvioEmail.php** - Email service wrapper
- **PDFSign.php** - Digital signature for documents
- **Kentro.php** - Integration with Kentro system
- **simpleXLSX.php**, **SimpleXLS.php** - Excel import/export utilities

## Development Workflow

### Build & Run
```bash
# Backend setup
composer install
php artisan migrate --seed

# Frontend
npm install
npm run dev          # Development build
npm run prod         # Production build
npm run watch        # Watch mode

# Serve
php artisan serve    # Runs on http://localhost:8000
```

### Key Build Files
- [webpack.mix.js](webpack.mix.js) - Compiles 50+ module-specific JS files from [resources/js/rpclinica](resources/js/rpclinica)
- Routes compile via [mix.js()](webpack.mix.js#L1-L45) - one JS file per major feature

## Code Patterns & Conventions

### Database Naming
- **Snake_case tables**: `agendamento`, `paciente`, `profissional` (NOT camelCase)
- **Legacy convention**: Column names often short: `di` (data_inicio), `df` (data_fim), `id_*` for foreign keys
- **Migration location**: [database/migrations](database/migrations) - Recent migrations follow standard timestamp naming

### Query Style
Queries typically manual with `DB::` façade:
```php
use Illuminate\Support\Facades\DB;
// Avoid query builder chains—use raw SQL joins frequently
DB::select("SELECT * FROM agendamento WHERE situacao = ?", [$situacao]);
```

### Helper Functions
[app/Helpers.php](app/Helpers.php) (1450+ lines) contains global utility functions:
- Brazilian state data constants (`ESTADOS`)
- Session-based helpers (routing, user data)
- Imported globally in [composer.json](composer.json#L47)

### Request/Parameter Handling
- Use array destructuring: `$di = $request['di'];` instead of `$request->get('di')`
- Timestamps often passed as Unix milliseconds (convert with `date('Y-m-d', $timestamp)`)
- Agendas passed as comma-separated IDs: `$Agendas = explode(',', $request['agendas'])`

### Frontend Build Strategy
Each major feature has its own compiled JS file:
- [resources/js/rpclinica/agendamentos.js](resources/js/rpclinica/agendamentos.js) → [public/js/rpclinica/agendamentos.js](public/js/rpclinica/agendamentos.js)
- Mix with Vue 3 + Alpine.js for interactivity
- TailwindCSS compiled with PostCSS

## Critical Integration Points

### WhatsApp Messaging System
Models: `WhastApi`, `WhastSend`, `WhastReceive`, `WhastRotina`, `WhastRetornoAgenda`
- Used for appointment confirmations, notifications
- Cron jobs trigger via routes: `/crontab-*` endpoints (see [routes/rpclinica.php](routes/rpclinica.php#L44-L46))

### PDF Generation & Signing
- **DOMPDF** for report generation (`barryvdh/laravel-dompdf`)
- **PDFSign.php** for digital signatures on official documents
- **FPDI** (`setasign/fpdi`) for PDF manipulation

### AWS S3 Integration
- Referenced in `DiretoriosS3` model
- File uploads handled in controllers with `Storage` façade
- Cron route: `/crontab-aws` (config in [config/filesystems.php](config/filesystems.php))

### Ophthalmology Module
Self-contained domain with 15+ specialized models and forms (formularios).
- Routes under `/rpclinica/*` with namespace `App\Http\Controllers\rpclinica`
- Medical exams (acuidade, refração, tonometria) tracked separately

## Common File Locations

| Purpose | Location |
|---------|----------|
| Global functions | [app/Helpers.php](app/Helpers.php) |
| Scheduling logic | [app/Bibliotecas/apiAgendamento.php](app/Bibliotecas/apiAgendamento.php) |
| Routes (primary) | [routes/rpclinica.php](routes/rpclinica.php) |
| Models | [app/Model/rpclinica/](app/Model/rpclinica) |
| Controllers | [app/Http/Controllers/rpclinica/](app/Http/Controllers/rpclinica) |
| Frontend scripts | [resources/js/rpclinica/](resources/js/rpclinica) |
| Build config | [webpack.mix.js](webpack.mix.js) |

## Testing & Quality

- Test framework: **PHPUnit** (8.5.8 or 9.3.3 per [composer.json](composer.json#L30))
- Test structure: [tests/](tests) with `Feature/` and `Unit/` subdirs
- Laravel Tinker available for REPL debugging

## Important Notes

1. **PHP 7.2.5+ or 8.0+**: Ensure compatibility (no typed properties in controllers)
2. **Database timestamps**: Often custom fields `created_at`, `updated_at` not auto-managed
3. **No strict Eloquent ORM usage**: Manual SQL queries common; verify relationships before assuming
4. **Language**: Portuguese naming throughout (controllers, columns, constants)
5. **Environment variables**: Check `.env` for `WHATSAPP_API_KEY`, AWS credentials, database setup
