<?php

$logPath = 'C:\\Users\\fikri\\.gemini\\antigravity-ide\\brain\\57aeba76-64b1-475a-ba18-8cb3969c61c8\\.system_generated\\logs\\transcript_full.jsonl';
$handle = fopen($logPath, 'r');
$lastGoodContent = '';

while (($line = fgets($handle)) !== false) {
    $data = json_decode($line, true);
    if (! $data) {
        continue;
    }

    // Check if it's a tool response for view_file
    if (isset($data['type']) && $data['type'] === 'TOOL_RESPONSE') {
        $content = $data['content'] ?? '';
        if (strpos($content, 'file:///c:/laragon/www/kargokita/resources/views/livewire/driver-cockpit.blade.php') !== false) {
            // We want to find a view_file that showed a large portion or a replace_file_content that shows the file.
            // Wait, view_file output format:
            // 1: <line1>
            // 2: <line2>
        }
    }
}
fclose($handle);
echo 'Done';
