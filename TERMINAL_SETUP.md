# Terminal Setup Guide

## What's Been Done:

✅ Git Bash is now set as your default terminal in VS Code
✅ VS Code settings have been updated (.vscode/settings.json)

## Next Steps:

### Option 1: Use WSL2 with Zsh (RECOMMENDED)

This gives you the best Linux terminal experience on Windows.

1. Open PowerShell as Administrator and run:

    ```powershell
    wsl --install -d Ubuntu
    ```

2. Restart your computer when prompted

3. Open Ubuntu from Start Menu and set up your user

4. In Ubuntu terminal, run these commands one by one:

    ```bash
    sudo apt update
    sudo apt install zsh -y
    sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"
    ```

5. Install plugins:

    ```bash
    git clone https://github.com/zsh-users/zsh-autosuggestions ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autosuggestions
    git clone https://github.com/zsh-users/zsh-syntax-highlighting.git ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting
    ```

6. Edit ~/.zshrc:

    ```bash
    nano ~/.zshrc
    ```

    Find the line with `plugins=` and change it to:

    ```
    plugins=(git zsh-autosuggestions zsh-syntax-highlighting docker npm composer)
    ```

    Save (Ctrl+O, Enter, Ctrl+X)

7. Apply changes:

    ```bash
    source ~/.zshrc
    ```

8. Set Zsh as default:
    ```bash
    chsh -s $(which zsh)
    ```

### Option 2: Use Git Bash (Simpler but Limited)

If you don't want to use WSL, you can use the enhanced Git Bash.

1. Open a new terminal in VS Code (it should now be Git Bash)

2. Run the setup script:

    ```bash
    bash setup-zsh.sh
    ```

3. Restart your terminal

## Plugins Installed:

-   **zsh-autosuggestions**: Shows predictions as you type (press → to accept)
-   **zsh-syntax-highlighting**: Colors commands (green=valid, red=invalid)
-   **zsh-completions**: Better tab completion

## VS Code Terminal Configuration:

Your VS Code now has Git Bash as the default terminal. You can switch between terminals using the dropdown in the terminal panel.

## Troubleshooting:

-   If terminal doesn't change, restart VS Code completely
-   Make sure Git is installed from https://git-scm.com/
-   For WSL issues, run: `wsl --update`
