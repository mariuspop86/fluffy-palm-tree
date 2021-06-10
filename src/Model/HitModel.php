<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class HitModel
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
     * @Assert\GreaterThan($this->from)
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
    public function setFrom($from): void
    {
        $this->from = $from;
    }
    
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo($to): void
    {
        $this->to = $to;
    }
}
