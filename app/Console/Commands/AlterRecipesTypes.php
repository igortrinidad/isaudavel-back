<?php

namespace App\Console\Commands;

use App\Models\MealRecipe;
use Illuminate\Console\Command;

class AlterRecipesTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recipe:types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change recipe type to multiple types';

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
    public function handle()
    {
        $this->info('Command started' );

        $recipes = MealRecipe::all();

        foreach( $recipes as $recipe)
        {
            $recipe->types()->attach($recipe->type_id);
        }

        $this->info('Command finished' );
    }
}
