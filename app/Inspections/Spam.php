<?php

namespace App\Inspections;

class Spam {
    /**
     * @var string[]
     */
    protected $inspections = [
      InvalidKeyWords::class,
      KeyPressedDown::class
    ];

    /**
     * @param $body
     * @return false
     */
    public function detect($body): bool
    {
        foreach($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }
        return false;
    }
}
