<?php

namespace App\Tests;

use App\Entity\Animateur;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use PHPUnit\Framework\Attributes\DataProvider;



class AnimateurTest extends TestCase
{
    /**
     * @dataProvider getValidationTestCases
     */


    // public function testSomething(): void
    // {
    //     $this->assertTrue(true);
    // }

    public function testAnimateurValidationPasses($nom, $prenom, $email, $password, $expected): void
    {
        //Arrange
        $animateur = Animateur::build($nom, $prenom, $email, $password);
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        // Act
        $result = $validator->validate($animateur);

        // Assert
        $this->assertEquals($expected, count($result) == 0);
    }
    /**
     * fournisseur de données pour les métodes de test
     */
    public function getValidationTestCases()
    {
        return [
            'Succeeds when data is correct' => ['abdel', 'ben', 'abenamar@gmx.com', 'Admin123@', true],
            'Fails when nom is blank' => ['', 'mani', 'soupramani@yahoo.fr', 'abc7Q@az', false],
            'Fails when prénom is blank' => ['soupra', '', 'soupramani@yahoo.fr', 'abc7Q@az', false],
            'Fails when email is not valid' => ['soupra', 'mani', 'soupramaniyahoo.fr', 'abc7Q@az', false],
            'Fails when password is missing' => ['soupra', 'mani', 'soupramani@yahoo.fr', '', false],
            'Fails when password contains less than 6 character' => ['soupra', 'mani', 'soupramani@yahoo.fr', 'abc7Q@a', false],
            'Fails when password does not contain a special character' => ['soupra', 'mani', 'soupramani@yahoo.fr', 'abc7Qkla', false],
            'Fails when password does not contain a uppercase character' => ['soupra', 'mani', 'soupramani@yahoo.fr', 'abc7q@kla', false],
            'Fails when password does not contain a lowercase character' => ['soupra', 'mani', 'soupramani@yahoo.fr', '1144A@DF', false],
            'Fails when password does not contain a digit' => ['soupra', 'mani', 'soupramani@yahoo.fr', 'abcaQ@az', false],
        ];
    }
}
