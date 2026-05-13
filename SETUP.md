# QAMS — Laravel + MySQL Setup Guide (XAMPP)

## Requirements
- PHP 8.2+
- Composer
- XAMPP (Apache + MySQL)
- Laravel 11

---

## Step 1: Create a Fresh Laravel Project

Open terminal / XAMPP Shell and run:

```bash
composer create-project laravel/laravel qams
cd qams
```

## Step 2: Copy Project Files

Copy all the files from this download into your `qams/` Laravel folder:
- `app/` → into `qams/app/`
- `database/` → into `qams/database/`
- `resources/views/` → into `qams/resources/views/`
- `routes/web.php` → into `qams/routes/web.php`
- `.env.example` → rename to `.env`

## Step 3: Create MySQL Database

1. Start XAMPP (Apache + MySQL)
2. Open **phpMyAdmin**: http://localhost/phpmyadmin
3. Create a new database named: `qams_db`

## Step 4: Configure .env

Edit the `.env` file:

```env
APP_NAME=QAMS
APP_URL=http://localhost/qams/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qams_db
DB_USERNAME=root
DB_PASSWORD=
```

## Step 5: Generate App Key

```bash
php artisan key:generate
```

## Step 6: Register the CheckRole Middleware

Open `bootstrap/app.php` and add the middleware alias:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

## Step 7: Run Migrations

```bash
php artisan migrate
```

## Step 8: Seed the Database (Default Users)

```bash
php artisan db:seed
```

This creates:
| Role    | Username  | Password   |
|---------|-----------|------------|
| Admin   | admin     | admin123   |
| Teacher | teacher1  | teacher123 |
| Student | student1  | student123 |

## Step 9: Run the App

```bash
php artisan serve
```

Open: http://127.0.0.1:8000

---


## File Storage (for pictures/file uploads)

```bash
php artisan storage:link
```

---

Remove old link: rm -rf public/storage

Run migrations: php artisan migrate
Rollback: php artisan migrate:rollback
Fresh migration: php artisan migrate:fresh
Without cache: composer install --no-cache

Clear all: php artisan optimize:clear
Clear config: php artisan config:clear
Clear routes: php artisan route:clear
Clear views: php artisan view:clear


## Project Structure

```
app/
  Http/
    Controllers/
      Auth/LoginController.php
      Admin/  (DashboardController, StudentController, TeacherController, ClassController, SubjectController, ReportController)
      Teacher/ (DashboardController, QuestionController, QuizController, AssignmentController)
      Student/ (DashboardController, QuizController, AssignmentController)
    Middleware/CheckRole.php
  Models/
    User.php, Student.php, Teacher.php, SchoolClass.php, Subject.php
    Question.php, Quiz.php, QuizAttempt.php, QuizAnswer.php
    Assignment.php, AssignmentSubmission.php
database/
  migrations/  (8 migration files, in order)
  seeders/DatabaseSeeder.php
resources/views/
  layouts/app.blade.php
  auth/login.blade.php
  admin/  (dashboard, students, teachers, classes, subjects, reports)
  teacher/ (dashboard, questions, quizzes, assignments)
  student/ (dashboard, quizzes, assignments, results)
routes/web.php
```

---

## Key Features Implemented

### Admin
- Login, Dashboard with statistics
- Add/Update/Delete Classes and Subjects
- Register students with: name, admission number, father's name, picture, class
- Register teachers with: job history, education
- Assign subjects to teachers
- Block / Unblock student and teacher accounts
- Generate reports for students and teachers

### Teacher
- Login, Dashboard
- Create/manage Question Bank (MCQ, True/False, Short Answer)
- Conduct quizzes with deadline; select questions from bank
- Upload assignments with deadline and total marks
- Extend quiz/assignment deadlines
- View student submissions and grade assignments
- Publish quiz results
- View student performance reports

### Student
- Login, Dashboard
- Attempt quizzes within deadline (auto-marked by system)
- Submit assignment solutions within deadline
- **Automatic zero marks** if assignment submitted late
- View quiz results (when teacher publishes)
- View assignment grades and feedback
- View personal performance report
