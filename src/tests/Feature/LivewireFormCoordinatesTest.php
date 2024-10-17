<?php

namespace Tests\Feature;

use App\Livewire\Forms\Coordinates;
use PHPUnit\Framework\TestCase;

class LivewireFormCoordinatesTest extends TestCase
{
    public function test_coordinates_can_be_converted_to_string()
    {
        $coordinates = new Coordinates(10.5, 20.5);
        $this->assertEquals("10.5,20.5", (string) $coordinates);
    }

    public function test_getters_work_correctly()
    {
        $coordinates = new Coordinates(10.5, 20.5);
        $this->assertEquals(10.5, $coordinates->lat);
        $this->assertEquals(20.5, $coordinates->long);
    }

    public function test_setters_work_correctly()
    {
        $coordinates = new Coordinates(10.5, 20.5);
        $coordinates->lat = 12.5;
        $coordinates->long = 30.5;
        $this->assertEquals(12.5, $coordinates->lat);
        $this->assertEquals(30.5, $coordinates->long);
    }

    public function test_isset_method()
    {
        $coordinates = new Coordinates(10.5, 20.5);
        $this->assertTrue(isset($coordinates->lat));
        $this->assertTrue(isset($coordinates->long));
    }

    public function test_unset_method()
    {
        $coordinates = new Coordinates(10.5, 20.5);
        unset($coordinates->lat);
        unset($coordinates->long);
        $this->assertNull($coordinates->lat);
        $this->assertNull($coordinates->long);
    }

    public function test_invoke_method()
    {
        $coordinates = new Coordinates(10.5, 20.5);
        $coordinates(15.5, 25.5);
        $this->assertEquals(15.5, $coordinates->lat);
        $this->assertEquals(25.5, $coordinates->long);
    }

    public function test_call_method_for_getters_and_setters()
    {
        $coordinates = new Coordinates(10.5, 20.5);
        $this->assertEquals(10.5, $coordinates->getLat());
        $this->assertEquals(20.5, $coordinates->getLong());

        $coordinates->setLat(13.5);
        $coordinates->setLong(23.5);

        $this->assertEquals(13.5, $coordinates->getLat());
        $this->assertEquals(23.5, $coordinates->getLong());
    }

    public function test_static_call_method_from_string()
    {
        $coordinates = Coordinates::fromString("15.5,25.5");
        $this->assertEquals(15.5, $coordinates->lat);
        $this->assertEquals(25.5, $coordinates->long);
    }

    public function test_encode_and_decode_methods()
    {
        $coordinates = ['lat' => 10.5, 'long' => 20.5];
        $encoded = Coordinates::encode($coordinates);
        $decoded = Coordinates::decode($encoded);

        $this->assertEquals($coordinates, $decoded);
    }

    public function test_serialize_and_unserialize_methods()
    {
        $coordinates = new Coordinates(10.5, 20.5);
        $serialized = $coordinates->__serialize();
        $unserialized = new Coordinates(0, 0);
        $unserialized->__unserialize($serialized);

        $this->assertEquals($coordinates->lat, $unserialized->lat);
        $this->assertEquals($coordinates->long, $unserialized->long);
    }

    public function test_rules_static_method()
    {
        $rules = Coordinates::rules();
        $this->assertEquals([
            'lat' => 'nullable|numeric|between:-90,90',
            'long' => 'nullable|numeric|between:-180,180',
        ], $rules);

        $field_rules = Coordinates::rules('location');
        $this->assertEquals([
            'location.lat' => 'nullable|numeric|between:-90,90',
            'location.long' => 'nullable|numeric|between:-180,180',
        ], $field_rules);
    }

    public function test_debug_info_method()
    {
        $coordinates = new Coordinates(10.5, 20.5);
        $debugInfo = $coordinates->__debugInfo();

        $this->assertArrayHasKey('lat', $debugInfo);
        $this->assertArrayHasKey('long', $debugInfo);
        $this->assertEquals(10.5, $debugInfo['lat']);
        $this->assertEquals(20.5, $debugInfo['long']);
    }
}
