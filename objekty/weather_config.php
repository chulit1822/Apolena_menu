<?php
declare(strict_types=1);

return [
  // OpenWeather
  'owm_api_key' => '6cfe1f760174a857211ac14405f3b865',

  // Výchozí poloha (Apolena / Klínovec – uprav podle sebe)
  'lat' => 50.4182608,
  'lon' => 12.9965269,

  // Cache
  'cache_dir' => __DIR__ . '/cache',
  'cache_ttl_seconds' => 600, // 10 minut

  // OWM request
  'units' => 'metric', // metric | imperial | standard
   
  ];
