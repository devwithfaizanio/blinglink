---
name: blinglink
description: Comprehensive development guide, architectural layout, API routes, database schemas, and operational workflows for the BlingLink Laravel 12 application. Use this skill when developing, refactoring, or extending BlingLink.
---

# BlingLink Project Skill & Guide

BlingLink is a modern social networking, mentorship, matchmaking, and community platform built on **Laravel 12** and **PHP 8.2+**.

---

## 🛠 Tech Stack & Core Dependencies

- **Framework**: Laravel 12 (PHP ^8.2)
- **Authentication**: Laravel Sanctum (`auth:sanctum`)
- **API Documentation**: Dedoc Scramble (`/docs/api/v1`, `/docs/v1.json`)
- **Real-time Messaging**: Pusher Broadcaster (`pusher/pusher-php-server`, `laravel-echo`)
- **Push Notifications**: Firebase PHP SDK (`kreait/firebase-php`)
- **Payments**: Stripe (`stripe/stripe-php`)
- **AI Concierge**: OpenAI PHP (`openai-php/laravel`)
- **Asset Bundling**: Vite with TailwindCSS v4 (`@tailwindcss/vite`)
- **Dev Utilities**: Laravel Telescope, Laravel Pail, Concurrently, Laravel Pint

---

## 📁 Repository Structure

```text
blinglink/
├── app/
│   ├── Events/               # Real-time WebSocket broadcasting events
│   │   ├── CommunityMessageSent.php
│   │   ├── ConnectionMessageSent.php
│   │   ├── MatchmakerMessageSent.php
│   │   └── MentorMessageSent.php
│   ├── Functions/            # Global helper functions (GlobalFunctions.php)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── api/v1/       # Mobile & Client API controllers
│   │   │   │   ├── admin/    # Admin portal APIs (Blogs, Users, Chats, Trophies)
│   │   │   │   └── auth/     # Auth APIs (UserController, ForgotPasswordController)
│   │   │   └── web/v1/       # Web portal controllers
│   │   └── Middleware/       # Custom middleware (e.g. role checking)
│   ├── Models/               # Eloquent domain models
│   ├── Notifications/api/v1/ # Firebase FCM channels and notifications
│   ├── Providers/            # Service providers
│   ├── Services/             # Business logic (ImageService.php)
│   └── Traits/               # Shared traits (CanResponseTrait, FailedValidation)
├── config/                   # Framework & service configurations
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # Database migration files
│   └── seeders/              # Database seeders
├── routes/
│   ├── api/v1.php            # Primary API v1 routes definition
│   ├── api.php               # API route entry point (prefixes /v1)
│   ├── channels.php          # Pusher private channel authorization rules
│   └── web.php               # Web routes & Scramble documentation routes
└── resources/                # Views, CSS (Tailwind v4), JavaScript
```

---

## 🗄 Core Domain Models

| Model | Description |
| :--- | :--- |
| `User` | Main account entity; supports roles (`user`, `admin`, `mentor`, `matchmaker`), subscriptions, trophies, account status. |
| `Community` | Social community groups with status (`pending`, `approved`, etc.). |
| `CommunityMessage` | Messages posted within a specific community. |
| `Connection` | Friend / networking connections between regular users (Status: pending, accepted, rejected). |
| `ConnectionChat` | Direct messages between connected users. |
| `MentorProfile` | Profile details for users offering mentorship. |
| `MatchmakerProfiles` | Profile details for matchmaking professionals. |
| `ConnectionToMentor` | Active paid/assigned link between a user and a mentor. |
| `ConnectionToMatchmaker` | Active paid/assigned link between a user and a matchmaker. |
| `MentorChat` | Direct messaging between a user and their mentor. |
| `MatchMakerChat` | Direct messaging between a user and their matchmaker. |
| `Event` & `EventAttendee` | Events created in the platform and attendee tracking. |
| `PaymentHistory` | Log of transactions for mentorship/matchmaker connections. |
| `Report` | Moderation reports submitted by users against other users/content. |
| `Trophy` | Gamification trophies assigned to users by admins. |
| `Blog` | Content articles managed by admins. |
| `ForgotPasswordOtp` | Temporary OTP storage for password resets. |

