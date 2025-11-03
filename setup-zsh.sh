#!/bin/bash

echo "========================================="
echo "Installing Zsh and Oh My Zsh Setup"
echo "========================================="

# Install Zsh if not already installed
if ! command -v zsh &> /dev/null; then
    echo "Installing Zsh..."
    # For Git Bash on Windows, we'll use a pre-compiled Zsh binary
    mkdir -p ~/zsh-install
    cd ~/zsh-install

    # Download zsh for Git Bash
    curl -L https://github.com/git-for-windows/git/releases/download/v2.42.0.windows.1/MinGit-2.42.0-64-bit.zip -o mingit.zip

    echo "Zsh needs to be installed manually on Windows."
    echo "Please follow these steps:"
    echo "1. Download MSYS2 from https://www.msys2.org/"
    echo "2. Install MSYS2"
    echo "3. Open MSYS2 terminal and run: pacman -S zsh"
    echo ""
    echo "Or use Windows Subsystem for Linux (WSL) for better Zsh support"
    echo ""
else
    echo "Zsh is already installed!"
fi

# Check if Oh My Zsh is installed
if [ -d "$HOME/.oh-my-zsh" ]; then
    echo "Oh My Zsh is already installed!"
else
    echo "Installing Oh My Zsh..."
    sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)" "" --unattended
fi

# Install zsh-autosuggestions
echo "Installing zsh-autosuggestions plugin..."
if [ ! -d "${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autosuggestions" ]; then
    git clone https://github.com/zsh-users/zsh-autosuggestions ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autosuggestions
else
    echo "zsh-autosuggestions already installed!"
fi

# Install zsh-syntax-highlighting
echo "Installing zsh-syntax-highlighting plugin..."
if [ ! -d "${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting" ]; then
    git clone https://github.com/zsh-users/zsh-syntax-highlighting.git ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting
else
    echo "zsh-syntax-highlighting already installed!"
fi

# Install zsh-completions
echo "Installing zsh-completions plugin..."
if [ ! -d "${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-completions" ]; then
    git clone https://github.com/zsh-users/zsh-completions ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-completions
else
    echo "zsh-completions already installed!"
fi

# Backup existing .zshrc if it exists
if [ -f "$HOME/.zshrc" ]; then
    cp "$HOME/.zshrc" "$HOME/.zshrc.backup"
    echo "Backed up existing .zshrc to .zshrc.backup"
fi

# Create/Update .zshrc with plugins
cat > "$HOME/.zshrc" << 'EOF'
# Path to your oh-my-zsh installation.
export ZSH="$HOME/.oh-my-zsh"

# Set theme
ZSH_THEME="robbyrussell"

# Plugins
plugins=(
    git
    zsh-autosuggestions
    zsh-syntax-highlighting
    zsh-completions
    docker
    docker-compose
    npm
    node
    composer
    laravel
    vscode
)

# Load Oh My Zsh
source $ZSH/oh-my-zsh.sh

# User configuration
export LANG=en_US.UTF-8

# Preferred editor
export EDITOR='vim'

# Aliases
alias ll='ls -alF'
alias la='ls -A'
alias l='ls -CF'
alias cls='clear'

# Enable autosuggestions
ZSH_AUTOSUGGEST_HIGHLIGHT_STYLE="fg=#666666"

# Git aliases
alias gst='git status'
alias gco='git checkout'
alias gcm='git commit -m'
alias gp='git push'
alias gl='git pull'

EOF

echo ""
echo "========================================="
echo "Setup Complete!"
echo "========================================="
echo ""
echo "Next steps:"
echo "1. Close this terminal and open a new one"
echo "2. Git Bash is now your default terminal"
echo "3. To use Zsh, type 'zsh' in your terminal"
echo ""
echo "Installed plugins:"
echo "- zsh-autosuggestions (predictions as you type)"
echo "- zsh-syntax-highlighting (colorful syntax)"
echo "- zsh-completions (better tab completions)"
echo ""
echo "Note: For Windows, consider using WSL for full Zsh support"
echo ""
