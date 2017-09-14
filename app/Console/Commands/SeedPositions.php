<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Scool\Untis\Models\Profesor;

/**
 * Class SeedPositions.
 *
 * @package App\Console\Commands
 */
class SeedPositions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:positions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cread code to seed positions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Obtener professores con cargo
        $profesores = Profesor::whereNotNull('cargo')->orderBy('cargo')->get();
        foreach ($profesores as $profesor) {
            if ($profesor->cargo)
                if (strpos($profesor->cargo, '/') !== false) {
                    $cargos = explode("/", $profesor->cargo);
                    foreach ($cargos as $cargo) {
                        print('position_first_or_create("' . trim($cargo) . '");' . "\n");
                    }

                } else {
                    print('position_first_or_create("' . trim($profesor->cargo) . '");' . "\n");
                }
        }
    }
}
