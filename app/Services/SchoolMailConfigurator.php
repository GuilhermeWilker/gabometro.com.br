<?php

namespace App\Services;

use App\Models\School;

class SchoolMailConfigurator
{
      public static function apply(School $school): void
      {
            if (! $school->mail_is_configured) {
                  throw new \RuntimeException("SMTP não configurado para a escola {$school->name}.");
            }

            config([
                  'mail.default' => 'smtp',
                  'mail.mailers.smtp.transport' => 'smtp',
                  'mail.mailers.smtp.host' => $school->mail_host,
                  'mail.mailers.smtp.port' => $school->mail_port,
                  'mail.mailers.smtp.username' => $school->mail_username,
                  'mail.mailers.smtp.password' => $school->mail_password,
                  'mail.mailers.smtp.encryption' => $school->mail_encryption,
                  'mail.from.address' => $school->mail_from_address,
                  'mail.from.name' => $school->mail_from_name,
            ]);
      }
}
