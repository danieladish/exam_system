exam_system

│   create_exam.php
│   dashboard.php
│   dashboard_students.php
│   login.php
│   logout.php
│   seb_config.php
│   show_scores.php
│   show_scores_students.php
│   view_exams.php
│   view_questions.php
│
├───css
│       create_exam.css
│       dashboard_page.css
│       dashboard_students_page.css
│       login_page.css
│       navigation.css
│       seb_config.css
│       tables.css
│
├───img
│       background-img.jpg
│
├───javascript
│       create_exam.js
│
├───php
│       config.php
│       fetch_exam.php
│       generate_config.php
│       login_check.php
│       save_exam.php
│       submit_exam.php
│
└───sql
        database_setup.sql

За създаването на базата данни и добавянето на текстово съдържание е необходимо да се импортира SQL скрипта “database_setup.sql”, която се намира в папката “sql”.  
Стартирайте MySQL и Apache в XAMPP. 
Поставете копирания SQL скрипт в интерфейса на SQL заявки в phpMyAdmin или го импортирайте файла на http://localhost/phpmyadmin/index.php?route=/server/import. 
Необходимо е папката exam_system да бъде поставена в htdocs папката на XAMPP.  Линк за достъп до началната страница: http://localhost/exam_system/dashboard.php.
Необходими данни за вход като преподавател:  teacher1 password123, като студент student1 password456.
Достъпа до базата данни маже да се конфигурира от config.php, който се намира в папката php.
