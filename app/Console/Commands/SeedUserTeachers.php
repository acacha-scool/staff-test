<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ObtainsUserDataFromTeacherCode;
use Scool\Untis\Models\Profesor;

/**
 * Class SeedUserTeachers
 *
 * @package App\Console\Commands
 */
class SeedUserTeachers extends Command
{
    use ObtainsUserDataFromTeacherCode;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:user_teachers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed user teachers from GPUNTIS files';

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

        foreach ($profesores as $profesor) {
            $userData = $this->obtainUserDataFromTeacherCode($profesor->codigo);
            $username= $userData['name'];
            $email= $userData['email'];
            print("user_teacher_first_or_create('". $username . "', '" . $email ."');\n");
        }
    }

}
