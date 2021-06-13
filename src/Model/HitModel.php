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
    private string $from;

    /**
     * @Assert\NotBlank
     * @Assert\Date(message="This value is not a valid date. Expected format YYYY-mm-dd")
     * @var string A "Y-m-d" formatted value
     */
    private string $to;
    
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string|null $from
     */
    public function setFrom(?string $from): void
    {
        if ($from) {
            $this->from = $from;
        }
    }
    
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string|null $to
     */
    public function setTo(?string $to): void
    {
        if (isset($to)) {
            $this->to = $to;
        }
    }
}
