<?php

namespace App\Jobs;

use App\CrudTable;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ProcessCrudTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $crudTable;
    protected $instance;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CrudTable $crudTable, $instance = null)
    {
        $this->crudTable = $crudTable->withoutRelations();
        $this->instance = $instance;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info(" ------ Job : ProcessCrudTable ------ ");
        $client = new Client([
            // 'base_uri' => config('app.url'),
            // 'http_errors' => true,
            'timeout'  => 10.0,
        ]);

        $promise = $client->getAsync(config('app.url') . '/ajax/caches/reload' , [
            'query' => [
                'crud_table_id' => $this->crudTable->id,
                'instance_id' => $this->instance->id ?? ''
            ]
        ] );

        $promise->then(
            function (ResponseInterface $res) {
                Log::info('Caches rechargés !');
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
