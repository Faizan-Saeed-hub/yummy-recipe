# Changes Made for Recipe Project Setup

## Files Added
- `requirements.txt`
  - Added at root of the Laravel project.
  - Lists Python ML dependencies and the main install commands for PHP, Node.js, Python, and database setup.

- `CHANGES.md`
  - This summary file describing what was added and verified.

## Work Completed
- Verified Laravel dependencies are installed with Composer.
- Verified Python ML dependencies are installed for `models/api.py`.
- Added `flask-cors` to the model dependencies and ensured the ML API can accept cross-origin requests.
- Added new user profile fields for age, dietary restrictions, and health conditions.
- Added recipe preference storage to allow profile-based recipe suggestions.
- Added a database migration to store the new profile fields in the `users` table.
- Ensured the MySQL database exists:
  - Database name: `yummy_recipes`
  - Character set: `utf8mb4`
- Verified Laravel migrations have been run successfully.

## Verified Runtime
- Laravel app running at: `http://localhost:8000`
- Flask ML API running at: `http://localhost:5000`
- ML status endpoint: `http://localhost:5000/status`

## No Structure Changes
- No directories were moved or removed.
- Only new project files were added and existing files were updated to enable the website-model integration.

## Notes
- The website already uses `ImageRecognitionControlller` to send images directly to the ML API.
- The Flask API now exposes a health-check endpoint and supports CORS so the Laravel site can communicate with it.
- If Node.js is not installed yet, install it from https://nodejs.org and run `npm install` in the project root.

## What to Run Next
1. Start the database service (MySQL via XAMPP).
2. Run Flask model API using the working virtual environment:
   - `cd c:\xampp\htdocs\RECEPIE`
   - `& .venv\Scripts\python.exe .\RECEPIE\models\api.py`
3. Run Laravel server:
   - `cd c:\xampp\htdocs\RECEPIE\RECEPIE`
   - `& "c:\xampp\php\php.exe" artisan serve --host=localhost --port=8000`
4. Open the website:
   - `http://localhost:8000`

## Important Python Note
- Use the root `.venv` virtual environment at `c:\xampp\htdocs\RECEPIE\.venv`.
- The inner `RECEPIE\venv` is not usable because it points to a missing Python installation.
