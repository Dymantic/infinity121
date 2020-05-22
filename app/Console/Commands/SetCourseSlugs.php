<?php

namespace App\Console\Commands;

use App\Teaching\Subject;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SetCourseSlugs extends Command
{

    protected $signature = 'courses:slug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set course slugs';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Subject::all()->each(function($subject) {
            $subject->slug = Str::slug($subject->title['en']);
            $subject->save();
        });
    }
}
