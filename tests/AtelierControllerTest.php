<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AtelierControllerTest extends WebTestCase
{
    
    public function testRouteLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

    }
    public function testRouteHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'CATALOGUES ATELIERS');
    }

//     public function testLoginWithBadCredentials()
// {
//     $client = static::createClient();
//     $crawler = $client->request('GET', '/login');
//     $form = $crawler->selectButton('Se connecter')->form([
//         'email' => 'john@doe.fr',
//         'password' => 'fakepassword'
//     ]);
//     $client->submit($form);
//     $this->assertResponseRedirects('/login');
//     $client->followRedirect();
//     $this->assertSelectorExists('.alert.alert-danger');
// }
}
