<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Scool\Untis\Models\Profesor;

/**
 * Class SeedVacancies
 *
 * @package App\Console\Commands
 */
class SeedVacancies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:vacancies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed vacancies from GPUNTIS files';

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
     */
    public function handle()
    {
        //Una vacante tiene que tener horarios Obtener los profes que tienen horario
        $profesores = Profesor::has('horarios')->whereNotNull('especialidad')->orderBy('especialidad')->get();
        $lastEspecialidad = null;
        $counter = 1;
        foreach ($profesores as $profesor) {
            if ($lastEspecialidad === $profesor->especialidad) $counter++;
            else $counter = 1;
            $code = $profesor->especialidad . '_' . $counter;
            print('vacancy_first_or_create("' . $code . '", obtainSpecialityIdByCode("' . $profesor->especialidad . '"), "active");' . "\n");
            $lastEspecialidad = $profesor->especialidad;
        }
    }

}
