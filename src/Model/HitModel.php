<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class HitModel implements ModelInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Date(message="This value is not a valid date. Expected format YYYY-mm-dd")
     * @var string A "Y-m-d" formatted value
     */
    private $from;

    /**
     * @Assert\NotBlank
     * @Assert\Date(message="This value is not a valid date. Expected format YYYY-mm-dd")
     * @var string A "Y-m-d" formatted value
     */
    private $to;
    
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom(string $from): void
    {
        $this->from = $from;
    }
    
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo(string $to): void
    {
        $this->to = $to;
    }
}
