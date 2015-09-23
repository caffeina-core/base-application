<?php

/**
 * Common config
 */

return [
  'debug' => false, // Disable when in production
  'cache' => [
    'directory' => null, // if null will use the sys_get_temp_dir
    'views'     => true,
  ],
];
