<?php


namespace Tests\Feature\Teaching;


use App\Calendar\TimePeriod;
use App\Locations\Area;
use App\Profile;
use App\Teaching\Subject;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchAvailableTeachersForCourseTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function fetch_available_teachers()
    {
        $this->withoutExceptionHandling();

        $areaA = factory(Area::class)->create();
        $areaB = factory(Area::class)->create();

        $subjectA = factory(Subject::class)->create();
        $subjectB = factory(Subject::class)->create();

        $availableA = $this->createTeacher();
        $availableB = $this->createTeacher();
        $notAllTimes = $this->createTeacher();
        $otherLocation = $this->createTeacher();
        $noTimes = $this->createTeacher();
        $noSubject = $this->createTeacher();

        $availableA->setWorkingAreas([$areaA->id]);
        $availableB->setWorkingAreas([$areaA->id]);
        $notAllTimes->setWorkingAreas([$areaA->id]);
        $otherLocation->setWorkingAreas([$areaB->id]);
        $noTimes->setWorkingAreas([$areaA->id]);
        $noSubject->setWorkingAreas([$areaA->id]);

        $availableA->assignSubjects([$subjectA->id]);
        $availableB->assignSubjects([$subjectA->id]);
        $notAllTimes->assignSubjects([$subjectA->id]);
        $otherLocation->assignSubjects([$subjectA->id]);
        $noTimes->assignSubjects([$subjectA->id]);
        $noSubject->assignSubjects([$subjectB->id]);

        $availableA->setAvailabilityFor(1, [
            new TimePeriod("08:00", "13:00"),
            new TimePeriod("14:00", "17:00"),
        ]);
        $availableA->setAvailabilityFor(3, [
            new TimePeriod("08:00", "13:00"),
            new TimePeriod("14:00", "17:00"),
        ]);
        $availableA->setAvailabilityFor(5, [
            new TimePeriod("08:00", "13:00"),
            new TimePeriod("14:00", "17:00"),
        ]);

        $availableB->setAvailabilityFor(1, [
            new TimePeriod("09:30", "12:30"),
            new TimePeriod("15:00", "18:30"),
        ]);
        $availableB->setAvailabilityFor(3, [
            new TimePeriod("09:30", "12:30"),
            new TimePeriod("15:00", "18:30"),
        ]);
        $availableB->setAvailabilityFor(5, [
            new TimePeriod("09:30", "12:30"),
            new TimePeriod("15:00", "18:30"),
        ]);

        $notAllTimes->setAvailabilityFor(1, [
            new TimePeriod("09:30", "12:30"),
            new TimePeriod("15:00", "18:30"),
        ]);
        $notAllTimes->setAvailabilityFor(3, [
            new TimePeriod("09:30", "12:30"),
            new TimePeriod("15:00", "18:30"),
        ]);
        $notAllTimes->setAvailabilityFor(5, [
            new TimePeriod("09:30", "12:30"),
        ]);

        $otherLocation->setAvailabilityFor(1, [
            new TimePeriod("08:00", "13:00"),
            new TimePeriod("14:00", "17:00"),
        ]);
        $otherLocation->setAvailabilityFor(3, [
            new TimePeriod("08:00", "13:00"),
            new TimePeriod("14:00", "17:00"),
        ]);
        $otherLocation->setAvailabilityFor(5, [
            new TimePeriod("08:00", "13:00"),
            new TimePeriod("14:00", "17:00"),
        ]);

        $noTimes->setAvailabilityFor(2, [
            new TimePeriod("08:00", "13:00"),
            new TimePeriod("14:00", "17:00"),
        ]);
        $noTimes->setAvailabilityFor(4, [
            new TimePeriod("08:00", "13:00"),
            new TimePeriod("14:00", "17:00"),
        ]);

        $noSubject->setAvailabilityFor(1, [
            new TimePeriod("08:00", "13:00"),
            new TimePeriod("14:00", "17:00"),
        ]);
        $noSubject->setAvailabilityFor(3, [
            new TimePeriod("08:00", "13:00"),
            new TimePeriod("14:00", "17:00"),
        ]);
        $noSubject->setAvailabilityFor(5, [
            new TimePeriod("08:00", "13:00"),
            new TimePeriod("14:00", "17:00"),
        ]);

        $response = $this->asAdmin()->postJson("/admin/api/available-teachers", [
            'subject_id' => $subjectA->id,
            'area_id' => $areaA->id,
            'lesson_blocks' => [
                [
                    'day_of_week' => 3,
                    'starts' => '1600',
                    'ends' => '1700',
                ],
                [
                    'day_of_week' => 5,
                    'starts' => '1600',
                    'ends' => '1700',
                ]
            ]
        ]);

        $response->assertSuccessful();

        $fetched_teachers = $response->decodeResponseJson();

        $this->assertCount(2, $fetched_teachers);
        $this->assertTrue(collect($fetched_teachers)->pluck('id')->contains($availableA->id));
        $this->assertTrue(collect($fetched_teachers)->pluck('id')->contains($availableB->id));
    }

    private function createTeacher()
    {
        return factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only'),
            'is_public' => true,
        ]);
    }


}
