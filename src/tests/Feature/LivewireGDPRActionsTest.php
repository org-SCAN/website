<?php

namespace Tests\Feature;

use App\Livewire\GdprActions;
use App\Models\Field;
use App\Models\Link;
use App\Models\Refugee;
use App\Services\ZipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class LivewireGDPRActionsTest extends TestCase
{
    use RefreshDatabase;

    protected Refugee $refugee1;
    protected Refugee $refugee2;
    public function setUp(): void
    {
        parent::setUp();
        // Generate a field
        $field = Field::factory()->create(['best_descriptive_value' => 1, 'status' => 2]);

        // Create two refugees
        $this->refugee1 = Refugee::factory()->create();
        $this->refugee2 = Refugee::factory()->create();

        // Attach the field to the refugees
        $this->refugee1->fields()->attach([$field->id => ['value' => 'yess']]);
        $this->refugee2->fields()->attach([$field->id => ['value' => 'yess']]);

        // Create a link between the two refugees
        $this->links = Link::factory()->count(3)->create(['from' => $this->refugee1->id, 'to' => $this->refugee2->id]);

    }

    protected function tearDown(): void
    {
        // Clean up the 'zip/' directory after tests
        if (file_exists('zip')) {
            array_map('unlink', glob("zip/*.*"));
            rmdir('zip');
        }
        Mockery::close();
        parent::tearDown();
    }

    public function test_delete_deletes_refugee_and_links()
    {
        $refugee = $this->refugee1;
        $links = Link::withTrashed()->where('from', $refugee->id)->orWhere('to', $refugee->id)->get();
        // Check that the link has been deleted
        $this->assertCount(3, $links);

        Livewire::test(GdprActions::class)
            ->set('person', $refugee->id)
            ->call('delete');

        // Check that the refugee has been deleted
        $this->assertNull(Refugee::find($refugee->id));

        $links = Link::withTrashed()->where('from', $refugee->id)->orWhere('to', $refugee->id)->get();
        // Check that the link has been deleted
        $this->assertCount(0, $links);
    }

    protected function prepareZipTest(){
        $refugee = $this->refugee1;

        // Créer un lien associé au réfugié
        $link1 = Link::factory()->create(['from' => $refugee->id]);

        // Mock ZipService ou utilise un Zip réel si nécessaire
        Livewire::test(GdprActions::class)
            ->set('person', $refugee->id)
            ->call('export');

        // Vérifie que le fichier zip a bien été créé avec le bon nom
        return $zipFileName = 'zip/' . Str::snake($refugee->best_descriptive_value) . '.zip';
    }

    /**
     * Test that the export method creates a zip file with personal details and links
     * @return void
     */
    public function test_export_creates_zip_with_personal_details_and_links()
    {
        $zipFileName = $this->prepareZipTest();
        $this->assertFileExists($zipFileName);

        // Clean up: supprime le fichier zip après le test
        if (file_exists($zipFileName)) {
            unlink($zipFileName);
        }
    }

    public function test_export_delete_zip_after_download(){
        // change the env from testing to local to test the deleteFileAfterSend method
        $this->app['env'] = 'local';
        // call the test_export_creates_zip_with_personal_details_and_links method
        $zipFileName = $this->prepareZipTest();
        $this->assertFileDoesNotExist($zipFileName);
    }
}
