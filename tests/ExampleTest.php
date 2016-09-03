<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testAppRoot()
    {
        $this->visit('/')
            ->see('Night Life Locator');
    }
    /*
     * Visit a link in the returned response
     */

    public function testLinkVisit()
    {
        $this->visit('/')
            ->click('top10')
            ->seePageIs('/top10');
    }
    /*
     * Registration form
     */

    public function testNewUserRegistration()
    {
        $this->visit('/register')
            ->type('neelkanth@mailinator.com', 'email')
            ->type('123456', 'password')
            ->type('123456', 'password_confirmation')
            ->type('Neelkanth', 'first_name')
            ->type('Kaushik', 'last_name')
            ->press('Register')
            ->seePageIs('/register');
    }

    public function testApplication()
    {
        $attributes = array('email');
        $user = factory(App\User::class)->create($attributes);
        
        $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->visit('/')
            ->see('Hello, ' . $user->email);
    }
}
