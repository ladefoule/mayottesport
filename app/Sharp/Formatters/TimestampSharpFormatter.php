<?php

namespace App\Sharp\Formatters;

use Carbon\Carbon;
use Code16\Sharp\Form\Fields\Formatters\SharpFieldFormatter;
use Code16\Sharp\Form\Fields\SharpFormField;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class TimestampSharpFormatter extends SharpFieldFormatter
{

    /**
     * @param SharpFormField $field
     * @param $value
     * @return mixed
     */
    function toFront(SharpFormField $field, $value)
    {
        $txt = '';
        if($value){
            $datetime = Carbon::createFromTimestamp(strtotime($value))->timezone(Config::get('app.timezone'));
            $txt = date_format($datetime, 'd/m/Y à H:i:s');
        }
        return $txt;
    }

    /**
     * @param SharpFormField $field
     * @param string $attribute
     * @param $value
     * @return mixed
     */
    function fromFront(SharpFormField $field, string $attribute, $value)
    {
        return '';
    }
}
