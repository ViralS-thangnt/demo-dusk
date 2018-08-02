<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Exports\SpeedTestExport;
use App\User;
use Excel;

class ThangTest extends DuskTestCase
{
    use DatabaseMigrations, RefreshDatabase;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    // public function test_login()
    // {
    //     // Test 1
    //     $user = factory(User::class)->create([
    //         'email' => 'admin@example.com',
    //         'password' => bcrypt('123456'),
    //     ]);

    //     $this->browse(function (Browser $browser) use ($user) {
    //         $browser->visit('/')
    //                 ->waitForText('Laravel')
    //                 ->assertSee('Laravel')
    //                 ->pause(3000)
    //                 ->visit('/login')
    //                 ->type('email', $user->email)
    //                 ->type('password', '123456')
    //                 ->press('Login')
    //                 ->assertPathIs('/home');
    //     });
    // }

    // public function test_login_fail() {
    //     // Test 2
    //     $user = factory(User::class)->create([
    //         'email' => 'taylor@laravel.com',
    //     ]);

    //     $this->browse(function ($first, $second, $third) use ($user) {
    //         $first->visit('/login')
    //                 ->type('email', $user->email)
    //                 ->type('password', 'secret')
    //                 ->press('Login')
    //                 ->assertPathIs('/home');
    //         $second->visit('/')
    //                 ->assertSee('Laravel');
    //         $third->visit('/')
    //                 ->assertSee('Laravel');
    //     });
    // }

    // public function test_register() {
    //     // \Artisan::call('migrate:refresh');
    //     // \Artisan::call('db:seed', ['--class' => 'TestPlaylistSeeder']);
    //     // Test 3
    //     $user = factory(User::class)->create([
    //         'email' => 'admin@example.com',
    //         'password' => bcrypt('123456'),
    //     ]);
    //     $this->browse(function (Browser $firstBrowser, $secondBrowser, $thirdBrowser) use ($user) {
    //         $firstBrowser->visit('/')
    //                 ->assertSee('Laravel')
    //                 ->resize(700, 600)

    //                 // test register
    //                 ->visit('/register')
    //                 ->assertSee('Register')
    //                 ->pause(1000)
    //                 ->type('name', 'Thang')
    //                 ->type('email', 'toanthang1988@gmail.com')
    //                 ->type('password', '123456')
    //                 ->type('password_confirmation', '123456')
    //                 ->pause(1000)
    //                 ->press('Register')
    //                 ->assertPathIs('/home')
    //                 ->pause(1000)

    //                 // test logout
    //                 ->clickLink('Logout')
    //                 ->pause(1000)
    //                 ->assertPathIs('/')
    //                 ->pause(2000)

    //                 // test login with user just register
    //                 ->visit('/login')
    //                 ->assertSee('Login')
    //                 ->pause(1000)
    //                 ->type('email', 'toanthang1988@gmail.com')
    //                 ->type('password', '123456')
    //                 ->pause(1000)
    //                 ->press('Login')
    //                 ->pause(2000)
    //                 ->assertPathIs('/home')
    //                 ->pause(3000)
    //                 ;

    //         $secondBrowser->visit('/')
    //                 ->assertSee('Laravel')
    //                 ->resize(700, 600)
    //                 ->visit('/login')
    //                 ->pause(1000)
    //                 ->type('email', 'admin@example.com')
    //                 ->type('password', '123456')
    //                 ->pause(1000)
    //                 ->press('Login')
    //                 ->pause(5000)
    //                 ->assertPathIs('/home')
    //                 // ->pause(5000)
    //                 ;
    //         $thirdBrowser->visit('http://google.com')
    //                 ->pause(10000)
    //                 ;
    //     });
    // }

    public function test_access_singtel_speedtest() {
        // http://speed-portal.singnet.com.sg/SG/1G?is_admin=true
        $singtelUrl = 'http://speed-portal.singnet.com.sg/SG/1G?is_admin=true';
        $this->browse(function (Browser $firstBrowser) use($singtelUrl) {

            $response = $firstBrowser->visit($singtelUrl)
                    ->assertSee('Start')
                    ->assertSee('Change Server')
                    ->assertSee('Singapore Speedtest Server')
                    // ->resize(1500, 700)
                    ->pause(1000)
                    ->press('Start')
                    ->pause(1000)
                    ->assertSee('Test Running')
                    ->waitForText('Restart', 300000)
                    ;

            // Extract data
            $ping = $firstBrowser->text('#avgLatency');
            dump($ping);

            $downloadSpeed = $firstBrowser->text('#downloadSpeed');
            dump($downloadSpeed);

            $uploadSpeed = $firstBrowser->text('#uploadSpeed');
            dump($uploadSpeed);

            $data = [$ping, $downloadSpeed, $uploadSpeed];
            $filename = 'Report_' . date('Y-m-d-H-i-s', time()) . '.xlsx';

            $temp = new SpeedTestExport($data);
            Excel::store($temp, $filename);
            dump($temp);

            $imageName = 'Report_' . date('Y-m-d-H-i-s', time()) . '.jpg';
            $response->driver->takeScreenshot(storage_path('app/' . $imageName));

        });


    }


}











