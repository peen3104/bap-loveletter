# 📩 BAP - Letter Management & Approval System

A web-based letter submission and approval system built using Laravel.  
This application enables structured document submission and review workflows between Staff and Supervisors (Kabag).

---

## 🚀 Live Demo
(Coming Soon)

---

## 🛠 Tech Stack

- Laravel
- PHP
- PostgreSQL
- Blade Template Engine
- Railway (Deployment)
- Git & GitHub

---

## 🔐 Authentication & Role System

Users must register before accessing the system.

Role assignment is automatically determined based on the email format during registration:

- Email containing the word **"staff"** → Assigned as **Staff**
- Email containing the word **"kabag"** → Assigned as **Supervisor (Kabag)**

This logic is validated during registration and mapped directly to predefined roles stored in the PostgreSQL database.

After successful registration:
- Users are automatically redirected to their respective dashboard based on role.
- Each role has different access permissions and workflows.

---

## ✨ Features

- User authentication (Register & Login)
- Automatic role detection via email keyword
- Role-based access control (Staff & Supervisor)
- Letter submission system
- Letter approval & rejection workflow
- Letter status tracking
- Separate dashboard for each role
- Secure data persistence using PostgreSQL

---

## 🏗 System Workflow

### 🧑‍💼 Staff Flow
1. Register using an email containing **"staff"**
2. Login → Redirected to Staff Dashboard
3. Create and submit a letter
4. Monitor submission status
5. If rejected → Edit and resubmit

### 👩‍💼 Supervisor (Kabag) Flow
1. Register using an email containing **"kabag"**
2. Login → Redirected to Supervisor Dashboard
3. Review incoming letter submissions
4. Approve → Letter marked as approved
5. Reject → Letter returned to Staff for revision

---

## 🗄 Database Design

- Roles are predefined in PostgreSQL
- Users are mapped to roles upon registration
- Letters are linked to the submitting user
- Status is dynamically updated based on supervisor action

---

## 🎯 Key Highlights

- Implements role-based authorization
- Automated role assignment logic
- Structured approval workflow
- Clean separation between user responsibilities
- Built using Laravel MVC architecture

---

## 📌 Project Purpose

This system was built to simulate a real-world administrative approval workflow, focusing on backend logic, authentication, role-based authorization, and database management.

---
