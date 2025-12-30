# ApplyBoard Africa Ltd - System Guide

## Overview

ApplyBoard Africa Ltd is a comprehensive education and travel consulting platform built with PHP and MySQL. The system consists of a public marketing website and three user portals: Student/Client Portal, Agent Dashboard, and Admin/Manager Panel.

---

## System Architecture

### Directory Structure

```
smile-dove/
├── index.php                 # Homepage
├── about.php                 # About page
├── services.php              # Services page
├── agents.php                # Agents info page
├── platform.php              # Platform info page
├── contact.php               # Contact/Inquiry form
├── config/                   # Configuration files
│   ├── config.php            # Database connection & session
│   ├── auth_helper.php       # Authentication functions
│   ├── case_helper.php       # Case management functions
│   └── function.php          # Utility functions
├── user/                     # Client/Student portal
├── agent/                    # Agent dashboard
├── manager/                  # Admin panel
├── uploads/                  # File uploads
└── vendor/                   # Composer dependencies
```

### Database Tables

| Table               | Purpose                                        |
| ------------------- | ---------------------------------------------- |
| `admin`             | Administrator accounts                         |
| `agents`            | Agent profiles and verification status         |
| `users`             | Client/Student accounts                        |
| `inquiries`         | Contact form submissions                       |
| `cases`             | Application cases (study abroad, visa, travel) |
| `commissions`       | Agent commission records                       |
| `notifications`     | System notifications                           |
| `activity_logs`     | Audit trail for all actions                    |
| `documents`         | Uploaded documents                             |
| `case_documents`    | Documents linked to cases                      |
| `case_stages`       | Case stage history                             |
| `agent_performance` | Agent ratings and metrics                      |

---

## User Roles

### 1. Visitor (Public)

- View public marketing pages
- Submit inquiries via contact form
- Register as a client

### 2. Client/Student

- Track application cases
- Upload documents
- View case status and progress
- Communicate with assigned agent

### 3. Agent

- Manage referred clients
- Submit and track cases
- View commissions
- Use referral link to acquire clients

### 4. Admin/Manager

- Verify/reject agents
- Manage all cases
- Approve commissions
- View reports and analytics
- System settings

---

## Panel Features

### Public Website

| Page     | URL             | Purpose                             |
| -------- | --------------- | ----------------------------------- |
| Homepage | `/`             | Landing page with services overview |
| About    | `/about.php`    | Company information                 |
| Services | `/services.php` | Service offerings                   |
| Agents   | `/agents.php`   | Agent information                   |
| Platform | `/platform.php` | Platform features                   |
| Contact  | `/contact.php`  | Inquiry submission form             |

### Client/Student Portal (`/user/`)

| Page            | URL                         | Purpose                     |
| --------------- | --------------------------- | --------------------------- |
| Dashboard       | `/user/`                    | Overview with case summary  |
| Cases           | `/user/cases.php`           | View all cases and status   |
| Documents       | `/user/documents.php`       | Upload and manage documents |
| New Application | `/user/new_application.php` | Submit new application      |
| Profile         | `/user/profile.php`         | Update personal information |
| Notifications   | `/user/notifications.php`   | View system notifications   |
| Login           | `/user/login.php`           | Client authentication       |
| Register        | `/user/register.php`        | New client registration     |
| Logout          | `/user/logout.php`          | End session                 |

### Agent Dashboard (`/agent/`)

| Page          | URL                        | Purpose                            |
| ------------- | -------------------------- | ---------------------------------- |
| Dashboard     | `/agent/`                  | Overview with stats, referral link |
| Clients       | `/agent/clients.php`       | View referred clients              |
| Cases         | `/agent/cases.php`         | Manage client cases                |
| Inquiries     | `/agent/inquiries.php`     | View referred inquiries            |
| Commissions   | `/agent/commissions.php`   | Track earnings                     |
| Verification  | `/agent/verification.php`  | Upload verification documents      |
| Profile       | `/agent/profile.php`       | Update profile, bank details       |
| Notifications | `/agent/notifications.php` | View notifications                 |
| Login         | `/agent/login.php`         | Agent authentication               |
| Register      | `/agent/register.php`      | New agent registration             |
| Logout        | `/agent/logout.php`        | End session                        |

