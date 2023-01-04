<?php


use PHPUnit\Framework\TestCase;

// Bien mettre le même nom de classe que le nom du fichier et finissant par "Test"
class SampleTest extends TestCase
{
    // Bien mettre le mot-clé "Test" au début de la fonction
    public function testIsTrue()
    {
        // Voir les fonctions possibles de TestCase ici : https://phpunit.readthedocs.io/fr/latest/assertions.html
        $this->assertTrue(true);
    }
}
