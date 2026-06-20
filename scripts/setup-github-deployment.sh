#!/bin/bash

# GitHub Actions Deployment Setup Script for Namecheap cPanel
# This script sets up SSH key authentication for automated deployments

echo "🚀 Setting up GitHub Actions deployment..."
echo ""

# Create .ssh directory if it doesn't exist
mkdir -p ~/.ssh
chmod 700 ~/.ssh

# Check if key already exists
if [ -f ~/.ssh/github_actions ]; then
    echo "⚠️  SSH key already exists at ~/.ssh/github_actions"
    echo "Existing key will be used."
else
    # Generate SSH key pair (no passphrase for automated deployment)
    echo "📝 Generating SSH key pair..."
    ssh-keygen -t ed25519 -f ~/.ssh/github_actions -N "" -C "github-actions@cbt.my360school.com"
    echo "✅ SSH key pair generated successfully"
fi

echo ""
echo "🔐 SSH Public Key (add to GitHub Secrets):"
echo "=========================================="
cat ~/.ssh/github_actions.pub
echo ""

echo ""
echo "📋 SETUP INSTRUCTIONS:"
echo "=========================================="
echo ""
echo "1. Go to GitHub repository: https://github.com/techsalaf/exam360"
echo ""
echo "2. Navigate to: Settings → Secrets and variables → Actions"
echo ""
echo "3. Click 'New repository secret' and add:"
echo "   Name: NAMECHEAP_SSH_KEY"
echo "   Value: Copy the PRIVATE key below (keep it SECRET!):"
echo ""
echo "🔑 SSH Private Key (add to GitHub Secrets):"
echo "=========================================="
cat ~/.ssh/github_actions
echo ""
echo ""
echo "4. Click 'Add secret' to save"
echo ""
echo "5. Authorize SSH key for this user (already done if key exists in .ssh)"
echo "   Current user: $(whoami)"
echo "   Home directory: $HOME"
echo ""
echo "✅ Setup complete! Your deployment is ready."
echo ""
echo "💡 TEST: Try pushing to main branch and watch the Action run!"