### Admin/Manager Panel (`/manager/`)

| Page          | URL                          | Purpose                     |
| ------------- | ---------------------------- | --------------------------- |
| Dashboard     | `/manager/`                  | System overview, statistics |
| Agents        | `/manager/agents.php`        | Verify/reject agents        |
| Inquiries     | `/manager/inquiries.php`     | Manage contact submissions  |
| Cases         | `/manager/cases.php`         | Full case management        |
| Clients       | `/manager/clients.php`       | Client directory            |
| Commissions   | `/manager/commissions.php`   | Approve/pay commissions     |
| Reports       | `/manager/reports.php`       | Analytics and reports       |
| Notifications | `/manager/notifications.php` | Send system notifications   |
| Activity Logs | `/manager/activity-logs.php` | Audit trail                 |
| Settings      | `/manager/settings.php`      | System configuration        |
| Profile       | `/manager/profile.php`       | Admin account settings      |
| Login         | `/manager/login.php`         | Admin authentication        |
| Logout        | `/manager/logout.php`        | End session                 |

---

## Key Workflows

### 1. Agent Registration & Verification

```
1. Agent visits /agent/register.php
2. Fills registration form (name, email, phone, password)
3. System generates unique Agent Code (AGT-XXXXXX)
4. Agent status set to "pending"
5. Agent uploads verification documents via /agent/verification.php
6. Admin reviews in /manager/agents.php
7. Admin approves or rejects
8. Agent status updated to "verified" or "rejected"
9. Verified agents can use referral links and submit cases
```

### 2. Referral System

```
1. Agent gets referral link: /user/register.php?ref=AGT-XXXXXX
2. Agent shares link with potential clients
3. Client clicks link, referral code stored in cookie (30 days)
4. Client registers, agent_id linked to their account
5. Agent's referral_count incremented
6. Client permanently linked to agent (unless admin override)
```

### 3. Inquiry to Case Conversion

```
1. Visitor submits inquiry via /contact.php
2. If referral cookie exists, inquiry linked to agent
3. Admin reviews in /manager/inquiries.php
4. Admin marks as "contacted" or "resolved"
5. Admin can convert inquiry to case:
   - System creates client account if needed
   - Creates case linked to client and agent
6. Case progresses through stages
```

### 4. Case Management Stages

**Study Abroad Cases:**

1. Assessment
2. Options
3. Application
4. Submission
5. Offer
6. Visa
7. Travel
8. Closed

**Visa Cases:**

1. Assessment
2. Documents
3. Submission
4. Decision
5. Closed

**Travel Cases:**

1. Requirements
2. Booking
3. Completed
4. Closed

### 5. Commission Flow

```
1. Case reaches completion stage
2. Commission automatically generated (via case_helper.php)
3. Admin reviews in /manager/commissions.php
4. Admin approves commission
5. Admin marks as "paid"
6. Amount added to agent's wallet_balance
7. Agent sees commission in /agent/commissions.php
```

---

## Authentication System

### Session Management

Sessions are managed via `config/auth_helper.php`:

- `loginUser($type, $data)` - Create session for user type
- `logout($type)` - Destroy session for user type
- `auth($type)` - Get current authenticated user
- `isLoggedIn($type)` - Check if user is logged in

### Session Keys

| User Type | Session Key                      |
| --------- | -------------------------------- |
| Admin     | `$_SESSION['sdtravels_manager']` |
| Agent     | `$_SESSION['sdtravels_agent']`   |
| Client    | `$_SESSION['sdtravels_user']`    |

### Password Handling

- New registrations use `password_hash()` (bcrypt)
- Login supports both hashed and legacy plaintext passwords
- Plaintext fallback for backward compatibility during migration

---

## Referral Cookie System

