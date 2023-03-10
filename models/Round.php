<?php
require_once "Game.php";

class Round
{
    /**
     * @var ?int Valeur du premier lancer. Initialisée à NULL et devient un nombre si le round a fait un lancer
     **/
    private ?int $first_throw = null;

    /**
     * @var ?int Valeur du second lancer. Initialisée à NULL et devient un nombre si le round a fait un lancer
     *
     * Si le joueur fait un strike, il n'a pas le droit au second lancer
     **/
    private ?int $second_throw = null;

    /**
     * @var ?int Valeur du troisième lancer. Initialisée à NULL et devient un nombre si le round a fait un lancer
     *
     * Cette variable a une valeur quand le joueur fait un strike ou un spare au dernier tour
     **/
    private ?int $third_throw = null;

    /**
     * Assesseur pour la valeur du premier lancer
     * @return ?int Valeur du premier lancer
     **/
    public function get_first_throw(): ?int
    {
        return $this->first_throw;
    }

    /**
     * Assesseur pour la valeur du deuxième lancer
     * @return ?int Valeur du deuxième lancer
     **/
    public function get_second_throw(): ?int
    {
        return $this->second_throw;
    }

    /**
     * Assesseur pour la valeur du troisième lancer
     * @return ?int Valeur du troisième lancer
     **/
    public function get_third_throw(): ?int
    {
        return $this->third_throw;
    }

    /**
     * Mutateur pour le premier lancer
     *
     * @param int $throw_value Valeur du premier lancer comprises en 0 et Game::MAX_PIN
    **/
    public function set_first_throw(int $throw_value): void
    {
        $this->first_throw = $throw_value;
    }

    /**
     * Mutateur pour le deuxième lancer
     *
     * @param int $throw_value Valeur du deuxième lancer comprises en 0 et Game::MAX_PIN
     **/
    public function set_second_throw(int $throw_value): void
    {
        $this->second_throw = $throw_value;
    }

    /**
     * Mutateur pour le troisième lancer
     *
     * @param int $throw_value Valeur du deuxième lancer comprises en 0 et Game::MAX_PIN
     **/
    public function set_third_throw(int $throw_value): void
    {
        $this->third_throw = $throw_value;
    }
}
