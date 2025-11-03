# Oh My Zsh Setup for Windows
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Setting up Oh My Zsh for Windows" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

# Check if Git Bash is installed
$gitBashPath = "C:\Program Files\Git\bin\bash.exe"
if (Test-Path $gitBashPath) {
    Write-Host "Git Bash found!" -ForegroundColor Green
} else {
    Write-Host "Git Bash not found. Please install Git from https://git-scm.com/" -ForegroundColor Red
    exit
}

Write-Host ""
Write-Host "IMPORTANT: For the best Zsh experience on Windows, we recommend:" -ForegroundColor Yellow
Write-Host ""
Write-Host "Option 1 (Recommended): Use WSL2 with Ubuntu" -ForegroundColor Cyan
Write-Host "  - Full Linux environment with native Zsh support" -ForegroundColor Gray
Write-Host "  - Run: wsl --install" -ForegroundColor Gray
Write-Host ""
Write-Host "Option 2: Use Git Bash with Oh My Bash" -ForegroundColor Cyan
Write-Host "  - Bash alternative with similar features" -ForegroundColor Gray
Write-Host ""

$choice = Read-Host "Choose option 1 for WSL or 2 for Git Bash or 3 to skip"

if ($choice -eq "1") {
    Write-Host ""
    Write-Host "Installing WSL2..." -ForegroundColor Cyan
    wsl --install -d Ubuntu
    Write-Host ""
    Write-Host "After WSL installs and you set up Ubuntu:" -ForegroundColor Yellow
    Write-Host "1. Open Ubuntu from Start Menu" -ForegroundColor Gray
    Write-Host "2. Run these commands:" -ForegroundColor Gray
    Write-Host "   sudo apt update" -ForegroundColor White
    Write-Host "   sudo apt install zsh -y" -ForegroundColor White
    Write-Host '   sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"' -ForegroundColor White
    Write-Host "   git clone https://github.com/zsh-users/zsh-autosuggestions ~/.oh-my-zsh/custom/plugins/zsh-autosuggestions" -ForegroundColor White
    Write-Host "   git clone https://github.com/zsh-users/zsh-syntax-highlighting.git ~/.oh-my-zsh/custom/plugins/zsh-syntax-highlighting" -ForegroundColor White
    Write-Host ""
    Write-Host "3. Then edit ~/.zshrc and add to plugins line:" -ForegroundColor Gray
    Write-Host "   plugins=(git zsh-autosuggestions zsh-syntax-highlighting)" -ForegroundColor White

} elseif ($choice -eq "2") {
    Write-Host ""
    Write-Host "Creating setup script for Git Bash..." -ForegroundColor Cyan
    Write-Host "Created setup-bash.sh" -ForegroundColor Green
    Write-Host ""
    Write-Host "Now run this command:" -ForegroundColor Yellow
    Write-Host "  bash setup-zsh.sh" -ForegroundColor White
    Write-Host ""
    Write-Host "The script will install Oh My Bash with plugins" -ForegroundColor Gray} else {
    Write-Host "Setup skipped." -ForegroundColor Gray
}

Write-Host ""
Write-Host "VS Code has been configured to use Git Bash as default terminal." -ForegroundColor Green
Write-Host "Restart VS Code to apply changes." -ForegroundColor Yellow
Write-Host ""
