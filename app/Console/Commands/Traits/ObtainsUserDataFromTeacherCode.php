<?php
use Scool\EbreEscoolModel\Teacher;

/**
 * Class ObtainsUserDataFromTeacherCode.
 */
trait ObtainsUserDataFromTeacherCode
{
    /**
     *  obtainUserDataFromTeacherCode($code)
     *
     * @param $code
     * @return array
     */
    protected function obtainUserDataFromTeacherCode($code)
    {
        $teachers = DB::connection('ebre_escool')
            ->table('teacher_academic_periods')
            ->where([
                'teacher_academic_periods_code' => $code,
                'teacher_academic_periods_academic_period_id' => 8,
            ])
            ->get();
        $teacher_id = $teachers[0]->teacher_academic_periods_teacher_id;
        $teacher = Teacher::findOrFail($teacher_id);

        return [
            'username'  => $teacher->user->username ,
            'name'  => $teacher->person->person_givenName . ' ' . $teacher->person->person_sn1 . ' ' . $teacher->person->person_sn2,
            'email'     => $teacher->person->person_email ,
        ];
    }
}