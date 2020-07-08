<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloud options
    |--------------------------------------------------------------------------
    */
    'cloud' => [
        'backup' => env('CLOUD_BACKUP', false), // [bool] enable/disable cloud uploads
    ],

    /*
    |--------------------------------------------------------------------------
    | Inventory data
    |--------------------------------------------------------------------------
    */
    'inventory' => [
        'photographs' => [
            'originals' => 'inventario/fotos',
            'thumbnails' => 'inventario/fotosThumbnails',
            'trashed' => 'papelera/inventario/fotos',
            'thumbnail_width' => '100', // pixels, the height will be proportional
            'maximum_size' => '5120', // kilobytes
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Research data
    |--------------------------------------------------------------------------
    */
    'research' => [
        'photographs' => [
            'originals' => 'investigacion/fotos',
            'thumbnails' => 'investigacion/fotosThumbnails',
            'trashed' => 'papelera/investigacion/fotos',
            'thumbnail_width' => '100', // pixels, the height will be proportional
            'maximum_size' => '10240', // kilobytes
        ],
        'documents' => [
            'originals' => 'investigacion/documentos',
            'trashed' => 'papelera/investigacion/documentos',
            'maximum_size' => '5120', // kilobytes
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Restoration data
    |--------------------------------------------------------------------------
    */
    'restoration' => [
        'photographs' => [
            'originals' => 'restauracion/fotos',
            'thumbnails' => 'restauracion/fotosThumbnails',
            'trashed' => 'papelera/restauracion/fotos',
            'thumbnail_width' => '100', // pixels, the height will be proportional
            'maximum_size' => '10240', // kilobytes
        ],
        'documents' => [
            'originals' => 'restauracion/documentos',
            'trashed' => 'papelera/restauracion/documentos',
            'maximum_size' => '10240', // kilobytes
        ],
    ],

];
