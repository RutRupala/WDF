# StudentHub Local & Production Deployment Guide
**Subject:** Web Development Frameworks (ITUE203) - Practical 15  
**Institution:** Charotar University of Science and Technology (CHARUSAT)

---

## 1. Apache Virtual Host Setup (Intermediate Extension)

To configure `http://studenthub.local` on your local XAMPP Apache server:

### Step 1: Edit Windows Hosts File
Open Notepad as **Administrator**, navigate to:
`C:\Windows\System32\drivers\etc\hosts`
Add the following entry at the bottom:
```text
127.0.0.1    studenthub.local
```

### Step 2: Configure Apache Virtual Hosts (`httpd-vhosts.conf`)
Open `C:\xampp\apache\conf\extra\httpd-vhosts.conf` and append:
```apache
<VirtualHost *:80>
    ServerAdmin admin@charusat.edu.in
    DocumentRoot "E:/CLG/WEB/Prac/P-15"
    ServerName studenthub.local
    ErrorLog "logs/studenthub-error.log"
    CustomLog "logs/studenthub-access.log" common
    <Directory "E:/CLG/WEB/Prac/P-15">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Step 3: Enable Virtual Hosts in `httpd.conf`
Ensure the following line is uncommented in `C:\xampp\apache\conf\httpd.conf`:
```apache
Include conf/extra/httpd-vhosts.conf
```

### Step 4: Restart Apache Server
Open the XAMPP Control Panel and restart the Apache service.  
Navigate to `http://studenthub.local` in your browser.

---

## 2. MySQL Database Setup
1. Launch MySQL from XAMPP Control Panel.
2. Open phpMyAdmin at `http://localhost/phpmyadmin`.
3. Create database `studenthub_db` with `utf8mb4_unicode_ci`.
4. Click **Import** and choose `P-8/schema.sql` (or `P-15/database/studenthub.sql`).
5. Execute import. All 5 tables (`users`, `students`, `events`, `registrations`, `audit_logs`) and stored procedures will be created.

---

## 3. Directory Permissions & File Security
- The directory `uploads/posters/` requires write permissions (`chmod 775` or full write in Windows).
- The `.htaccess` file inside `uploads/posters/` disables script execution to prevent malicious webshell uploads.
- CSRF tokens and BCRYPT password hashes are enforced on all POST forms.
