<?php
require_once dirname(__DIR__) . "/models/Game.php";

use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    const NORMAL_NUMBER_OF_ROUNDS = 10;
    const NORMAL_NUMBER_OF_PINS = 10;

    public function test__game_init(): void
    {
        $players = array();
        $players[] = new Player("Player 1");
        $game = new Game($players, self::NORMAL_NUMBER_OF_ROUNDS, self::NORMAL_NUMBER_OF_PINS);

        $this->assertEquals($players[0], $game->get_current_player());
        $this->assertEquals(1, $game->get_current_round());
        $this->assertEquals(1, $game->get_current_throw());
    }

    public function test__game_init_with_invalid_player(): void
    {
        $players = array();
        $players[] = new stdClass();
        $this->expectException(InvalidArgumentException::class);
        new Game($players, self::NORMAL_NUMBER_OF_ROUNDS, self::NORMAL_NUMBER_OF_PINS);
    }

    public function test__game_get_rounds(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players,
            self::NORMAL_NUMBER_OF_ROUNDS,
            self::NORMAL_NUMBER_OF_PINS
        );

        $this->assertEquals(self::NORMAL_NUMBER_OF_ROUNDS, $game->get_rounds());
    }

    public function test__game_get_pins(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players,
            self::NORMAL_NUMBER_OF_ROUNDS,
            self::NORMAL_NUMBER_OF_PINS
        );

        $this->assertEquals(self::NORMAL_NUMBER_OF_PINS, $game->get_pins());
    }

    public function test__save_throw_normal(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game(
            $short_valid_list_players,
            self::NORMAL_NUMBER_OF_ROUNDS,
            self::NORMAL_NUMBER_OF_PINS
        );

        $game->save_throw(5);
        $this->assertEquals(5, $game->get_current_player()->get_first_throw_score(1));
    }

    public function test__save_throw_out_of_range(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players, self::NORMAL_NUMBER_OF_ROUNDS, self::NORMAL_NUMBER_OF_PINS);
        $this->expectException(InvalidArgumentException::class);
        $game->save_throw(999);
    }

    public function test__save_throw_huge(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players, self::NORMAL_NUMBER_OF_ROUNDS, 1000);
        $game->save_throw(999);
        $this->assertEquals(999, $game->get_current_player()->get_first_throw_score(1));
    }

    public function test__save_throw_more_than_pins(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game(
            $short_valid_list_players,
            self::NORMAL_NUMBER_OF_ROUNDS,
            self::NORMAL_NUMBER_OF_PINS
        );

        $game->save_throw(7); // On fait tomber 7 quilles, il en reste donc 3
        $this->expectException(LogicException::class);
        $game->save_throw(5); // On fait tomber 6 quilles [impossible]
    }

    public function test__save_throw_more_than_pins_2(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game(
            $short_valid_list_players,
            1,
            self::NORMAL_NUMBER_OF_PINS
        );

        $game->save_throw(10);
        $game->save_throw(7);
        $game->save_throw(2);

        $this->assertEquals(10, $game->get_current_player()->get_first_throw_score(1));
        $this->assertEquals(7, $game->get_current_player()->get_second_throw_score(1));
        $this->assertEquals(2, $game->get_current_player()->get_third_throw_score(1));
    }

    public function test__save_throw_more_than_pins_3(): void
    {
        $game = new Game(
            [new Player("Player 1")],
            1,
            self::NORMAL_NUMBER_OF_PINS
        );

        // Pas de spare, ni de strike ici
        $game->save_throw(4);
        $game->save_throw(3);

        $game->next();

        $game->save_throw(8);

        $this->assertEquals(4, $game->get_current_player()->get_first_throw_score(1));
        $this->assertEquals(3, $game->get_current_player()->get_second_throw_score(1));
        $this->assertEquals(8, $game->get_current_player()->get_first_throw_score(2));

        $this->expectException(LogicException::class);
        $game->save_throw(5);

    }

    public function test__current_player_did_spare(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players, self::NORMAL_NUMBER_OF_ROUNDS, self::NORMAL_NUMBER_OF_PINS);
        $game->save_throw(5);
        $game->save_throw(5);
        $this->assertTrue($game->current_player_did_spare());
    }

    public function test__current_player_did_spare_even(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players, self::NORMAL_NUMBER_OF_ROUNDS, 6);
        $game->save_throw(3);
        $game->save_throw(3);
        $this->assertTrue($game->current_player_did_spare());
    }

    public function test__current_player_did_spare_odd(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players, self::NORMAL_NUMBER_OF_ROUNDS, 3);
        $game->save_throw(3);
        $game->save_throw(0);
        $this->assertTrue($game->current_player_did_spare());
    }

    public function test__current_player_did_strike(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players, self::NORMAL_NUMBER_OF_ROUNDS, self::NORMAL_NUMBER_OF_PINS);
        $game->save_throw(10);
        $this->assertTrue($game->current_player_did_strike());
    }

    public function test__current_player_did_not_make_strike(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players, self::NORMAL_NUMBER_OF_ROUNDS, self::NORMAL_NUMBER_OF_PINS);
        $game->save_throw(9);
        $this->assertFalse($game->current_player_did_strike());
    }

    public function test__current_player_did_strike_odd(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players, self::NORMAL_NUMBER_OF_ROUNDS, 3);
        $game->save_throw(3);
        $this->assertTrue($game->current_player_did_strike());
    }

    public function test__current_player_did_strike_even(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players, self::NORMAL_NUMBER_OF_ROUNDS, 6);
        $game->save_throw(6);
        $this->assertTrue($game->current_player_did_strike());
    }

    public function test__next(): void
    {
        $game = new Game([new Player("Player 1")], self::NORMAL_NUMBER_OF_ROUNDS, self::NORMAL_NUMBER_OF_PINS);
        $game->save_throw(1);
        $game->save_throw(1);

        $game->next();

        $this->assertEquals(2, $game->get_current_round());
        $this->assertEquals(2, sizeof($game->get_current_player()->get_scoreboard()));
    }

    public function test__next__next_player(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players, self::NORMAL_NUMBER_OF_ROUNDS, self::NORMAL_NUMBER_OF_PINS);
        $game->save_throw(1);
        $game->save_throw(1);

        $game->next();

        $this->assertEquals(1, $game->get_current_round());
        $this->assertEquals($short_valid_list_players[1], $game->get_current_player());

    }

    public function test__next__next_player_0_score(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players, 2, self::NORMAL_NUMBER_OF_PINS);
        $game->save_throw(0);
        $game->save_throw(0);

        $game->next();

        $this->assertEquals(1, $game->get_current_round());
        $this->assertEquals($short_valid_list_players[1], $game->get_current_player());

    }

    public function test__next__next_player_above_2(): void
    {
        $short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );

        $game = new Game($short_valid_list_players, self::NORMAL_NUMBER_OF_ROUNDS, self::NORMAL_NUMBER_OF_PINS);
        $game->save_throw(1);
        $game->save_throw(1);

        $game->next();

        $game->save_throw(1);
        $game->save_throw(1);

        $game->next();

        $this->assertEquals(2, $game->get_current_round());
        $this->assertEquals(2, sizeof($game->get_current_player()->get_scoreboard()));
    }

    /**
     * Fonction dans le cas où aucun lancer n'a encore été fait
     * @return void
    **/
    public function test__calculate_score_in_round_for_player_no_throws_made(): void
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            self::NORMAL_NUMBER_OF_ROUNDS,
            self::NORMAL_NUMBER_OF_PINS
        );

        $result = $g->calculate_score_in_round_for_player($p, 1);

        $this->assertEquals(null, $result);
    }

    /**
     * Cas hors exception, des valeurs qui ne feront ni spare, ni strike
     * @return void
    **/
    public function test__calculate_score_in_round_for_player_simple(): void
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            self::NORMAL_NUMBER_OF_ROUNDS,
            self::NORMAL_NUMBER_OF_PINS
        );

        $g->save_throw(3);
        $g->save_throw(2);

        $result = $g->calculate_score_in_round_for_player($p, 1);
        $expected = 5;

        $this->assertEquals($expected, $result);
    }

    /**
     * Cas avec un spare très basique qui n'est pas au dernier round
     * @return void
    **/
    public function test__calculate_score_in_round_for_player_spare_simple(): void
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            self::NORMAL_NUMBER_OF_ROUNDS,
            self::NORMAL_NUMBER_OF_PINS
        );

        $g->save_throw(5);
        $g->save_throw(5);

        $g->next();

        $g->save_throw(5);

        $result     = $g->calculate_score_in_round_for_player($p, 1);
        $expected   = 15;

        $this->assertEquals($expected, $result);
    }

    /**
     * Cas avec un spare qui n'est pas au dernier round, mais dont
     * la première valeur du round suivant n'est pas encore connue.
     * @return void
    **/
    public function test__calculate_score_in_round_for_player_spare_but_impossible(): void
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            self::NORMAL_NUMBER_OF_ROUNDS,
            self::NORMAL_NUMBER_OF_PINS
        );

        $g->save_throw(5);
        $g->save_throw(5);

        $g->next();

        $result     = $g->calculate_score_in_round_for_player($p, 1);
        $expected   = null;

        $this->assertEquals($expected, $result);
    }

    /**
     * Cas d'un spare mais au dernier round
     * @return void
     */
    public function test__calculate_score_in_round_for_player_spare_simple_last_round(): void
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            1,
            self::NORMAL_NUMBER_OF_PINS
        );

        $g->save_throw(5);
        $g->save_throw(5);
        $g->save_throw(3);

        $g->next();

        $result     = $g->calculate_score_in_round_for_player($p, 1);
        $expected   = 13;

        $this->assertEquals($expected, $result);
    }

    /**
     * Cas d'un spare au dernier round impossible à calculer, car pas de troisième lancer
     * @return void
     */
    public function test__calculate_score_in_round_for_player_spare_simple_last_round_but_impossible(): void
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            1,
            self::NORMAL_NUMBER_OF_PINS
        );

        $g->save_throw(5);
        $g->save_throw(5);

        $g->next();

        $result     = $g->calculate_score_in_round_for_player($p, 1);
        $expected   = null;

        $this->assertEquals($expected, $result);
    }

    /**
     * Cas d'un strike au premier round
     * @return void
    **/
    public function test__calculate_score_in_round_for_player_strike_simple(): void
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            self::NORMAL_NUMBER_OF_ROUNDS,
            self::NORMAL_NUMBER_OF_PINS
        );

        $g->save_throw(10);

        $g->next();

        $g->save_throw(2);
        $g->save_throw(3);

        $result     = $g->calculate_score_in_round_for_player($p, 1);
        $expected   = 15;

        $this->assertEquals($expected, $result);
    }

    /**
     * Cas d'un strike en dernier round
     * @return void
     */
    public function test__calculate_score_in_round_for_player_strike_last(): void
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            1,
            self::NORMAL_NUMBER_OF_PINS
        );

        $g->save_throw(10);
        $g->save_throw(1);
        $g->save_throw(1);

        $this->assertEquals(12, $g->calculate_score_in_round_for_player($p, 1));
    }

    /**
     * Cas d'un strike dans un round intermédiaire
     * @return void
    **/
    public function test__calculate_total_correct_simple(): void
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            self::NORMAL_NUMBER_OF_ROUNDS,
            self::NORMAL_NUMBER_OF_PINS
        );

        $g->save_throw(10);

        $g->next();

        $g->save_throw(2);
        $g->save_throw(3);

        $this->assertEquals(20, $g->total_score_for_player($p));
    }

    public function test__calculate_total_long_match()
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            self::NORMAL_NUMBER_OF_ROUNDS,
            self::NORMAL_NUMBER_OF_PINS
        );

        for ($i = 1; $i <= 9; $i++) {
            $g->save_throw(2);
            $g->save_throw(2);
            $g->next();
        }

        $g->save_throw(5);
        $g->save_throw(1);

        $this->assertEquals(42, $g->total_score_for_player($p));
    }

    public function test__calculate_total_strike()
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            1,
            self::NORMAL_NUMBER_OF_PINS
        );

        $g->save_throw(10);
        $g->save_throw(5);
        $g->save_throw(2);

        $this->assertEquals(17, $g->total_score_for_player($p));
    }

    public function test__calculate_total_strike_in_a_row(): void
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            self::NORMAL_NUMBER_OF_ROUNDS,
            self::NORMAL_NUMBER_OF_PINS
        );

        $g->save_throw(self::NORMAL_NUMBER_OF_PINS);
        $g->next();

        $g->save_throw(self::NORMAL_NUMBER_OF_PINS);
        $g->next();

        $g->save_throw(self::NORMAL_NUMBER_OF_PINS);
        $g->next();

        $g->save_throw(4);
        $g->save_throw(3);

        $g->next();

        $this->assertEquals(78, $g->total_score_for_player($p));
    }

    /**
     * Cas d'un strike sans round suivant complet
    **/
    public function test__calculate_strike_no_following_round(): void
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            10,
            self::NORMAL_NUMBER_OF_PINS
        );

        $g->save_throw(10);
        $g->next();

        $this->assertEquals(null, $g->total_score_for_player($p));
    }

    public function test__calculate_strike_in_a_row_last_round(): void
    {
        $p = new Player("John Doe");

        $g = new Game(
            [$p],
            1,
            self::NORMAL_NUMBER_OF_PINS
        );

        $g->save_throw(10);
        $g->save_throw(10);
        $g->save_throw(10);

        $this->assertEquals(30, $g->total_score_for_player($p));
    }
}
