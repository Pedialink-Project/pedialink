<?php

use App\Controllers\MediaController;

return [
    ['GET', '/media/serve', [MediaController::class, 'serve'], 'media.serve'],
];