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
        seed_departments();
        //Una vacante tiene que tener horarios Obtener los profes que tienen horario
        $profesores = Profesor::has('horarios')->whereNotNull('especialidad')->orderBy('especialidad')->get();

        $departmentCounters = [];
        foreach ($profesores as $profesor) {
            $currentDepartment = obtainDepartmentIdBySpecialityCode($profesor->especialidad);
            if ( array_key_exists ( $currentDepartment , $departmentCounters ) ) {
                $departmentCounters[$currentDepartment] = $departmentCounters[$currentDepartment] + 1;
            }  else {
                $departmentCounters[$currentDepartment] = 1;
            }


            $speciality = 'obtainSpecialityIdByCode("' . $profesor->especialidad . '")';
            $department = 'obtainDepartmentIdByEspecialityCode("' . $profesor->especialidad . '")';
            $order = $departmentCounters[$currentDepartment];
            $owner = 'obtainTeacherIdByCode("' . $profesor->codigo . '")';
            $state = '"assigned"';
            print('vacancy_first_or_create( ' . $speciality . ', ' . $department . ', ' . $order . ', ' . $owner . ', ' . $state . ');' . "\n");
        }
    }

}
