<?php

namespace App\Entity;

class BlogPost
{
    // the configured marking store property must be declared
    private $currentPlace;
    private $title;
    private $content;

    // getter/setter methods must exist for property access by the marking store
    public function getCurrentPlace()
    {
        return $this->currentPlace;
    }

    public function setCurrentPlace($currentPlace, $context = [])
    {
        $this->currentPlace = $currentPlace;
    }
}