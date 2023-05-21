<?php
$readmePath = rex_path::addon('d2u_guestbook', 'README.md');
$readmeContent = rex_file::get($readmePath);
$readmeHtml = rex_markdown::factory()->parse($readmeContent);
echo $readmeHtml;