```php
// Set referral cookie (30-day expiry)
setcookie("sdtravels_ref", $agent_code, time() + (30 * 24 * 60 * 60), "/");

// Read referral from URL or cookie
$ref_code = $_GET['ref'] ?? $_COOKIE['sdtravels_ref'] ?? null;

// Resolve agent from code
$agent = mysqli_query($conn, "SELECT id FROM agents WHERE agent_code = '$ref_code' AND status = 'verified'");
```

---

## Activity Logging

All significant actions are logged to `activity_logs` table:

```php
logActivity($userId, $userType, $action, $entityType, $entityId, $description);
```

Example actions:

- `login`, `logout`
- `create`, `update`, `delete`
- `approve`, `reject`
- `case_status_updated`
- `commission_approved`

---

## Configuration

### Database Connection (`config/config.php`)

```php
$host = "localhost";
$user = "root";
$pass = "";
$db = "sdtravels";
$conn = mysqli_connect($host, $user, $pass, $db);
```

### Commission Settings (`config/settings.json`)

```json
{
  "commission": {
    "referral_rate": 5.0,
    "case_completion_rate": 10.0,
    "service_rate": 2.5
  }
}
```

---

## File Uploads

### Upload Directories

| Directory                  | Purpose                |
| -------------------------- | ---------------------- |
| `/uploads/documents/`      | General documents      |
| `/uploads/visa_documents/` | Visa-related documents |
| `/uploads/pilgrim_files/`  | Pilgrim service files  |
| `/uploads/temp/`           | Temporary uploads      |
| `/uploads/blog/`           | Blog images            |

### Document Upload Handling

```php
// In case_helper.php
uploadCaseDocument($caseId, $file, $documentType, $uploaderId, $uploaderType);
```

---

## Security Considerations

1. **SQL Injection Prevention**: Use `mysqli_real_escape_string()` for user inputs
2. **Password Security**: Use `password_hash()` for new passwords
3. **Session Security**: Session-based authentication with role checks
4. **File Upload Security**: Validate file types and sizes
5. **XSS Prevention**: Use `htmlspecialchars()` for output

---

## Default Admin Credentials

**Warning**: Change these immediately in production!

```
Email: admin@applyboardafrica.com
Password: (check admin table in database)
```

---

## Quick Start

### Access Points

| Portal          | URL                                           | Credentials          |
| --------------- | --------------------------------------------- | -------------------- |
| Public Site     | http://localhost/smile-dove/                  | -                    |
| Client Portal   | http://localhost/smile-dove/user/login.php    | Register new account |
| Agent Dashboard | http://localhost/smile-dove/agent/login.php   | Register new account |
| Admin Panel     | http://localhost/smile-dove/manager/login.php | See admin table      |

### Testing Flow

1. **Create Admin**: Insert record into `admin` table
2. **Test Agent Flow**:
   - Register agent at `/agent/register.php`
   - Login to admin, approve agent
   - Agent uploads verification docs
   - Admin verifies agent
3. **Test Referral**:
   - Get agent's referral link from dashboard
   - Register client using referral link
   - Verify client linked to agent
4. **Test Case Flow**:
   - Submit inquiry via contact form
   - Convert to case in admin panel
   - Progress through stages
   - Generate and approve commission

---

## Troubleshooting

### Common Issues

| Issue                  | Solution                                              |
| ---------------------- | ----------------------------------------------------- |
| Login fails            | Check password column length (should be VARCHAR(255)) |
| Referral not tracking  | Check cookie is set, agent status is 'verified'       |
| Case not creating      | Verify client and agent exist in database             |
| Commission not showing | Check case is linked to agent                         |

### Database Fixes

```sql
-- Increase password column if needed
ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL;
ALTER TABLE agents MODIFY password VARCHAR(255) NOT NULL;

-- Check referral counts
SELECT id, fullname, referral_count FROM agents;

-- View recent activity
SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 20;
```

---

## Version History

- **Sprint 0**: Project setup, database scaffolding
- **Sprint 1**: Public website, inquiry capture
- **Sprint 2**: Admin dashboard
- **Sprint 3**: Agent onboarding, referral system
- **Sprint 4**: Student portal, documents
- **Sprint 5**: Reporting, commissions

---

## Support

For technical support, contact the development team at Digitide Systems Technologies Ltd.
