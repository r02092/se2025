<?php

namespace App\Traits;

trait TransformCoordTrait
{
    public function encodeLng($value)
    {
        return (($value + 180) * (2 ** 32 - 1)) / 360;
    }
    private function encodeLat($value)
    {
        return (($value + 90) * (2 ** 32 - 1)) / 180;
    }
    public function getLngAttribute($value)
    {
        return ($value * 360) / (2 ** 32 - 1) - 180;
    }
    public function getLatAttribute($value)
    {
        return ($value * 180) / (2 ** 32 - 1) - 90;
    }
}
