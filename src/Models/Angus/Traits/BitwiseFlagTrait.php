<?php

namespace Imega\DataReporting\Models\Angus\Traits;

trait BitwiseFlagTrait
{
    protected function getFlag(string $name, int $flag): bool
    {
        return ($this->$name & $flag) === $flag;
    }

    protected function setFlag(string $name, int $flag, bool $value): bool
    {
        if ($value) {
            $this->$name |= $flag;

        } else {
            $this->$name &= ~$flag;
        }

        return ($this->$name & $flag) === $flag;
    }

    protected function toggleFlag(string $name, int $flag): bool
    {
        $this->$name ^= $flag;

        return $this->getFlag($name, $flag);
    }
}
