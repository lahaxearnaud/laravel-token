<?php
/**
 * Created by PhpStorm.
 * Author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 28/12/14
 * Time: 18:24
 */

namespace Lahaxearnaud\LaravelToken\commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Lahaxearnaud\LaravelToken\Models\Token;
use Symfony\Component\Console\Input\InputOption;

class ClearTokenCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'token:clear';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Tokens.';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if($this->option('all')) {
            \DB::statement("SET foreign_key_checks=0");
            Token::truncate();
            \DB::statement("SET foreign_key_checks=1");

            $this->info("All tokens deleted");
        } else {
            Token::where('expire_at', '<', Carbon::now()->timestamp)->delete();
            $this->info("All expired tokens removed");
        }
    }
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('all', 'all' ,InputOption::VALUE_NONE, 'All token (truncate table)')
        );
    }
}
