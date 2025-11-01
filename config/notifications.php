<?php

return [
    'justificacion' => [
        'observers' => [
            App\Services\Notifications\Observers\StudentEmailObserver::class,
            App\Services\Notifications\Observers\ProfessorReportObserver::class,
        ],
    ],
];