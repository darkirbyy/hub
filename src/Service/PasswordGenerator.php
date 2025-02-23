<?php

declare(strict_types=1);

namespace App\Service;

use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

/**
 * Service to generate a secure random password.
 */
class PasswordGenerator
{
    private ComputerPasswordGenerator $computerPasswordGenerator;

    /**
     * Constructor. Initializes the password generator with uppercase, lowercase, numbers, and symbols enabled.
     */
    public function __construct()
    {
        $this->computerPasswordGenerator = new ComputerPasswordGenerator();
        $this->computerPasswordGenerator->setUppercase()->setLowercase()->setNumbers()->setSymbols();
    }

    /**
     * Generates a secure random password of the given length.
     *
     * @param int $length the desired length of the password (default: 20)
     *
     * @return string the generated password
     */
    public function generatePassword(int $length = 20): string
    {
        $this->computerPasswordGenerator->setLength($length);
        $plainPassword = $this->computerPasswordGenerator->generatePassword();

        return $plainPassword;
    }
}
