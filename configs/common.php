<?php

/**
 * Common config
 */

return [
  'debug' => false,
  'cache' => [
    'objects' => [
      'dir' => APP_DIR.'/cache/objects',
    ],
    'views' => [
      'dir' => APP_DIR.'/cache/views',
    ],
  ],
];