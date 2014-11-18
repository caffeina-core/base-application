<?php

/**
 * Common config
 */

return [
  'debug' => true,
  'cache' => [
    'objects' => [
      'dir' => APP_DIR.'/cache/objects',
    ],
    'views' => [
      'dir' => APP_DIR.'/cache/views',
    ],
  ],
];