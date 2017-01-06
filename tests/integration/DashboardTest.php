<?php

use Carbon\Carbon;

class DashboardTest extends TestCase
{
    public function _construct()
    {
        $this->visitUrl = '/dashboard';
        dd($this->faker->name);
    }

    public function test_dashboard_status()
    {
        $this->visit($this->visitUrl)
            ->assertResponseOk();
    }

    public function test_add_quick_note()
    {
        $faker = Faker\Factory::create();

        $title = $faker->sentence(mt_rand(10, 16));

        $this->visit($this->visitUrl)
            ->see('<div class="notes">')
            ->type($title, 'frm_notes_title')
            ->press('Add')
            ->seeInDatabase('notes', ['title' => $title]);
    }

    public function test_message_empty_sprint_member()
    {
        $this->visit($this->visitUrl)
            ->see('list-sprint-empty');
    }

    public function test_create_sprint_backlog()
    {
        $faker = Faker\Factory::create();

        $title = $faker->sentence(mt_rand(10, 16));
        $description = $faker->sentence(mt_rand(10, 100));
        $dateStart = Carbon::now()->addDays(2)->toDateString();
        $dateFinish = Carbon::now()->addDays(16)->toDateString();
        $dateRange = $dateStart . ' - ' . $dateFinish;

        $this->visit($this->visitUrl)
            ->click('Create Sprint Backlog')
            ->see('class="modal-body"')
            ->type($title, 'title')
            ->type($dateRange, 'daterange')
            ->type($description, 'description')
            ->press('Confirm and Create')
            ->seeInDatabase('sprints', ['title' => $title]);
    }
}
