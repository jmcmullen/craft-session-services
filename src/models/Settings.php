<?php

namespace jmcmullen\sessionservices\models;

use craft\base\Model;

class Settings extends Model
{
    public string $baseUrl = '';
    public string $template = '';

    public function rules(): array
    {
        return [
            [['baseUrl', 'template'], 'string'],
            [['baseUrl', 'template'], 'required'],
        ];
    }
}
