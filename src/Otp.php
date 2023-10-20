<?php

namespace EmaSalas\OtpGenerator;

use EmaSalas\OtpGenerator\Models\OneTimePassword as Model;
use Illuminate\Support\Facades\Hash;

trait Otp
{
  public function generateToken(): string
  {
    $token = $this->generate();
    $expires = config('otp.expires', 15);

    Model::create([
      'model' => get_class($this),
      'model_id' => $this->id,
      'token' => Hash::make($token),
      'expired_at' => now()->addMinutes($expires)
    ]);

    return $token;
  }

  public function validateOtp(string $token): object
  {
    $otps = Model::where('model',get_class($this))
    ->where('model_id', $this->id)
    ->whereDate('created_at', '>=', now()->subHours(24))
    ->orderByDesc('created_at')
    ->get();
    $existsOtp = false;
    foreach ($otps as $otp) {
      if (Hash::check($token, $otp->token)) {
        $existsOtp = true;
        if ($otp->validity) {
          $otp->validity = false;
          if ($otp->expired_at->lt(now())) {
            return (object)[
              'status' => false,
              'message' => 'OTP Expired'
            ];
          } else {          
            return (object)[
              'status' => true,
              'message' => 'OTP is valid'
            ];
          }
          $otp->save();
        } else {
          return (object)[
            'status' => false,
            'message' => 'OTP is not valid'
          ];
        }
      } else {
        logger('NO HASH');
      }
    }

    if (!$otps->count() || !$existsOtp) {
      return (object)[
        'status' => false,
        'message' => 'OTP does not exist'
      ];
    }
  }

  private function generate(): string
  {
    $passwordGenerator = config('otp.password_generator', 'string');

    switch ($passwordGenerator) {
      case 'numeric':
        return $this->generateNumericPin();
      case 'numeric-no-0':
        return $this->generateNumericNoZeroPin();
      case 'string':
      default:
        return $this->generateStringPin();
    }
  }

  private function generateNumericPin(): string
  {
    $digits =  config('otp.length', 8);
    $i = 0;
    $pin = '';

    while ($i < $digits) {
      $pin .= random_int(0, 9);
      $i++;
    }

    return $pin;
  }

  private function generateNumericNoZeroPin(): string
  {
    $digits =  config('otp.length', 8);
    $i = 0;
    $pin = '';

    while ($i < $digits) {
      $pin .= random_int(1, 9);
      $i++;
    }

    return $pin;
  }

  private function generateStringPin(): string
  {
    $length =  config('otp.length', 8);
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $randomString .= $characters[$index];
    }

    return $randomString;
  }
}
