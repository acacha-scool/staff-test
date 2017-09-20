<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ObtainsUserDataFromTeacherCode;
use Scool\Untis\Models\Profesor;

/**
 * Class SeedTeachers
 *
 * @package App\Console\Commands
 */
class SeedTeachers extends Command
{
    use ObtainsUserDataFromTeacherCode;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:teachers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed teachers from GPUNTIS files';

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
     */
    public function handle()
    {
        //Una vacante tiene que tener horarios Obtener los profes que tienen horario
        $query = "CAST(codigo AS unsigned) ASC";
        $profesores = Profesor::has('horarios')->whereNotNull('especialidad')->orderByRaw($query)->get();
        foreach ($profesores as $profesor) {
            print('// ' . $profesor->nombre . " | " . $profesor->departamento . " | " . $profesor->especialidad . "\n");
            $code = $profesor->codigo;
            $userData = $this->obtainUserDataFromTeacherCode($code);
            $user = 'obtainUserIdByEmail("' . $userData['email'] . '")';
            $status = '"pending"';
            $specialities = "[ 
    obtainSpecialityIdByCode('" . $profesor->especialidad ."')  => ['main' => true],
]";
            print('teacher_first_or_create("' . $code . '", ' . $user . ', ' . $specialities .', ' . $status. ');' . "\n");
        }
    }

}
