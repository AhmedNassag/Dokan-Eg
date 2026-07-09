<?php
echo "Total: " . App\Models\Translation::count() . PHP_EOL;
echo "EN: " . App\Models\Translation::whereHas("language", function($q) { $q->where("code", "en"); })->count() . PHP_EOL;
echo "AR: " . App\Models\Translation::whereHas("language", function($q) { $q->where("code", "ar"); })->count() . PHP_EOL;