---

## 🌐 Key API Routes Overview (`routes/api/v1.php`)

### Public Routes
- `POST /api/v1/check-user-availability` - Validate username/email availability.
- `POST /api/v1/register` - Create new user account.
- `POST /api/v1/login` - Authenticate & receive Sanctum token.
- `POST /api/v1/forgot_password/*` - OTP request, verification & password reset.

### Authenticated User Routes (`auth:sanctum`)
- **Profile & Account**: `GET /profile`, `POST /update-profile`, `POST /switch-role`, `GET /users`, `GET /user/status-by-role`.
- **Matchmaker/Mentor Profiles**: `POST /matchmaker/profile`, `POST /mentor/profile`.
- **Connections**: `GET /connections/suggested`, `GET /connections`, `POST /connections/send`, `POST /connections/accept`, `POST /connections/reject`, `POST /connections/cancel`.
- **Connection Chat**: `GET /connection-chat/user-list`, `GET /connection-chat/chat/{id}`, `POST /connection-chat/send_message`.
- **Mentors & Matchmakers**: `POST /connect-mentor`, `POST /connect-matchmaker`, `GET /my-mentors`, `GET /my-matchmakers`.
- **Mentor Chat**: `GET /mentor-chat/user-list`, `GET /mentor-chat/chat/{id}`, `POST /mentor-chat/send_message`.
- **Matchmaker Chat**: `GET /matchmaker-chat/user-list`, `GET /matchmaker-chat/chat/{id}`, `POST /matchmaker-chat/send_message`.
- **Communities**: `GET/POST /communities`, `GET/DELETE /communities/{id}`, `POST /communities/add_members`, `POST /communities/remove_members`, DMs via `/communities/messages`.
- **Events**: `GET /events/all`, `POST /events/create`, `GET /events/{id}`, `POST /events/{id}/join`, `POST /events/{id}/cancel-join`.
- **AI Concierge**: `POST /recommended-clubs` (venue recommendations), `GET /inspiration` (relationship inspiration).
- **Payments**: `GET /generate_Ephemeral_Key` (Stripe ephemeral key generation).
- **Reports**: `POST /reports`.

### Admin Routes (`auth:sanctum` + `role:admin`)
- **User Management**: `GET /admin/users/list`, `GET /admin/users/detail/{id}`, `POST /admin/users/verify`, `POST /admin/users/account-status-update`.
- **Blog Management**: `GET/POST/DELETE /admin/blogs/*`.
- **Chat Oversight**: `GET /admin/chats/connection_chats_user_list`, `GET /admin/chats/matchmaker_chats_user_list`, `GET /admin/chats/mentor_chats_user_list`.
- **Trophies**: `GET/POST /admin/trophy/*` (create, update, assign, remove).
- **Reports Management**: `GET/POST /admin/reports`, `/admin/reports-update`.

---

## ⚡ Development & Operations Command Cheat Sheet

```bash
# 1. Start all development services concurrently (PHP server, Queue listener, Pail logs, Vite)
npm run dev

# 2. Run Database Migrations
php artisan migrate

# 3. Run Test Suite
composer test
# or
php artisan test

# 4. Code Formatting (Laravel Pint)
vendor/bin/pint

# 5. Clear Caches
php artisan optimize:clear

# 6. Interactive Shell
php artisan tinker
```

---

## 💡 Key Architectural Guidelines & Conventions

1. **API Responses**: Always use `CanResponseTrait` for returning JSON responses to maintain uniform API structure (`successResponse`, `errorResponse`).
2. **Real-time Communication**: When sending messages in chats or communities, trigger the corresponding Event (`ConnectionMessageSent`, `MentorMessageSent`, etc.) so Pusher broadcasts in real-time to active channels.
3. **Push Notifications**: Use `FirebaseChannel` to push FCM notifications for offline users alongside real-time broadcasts.
4. **Role Enforcement**: Use the `role` middleware (e.g. `role:admin`) when defining restricted routes.
5. **API Documentation**: OpenAPI documentation is automatically generated by Scramble. Access the UI at `/docs/api/v1` during development.
