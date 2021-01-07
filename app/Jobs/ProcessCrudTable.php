<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessCrudTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $table;
    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $table, int $id = 0)
    {
        $this->table = $table;
        $this->id = $id;
    }

    /**
     * Envoi d'une requète GET sur le lien de rechargement des caches avec Guzzle.
     *
     * @return void
     */
    public function handle()
    {
        Log::info(" -------- Job CrudTable ------ ");
        $client = new Client([
            // 'base_uri' => asset('/'),
            'http_errors' => true,
            // 'timeout'  => 10.0,
        ]);

        $promise = $client->getAsync(route('caches.reload') , [
            'query' => [
                'table' => $this->table,
                'id' => $this->id
            ]
        ] );

        $promise->then(
            function () {
                Log::info('Caches rechargés avec succès !');
            },
            function (RequestException $e) {
                Log::info($e->getMessage());
                Log::info($e->getRequest()->getMethod());
            }
        );

        // Execution de la requète
        $promise->wait();
    }
}
