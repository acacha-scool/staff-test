<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\RefreshDatabase;


/**
 * Class TeachersWizardTest.
 *
 * @package Tests\Browser
 */
class TeachersWizardTest extends DuskTestCase
{
    use RefreshDatabase;

    /**
     * See teacher wizard.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/teacher')
                    ->assertSee('Laravel');
        });
    }
}
