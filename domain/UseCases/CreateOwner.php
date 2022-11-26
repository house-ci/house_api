<?php

final class CreateOwner
{
    public function newOwner(array $owner): void
    {
        foreach ($owner as $key => $value) {
            echo $key . ' : ' . $value;
        }
    }
}
