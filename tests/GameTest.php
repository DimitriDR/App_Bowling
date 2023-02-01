<?php
require_once dirname(__DIR__) . "/models/Game.php";

use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    private array $short_valid_list_players;
    const NUMBER_OF_ROUNDS  = 10;
    const NUMBER_OF_PINS    = 10;

    public function __construct()
    {
        parent::__construct();
        $this->short_valid_list_players = array(
            new Player("Player 1"),
            new Player("Player 2")
        );
    }

    /**
     * @test
     */
    public function test__game_init(): void
    {
        $players = array();
        $players[] = new Player("Player 1");
        $game = new Game($players, self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);

        $this->assertEquals($players[0], $game->get_current_player());
        $this->assertEquals(1, $game->get_current_round());
        $this->assertEquals(1, $game->get_current_throw());
    }

    public function test__game_init_with_invalid_player(): void
    {
        $players = array();
        $players[] = new stdClass();
        $this->expectException(InvalidArgumentException::class);
        new Game($players, self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);
    }

    public function test__save_throw(): void
    {
        $game = new Game($this->short_valid_list_players, self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);
        $game->save_throw(5);
        $this->assertEquals(5, $game->get_current_player()->get_scoreboard()[1]->get_first_throw());
    }

    public function test__save_throw_out_of_range(): void
    {
        $game = new Game($this->short_valid_list_players, self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);
        $this->expectException(InvalidArgumentException::class);
        $game->save_throw(999);
    }

    public function test__current_player_did_spare(): void
    {
        $game = new Game($this->short_valid_list_players, self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);
        $game->save_throw(5);
        $game->save_throw(5);
        $this->assertTrue($game->current_player_did_spare());
    }

    public function test__current_player_did_strike(): void
    {
        $game = new Game($this->short_valid_list_players, self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);
        $game->save_throw(10);
        $this->assertTrue($game->current_player_did_strike());
    }

    public function test__next(): void
    {
        $game = new Game([new Player("Player 1")], self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);
        $game->save_throw(1);
        $game->save_throw(1);

        $game->next();

        $this->assertEquals(2, $game->get_current_round());
        $this->assertEquals(2, sizeof($game->get_current_player()->get_scoreboard()));
    }

    public function test__next__next_player(): void
    {
        $game = new Game($this->short_valid_list_players, self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);
        $game->save_throw(1);
        $game->save_throw(1);

        $game->next();

        $this->assertEquals(1, $game->get_current_round());
        $this->assertEquals($this->short_valid_list_players[1], $game->get_current_player());

    }

    public function test__next__next_player_above_2(): void
    {
        $game = new Game($this->short_valid_list_players, self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);
        $game->save_throw(1);
        $game->save_throw(1);

        $game->next();

        $game->save_throw(1);
        $game->save_throw(1);

        $game->next();

        $this->assertEquals(2, $game->get_current_round());
        $this->assertEquals(2, sizeof($game->get_current_player()->get_scoreboard()));
    }

    public function test__compute_points_one_round(): void
    {
        $g = new Game([new Player("John Doe")], self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);
        $g->save_throw(1);
        $g->save_throw(6);

        $p = $g->get_current_player();

        $this->assertEquals(7, $g->point_calculation($p));
    }

    public function test__compute_points_spare(): void
    {
        $g = new Game([new Player("John Doe")], self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);
        $g->save_throw(5);
        $g->save_throw(5);

        $g->next();

        $g->save_throw(6);
        $g->save_throw(2);

        $p = $g->get_current_player();

        $this->assertEquals(24, $g->point_calculation($p));
    }

    public function test__compute_points_spare_2(): void
    {
        $g = new Game([new Player("John Doe")], self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);
        $g->save_throw(5);
        $g->save_throw(5);

        $g->next();

        $g->save_throw(3);
        $g->save_throw(2);

        $p = $g->get_current_player();

        $this->assertEquals(18, $g->point_calculation($p));
    }

    public function test__compute_points_strike(): void
    {
        $g = new Game([new Player("John Doe")], self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);
        $g->save_throw(10);

        $g->next();

        $g->save_throw(5);
        $g->save_throw(4);

        $p = $g->get_current_player();

        $this->assertEquals(28, $g->point_calculation($p));
    }

    public function test__compute_points_strike_near_end(): void
    {
        $g = new Game([new Player("John Doe")], self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);

        // On triche un peu pour remplir uniquement le dernier round
        for ($i = 0; $i < 9; $i++) {
            $g->save_throw(2);
            $g->save_throw(2);
            $g->next();
        }

        $g->save_throw(10);
        $g->save_throw(5);
        $g->save_throw(4);

        $p = $g->get_current_player();

        $this->assertEquals(55, $g->point_calculation($p));
    }

    public function test__compute_points_spare_near_end(): void
    {
        $g = new Game([new Player("John Doe")], self::NUMBER_OF_ROUNDS, self::NUMBER_OF_PINS);

        // On triche un peu pour remplir uniquement le dernier round
        for ($i = 0; $i < 9; $i++) {
            $g->save_throw(2);
            $g->save_throw(2);
            $g->next();
        }

        $g->save_throw(5);
        $g->save_throw(5);
        $g->save_throw(4);

        $p = $g->get_current_player();

        $this->assertEquals(50, $g->point_calculation($p));
    }

    // TODO: Rajouter test pour moins de 10 tours
}
