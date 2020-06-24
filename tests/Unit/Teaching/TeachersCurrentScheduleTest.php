<?php


namespace Tests\Unit\Teaching;


use App\Calendar\Day;
use App\Calendar\TimePeriod;
use App\CustomerAffairs\Course;
use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TeachersCurrentScheduleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function get_current_daily_schedule_for_week()
    {
        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->create()
        ]);

        $confirmed_course = factory(Course::class)->state('confirmed')->create();
        $unconfirmed = factory(Course::class)->state('unconfirmed')->create();

        $teacher->setAvailabilityForDay(new Day(Carbon::MONDAY, [
            new TimePeriod("08:00", "12:00")
        ]));
        $teacher->setAvailabilityForDay(new Day(Carbon::TUESDAY, [
            new TimePeriod("08:00", "12:00"),
            new TimePeriod("14:00", "17:00"),
        ]));
        $teacher->setAvailabilityForDay(new Day(Carbon::WEDNESDAY, [
            new TimePeriod("08:00", "12:00")
        ]));
        $teacher->setAvailabilityForDay(new Day(Carbon::THURSDAY, [
            new TimePeriod("14:00", "17:00")
        ]));
        $teacher->setAvailabilityForDay(new Day(Carbon::FRIDAY, [
            new TimePeriod("08:00", "16:00")
        ]));

        $confirmed_course->setLessonBlocks([
            [
                'day_of_week' => Carbon::TUESDAY,
                'starts'      => '15:00',
                'ends'        => '17:00',
            ],
            [
                'day_of_week' => Carbon::FRIDAY,
                'starts'      => '14:00',
                'ends'        => '15:30',
            ],
        ]);

        $unconfirmed->setLessonBlocks([
            [
                'day_of_week' => Carbon::WEDNESDAY,
                'starts'      => '9:00',
                'ends'        => '11:00',
            ],
        ]);

        $unconfirmed->assignTeacher($teacher->id);
        $confirmed_course->assignTeacher($teacher->id);

        $expected = [
            'available'   => [
                Carbon::MONDAY    => [
                    'periods' => [
                        [
                            'starts' => '8:00',
                            'ends'   => '12:00'
                        ]
                    ]
                ],
                Carbon::TUESDAY   => [
                    'periods' => [
                        [
                            'starts' => '8:00',
                            'ends'   => '12:00',
                        ],
                        [
                            'starts' => '14:00',
                            'ends'   => '15:00',
                        ]
                    ]
                ],
                Carbon::WEDNESDAY => [
                    'periods' => [
                        [
                            'starts' => '8:00',
                            'ends'   => '9:00',
                        ],
                        [
                            'starts' => '11:00',
                            'ends'   => '12:00',
                        ]
                    ]
                ],
                Carbon::THURSDAY  => [
                    'periods' => [
                        [
                            'starts' => '14:00',
                            'ends'   => '17:00',
                        ]
                    ]
                ],
                Carbon::FRIDAY    => [
                    'periods' => [
                        [
                            'starts' => '8:00',
                            'ends'   => '14:00',
                        ],
                        [
                            'starts' => '15:30',
                            'ends'   => '16:00',
                        ]
                    ]
                ],
                Carbon::SATURDAY  => [
                    'periods' => []
                ],
                Carbon::SUNDAY    => [
                    'periods' => []
                ]
            ],
            'confirmed'   => [
                Carbon::MONDAY    => [
                    'periods' => []
                ],
                Carbon::TUESDAY   => [
                    'periods' => [
                        [
                            'starts' => '15:00',
                            'ends'   => '17:00',
                        ]
                    ]
                ],
                Carbon::WEDNESDAY => [
                    'periods' => []
                ],
                Carbon::THURSDAY  => [
                    'periods' => []
                ],
                Carbon::FRIDAY    => [
                    'periods' => [
                        [
                            'starts' => '14:00',
                            'ends'   => '15:30',
                        ]
                    ]
                ],
                Carbon::SATURDAY  => [
                    'periods' => []
                ],
                Carbon::SUNDAY    => [
                    'periods' => []
                ],
            ],
            'unconfirmed' => [
                Carbon::MONDAY    => [
                    'periods' => []
                ],
                Carbon::TUESDAY   => [
                    'periods' => []
                ],
                Carbon::WEDNESDAY => [
                    'periods' => [
                        [
                            'starts' => '9:00',
                            'ends'   => '11:00',
                        ],
                    ]
                ],
                Carbon::THURSDAY  => [
                    'periods' => []
                ],
                Carbon::FRIDAY    => [
                    'periods' => []
                ],
                Carbon::SATURDAY  => [
                    'periods' => []
                ],
                Carbon::SUNDAY    => [
                    'periods' => []
                ]
            ]
        ];

        $this->assertEquals($expected, $teacher->fresh()->currentSchedule());
    }
}
