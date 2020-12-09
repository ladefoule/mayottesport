<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache as CacheFacade;

class Cache extends CacheFacade
{
    /**
     * Surcharge de la méthode forget du Cache de Laravel qui envoie un log avec le nom du cache supprimé
     *
     * @param string $cache
     * @return void
     */
    public static function forget(string $cache)
    {
        CacheFacade::forget($cache);
        Log::info("Suppression du cache : " . $cache);
    }
}
