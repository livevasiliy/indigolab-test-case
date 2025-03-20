<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RequestCodeDTO
{
    /**
     * @Assert\NotBlank(message="Phone number is required")
     * @Assert\Type(type="string", message="Phone number must be a string")
     * @Assert\Regex(
     *     pattern="/^\+?[0-9]{10,15}$/",
     *     message="Invalid phone number format"
     * )
     *
     * @var string $phone_number
     */
    public $phone_number;
}
