<?php

namespace Domain\Interfaces;

use App\Models\Commands\Owner;

class CreateOwnerResponse
{
    public string $id;
    public string $fullName;
    public string $email;
    public string $phoneNumber;
    public string $identifier;

    public function __construct(Owner $owner)
    {
        $this->id = $owner;
        $this->fullName = null;
        $this->email = null;
        $this->phoneNumber = null;
        $this->identifier = null;

    }

}
