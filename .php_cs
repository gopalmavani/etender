<?php

return PhpCsFixer\Config::create()
  ->setRules([
      '@PSR2' => true,
      'strict_param' => false,
      'array_syntax' => ['syntax' => 'short'],
      'braces' => [
          'allow_single_line_closure' => true,
          'position_after_functions_and_oop_constructs' => 'same'],
  ])
  ->setIndent("  ")
  ->setUsingCache(false)
;
