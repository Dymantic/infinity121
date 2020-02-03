<?php


namespace Tests\Unit\Affiliates;


use App\Affiliates\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PresentAffiliateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function presents_as_array_correctly()
    {
        Storage::fake('media');

        $affiliate = factory(Affiliate::class)
            ->state('public')
            ->create([
                'name'        => ['en' => 'test name', 'zh' => 'zh test name', 'jp' => 'jp test name'],
                'description' => [
                    'en' => 'test description',
                    'zh' => 'zh test description',
                    'jp' => 'jp test description'
                ],
                'link'        => 'https:/test.test',
            ]);
        $image = $affiliate->addImage(UploadedFile::fake()->image('testpic.png'));

        $expected = [
            'id'            => $affiliate->id,
            'name'          => ['en' => 'test name', 'zh' => 'zh test name', 'jp' => 'jp test name'],
            'description'   => [
                'en' => 'test description',
                'zh' => 'zh test description',
                'jp' => 'jp test description'
            ],
            'link'          => 'https:/test.test',
            'logo_original' => $image->getUrl(),
            'logo_thumb'    => $image->getUrl('thumb'),
            'is_public'     => true,
        ];

        $this->assertEquals($expected, $affiliate->toArray());
    }

    /**
     *@test
     */
    public function presents_for_given_language()
    {
        Storage::fake('media');

        $affiliate = factory(Affiliate::class)
            ->state('public')
            ->create([
                'name'        => ['en' => 'test name', 'zh' => 'zh test name', 'jp' => 'jp test name'],
                'description' => [
                    'en' => 'test description',
                    'zh' => 'zh test description',
                ],
                'link'        => 'https:/test.test',
            ]);
        $image = $affiliate->addImage(UploadedFile::fake()->image('testpic.png'));

        $expected = [
            'id'            => $affiliate->id,
            'name'          => 'jp test name',
            'description'   => '',
            'link'          => 'https:/test.test',
            'logo_original' => $image->getUrl(),
            'logo_thumb'    => $image->getUrl('thumb'),
            'is_public'     => true,
        ];

        app()->setLocale('jp');

        $this->assertEquals($expected, $affiliate->forCurrentLang());
    }
}
