# Queue worker via cron (shared hosting)

This project runs the Laravel queue worker using **one cron job** that triggers the scheduler every minute. The scheduler then runs `queue:work` with options so it processes pending jobs and exits (no long-running daemon). This is the recommended approach when you cannot run a persistent `queue:work` process (e.g. on shared hosting).

---

## How it works

1. **Cron** runs every minute and executes: `php artisan schedule:run`
2. **Scheduler** (defined in `bootstrap/app.php`) runs the command:  
   `queue:work --stop-when-empty --max-time=55`
   - **`--stop-when-empty`**: Process all available jobs, then exit (no infinite loop).
   - **`--max-time=55`**: Stop after 55 seconds so the run finishes before the next cron tick (60 seconds).
3. **`withoutOverlapping(2)`**: Prevents a new run if the previous one is still running (lock expires after 2 minutes).

So every minute, the queue is drained (or runs for up to 55 seconds), then the process exits. No extra cron entries and no need for a long-lived worker process.

---

## Step-by-step setup on shared hosting

### 1. Confirm queue driver

Ensure your app uses the **database** queue (default in this project). In `.env`:

```env
QUEUE_CONNECTION=database
```

If you use Redis or another driver, the same cron setup still applies.

### 2. Ensure jobs table exists

Migrations should already have created the `jobs` table. If not:

```bash
php artisan queue:table
php artisan migrate
```

### 3. Add the single cron entry

In your hosting control panel (cPanel, Plesk, etc.) open **Cron Jobs** and add:

| Field    | Value |
|----------|--------|
| Minute   | `*` |
| Hour     | `*` |
| Day      | `*` |
| Month    | `*` |
| Weekday  | `*` |
| Command  | See below |

**Command** (replace `/path-to-your-project` with the full path to your Laravel app, e.g. `/home/username/public_html` or `/home/username/jam-jam`):

```bash
cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Example for a user `john` with app in `public_html/jam-jam`:

```bash
cd /home/john/public_html/jam-jam && php artisan schedule:run >> /dev/null 2>&1
```

- **`cd /path-to-your-project`** – Required so the command runs in the project root.
- **`>> /dev/null 2>&1`** – Discards output (optional; remove if you want to log output to a file, e.g. `>> /home/john/logs/schedule.log 2>&1`).

Save the cron job. It will run every minute.

### 4. Verify

- **List scheduled tasks:**
  ```bash
  php artisan schedule:list
  ```
  You should see `queue:work --stop-when-empty --max-time=55` running every minute.

- **Trigger manually (optional):**
  ```bash
  php artisan schedule:run
  ```
  Then check that queued jobs are processed (e.g. dispatch a test job and confirm it runs).

- **Check queue:**
  ```bash
  php artisan queue:monitor database:default
  ```
  Or inspect the `jobs` table in the database.

### 5. Optional: log schedule output

To capture scheduler output for debugging, change the cron command to:

```bash
cd /path-to-your-project && php artisan schedule:run >> /path-to-your-project/storage/logs/schedule.log 2>&1
```

Ensure `storage/logs` is writable. Rotate or clear this file periodically to avoid it growing too large.

---

## Summary

| What | Detail |
|------|--------|
| **Cron frequency** | Every minute (`* * * * *`) |
| **Cron command** | `cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1` |
| **Scheduled command** | `queue:work --stop-when-empty --max-time=55` (in `bootstrap/app.php`) |
| **Queue driver** | `database` (set in `.env` as `QUEUE_CONNECTION=database`) |

Do **not** add a separate cron entry for `queue:work` or `queue:work --daemon`; the scheduler handles it once per minute with the options above.
