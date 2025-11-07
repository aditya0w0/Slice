<?php
// Simple helper to append a short entry to DEV_NOTES.md and DEV_NOTES_SESSION.md
// Usage: php scripts/doc-change.php "Optional message"

date_default_timezone_set('UTC');

$cwd = __DIR__ . '/../';
chdir($cwd);

$message = isset($argv[1]) && trim($argv[1]) !== '' ? $argv[1] : null;
$auto = in_array('--auto', $argv, true);

// If auto, get last commit message
if ($auto && !$message) {
    $message = trim(shell_exec('git log -1 --pretty=%B 2>/dev/null')) ?: 'Auto documentation after commit';
}

if (!$message) {
    // fallback to asking the user (non-interactive fallback)
    $message = 'Code changes (no message provided)';
}

// get list of files changed in last commit
$files = trim(shell_exec('git diff --name-only HEAD^ HEAD 2>/dev/null')) ?: trim(shell_exec('git status --porcelain 2>/dev/null'));
$fileList = [];
if ($files) {
    foreach (preg_split('/\R+/', $files) as $line) {
        $line = trim($line);
        if ($line === '') continue;
        // if porcelain output (XY file), extract filename at end
        if (preg_match('/^[AMDRC]\s+(.*)$/', $line, $m)) {
            $fileList[] = $m[1];
        } else {
            $fileList[] = $line;
        }
    }
}

$time = (new DateTime())->format('Y-m-d H:i:s');
$entry = "\n### {$time} — {$message}\n\n";
if (count($fileList)) {
    $entry .= "Files changed:\n";
    foreach ($fileList as $f) {
        $entry .= "- {$f}\n";
    }
    $entry .= "\n";
}

$entry .= "Notes:\n- Documented automatically by scripts/doc-change.php\n\n";

$devNotes = __DIR__ . '/../DEV_NOTES.md';
$sessionNotes = __DIR__ . '/../DEV_NOTES_SESSION.md';

file_put_contents($devNotes, $entry, FILE_APPEND | LOCK_EX);
if (file_exists($sessionNotes)) {
    file_put_contents($sessionNotes, $entry, FILE_APPEND | LOCK_EX);
}

echo "Appended documentation entry to DEV_NOTES.md" . PHP_EOL;
if (file_exists($sessionNotes)) echo " and DEV_NOTES_SESSION.md" . PHP_EOL;

// exit with success
exit(0);
