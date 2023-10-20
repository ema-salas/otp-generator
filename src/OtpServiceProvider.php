<?php

namespace EmaSalas\OtpGenerator;

use Illuminate\Support\ServiceProvider;

class OtpServiceProvider extends ServiceProvider
{
  public function boot(): void
    {
        $this->publishes([$this->configPath() => config_path('otp.php')]);
        $this->publishes([$this->migrationPath() => database_path('migrations')]);
    }

    private function configPath()
    {
      return __DIR__.'/../config/otp.php';
    }

    private function migrationPath()
    {
      return __DIR__.'/../database/migrations/';
    }
}