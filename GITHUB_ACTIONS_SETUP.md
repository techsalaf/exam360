# GitHub Actions Auto-Deployment Setup

This guide sets up automated deployment from GitHub to your Namecheap cPanel hosting.

## 📋 Prerequisites

- ✅ GitHub repository (techsalaf/exam360)
- ✅ Namecheap cPanel with SSH access
- ✅ cPanel user: `pnzjqabw`
- ✅ Deployment path: `/home/pnzjqabw/cbt`
- ✅ Git already initialized on server

## 🚀 Setup Steps

### Step 1: Generate SSH Key on Namecheap Server

SSH into your Namecheap server and run the setup script:

```bash
cd /home/pnzjqabw/cbt
bash scripts/setup-github-deployment.sh
```

This will:
- Generate an SSH key pair (if it doesn't exist)
- Display the **private key** that you'll add to GitHub Secrets
- Display the **public key** (already authorized on server)

### Step 2: Add Private Key to GitHub Secrets

1. Go to: https://github.com/techsalaf/exam360/settings/secrets/actions
2. Click **"New repository secret"**
3. Fill in:
   - **Name:** `NAMECHEAP_SSH_KEY`
   - **Value:** Paste the entire **private key** from Step 1
4. Click **"Add secret"**

### Step 3: Initialize Git on Server (if not done)

If you haven't already set up git on the server:

```bash
cd /home/pnzjqabw/cbt
git init
git remote add origin https://github.com/techsalaf/exam360.git
git fetch origin main
git reset --hard origin/main
```

### Step 4: Test the Deployment

Make a small change to your code locally:

```bash
# On your local machine
echo "# Test" >> README.md
git add README.md
git commit -m "test: trigger deployment"
git push origin main
```

Then watch the action run:

1. Go to: https://github.com/techsalaf/exam360/actions
2. You should see a workflow running
3. Click on it to see real-time logs
4. Once it completes (green ✅), your code is deployed!

## 🔄 What Happens on Each Push

When you push to the `main` branch, GitHub Actions will automatically:

1. ✅ Pull latest code from GitHub
2. ✅ Install PHP dependencies (composer)
3. ✅ Install Node dependencies (npm)
4. ✅ Build frontend assets (npm run build)
5. ✅ Run database migrations
6. ✅ Clear application caches
7. ✅ Optimize the application

**Duration:** Usually 2-5 minutes depending on build size

## 📝 Workflow File

The workflow is defined in: `.github/workflows/deploy.yml`

Key configuration:
- **Trigger:** Pushes to `main` branch
- **Server:** cbt.my360school.com
- **User:** pnzjqabw
- **Path:** /home/pnzjqabw/cbt
- **Includes:** Migrations, cache clearing, asset building

## 🐛 Troubleshooting

### "Permission denied (publickey)"

**Solution:** Ensure the public key is in `~/.ssh/authorized_keys`:

```bash
# On Namecheap server
cat ~/.ssh/github_actions.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

### "git fetch origin main: command not found"

**Solution:** Ensure git is installed on the server:

```bash
which git
# If not found, contact Namecheap support to install git
```

### Workflow shows as "failed"

1. Go to: https://github.com/techsalaf/exam360/actions
2. Click the failed workflow
3. Check the error logs (usually in the "Deploy via SSH" step)
4. Common issues:
   - Wrong SSH key in secrets
   - Missing permissions on directories
   - Database migration errors
   - Disk space full

### Check Server Logs

SSH into server and check:

```bash
# Application error log
tail -f /home/pnzjqabw/cbt/storage/logs/laravel.log

# Check if files were actually updated
ls -la /home/pnzjqabw/cbt/
```

## 🔐 Security Best Practices

1. **Never commit the private key** to the repository
2. **Secrets are encrypted** and masked in logs
3. **SSH key is ed25519** (more secure than RSA)
4. **Deploy key is specific** to GitHub Actions only
5. **Keep the private key** safe and secret

## 📊 Monitoring Deployments

View all deployment history:
- GitHub Actions: https://github.com/techsalaf/exam360/actions
- Filter by workflow: "Deploy to Namecheap cPanel"
- Each run shows: status, duration, logs, and timestamp

## 🛠️ Manual Deployment (Fallback)

If GitHub Actions fails and you need to deploy manually:

```bash
ssh pnzjqabw@cbt.my360school.com
cd /home/pnzjqabw/cbt
git fetch origin main
git reset --hard origin/main
composer install --no-dev
npm ci && npm run build
php artisan migrate --force
php artisan cache:clear
php artisan optimize
```

## 📚 Additional Resources

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Laravel Deployment Guide](https://laravel.com/docs/11/deployment)
- [Namecheap cPanel SSH Guide](https://www.namecheap.com/support/knowledgebase/article.aspx/9598/2222/how-to-use-sftp-and-ssh-on-shared-hosting/)

---

**Setup Date:** 2026-06-19  
**Last Updated:** 2026-06-19  
**Deployment Target:** cbt.my360school.com (/home/pnzjqabw/cbt)
