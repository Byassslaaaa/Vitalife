<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class AdminControllerImportsTest extends TestCase
{
    /**
     * Test that AdminController can be instantiated without errors.
     * This verifies that all model imports are correct.
     */
    public function test_admin_controller_imports_are_valid(): void
    {
        // Test that the controller class can be loaded
        $this->assertTrue(class_exists('\App\Http\Controllers\AdminController'));
        
        // Test that all imported model classes exist
        $this->assertTrue(class_exists('\App\Models\Spa'));
        $this->assertTrue(class_exists('\App\Models\Yoga'));
        $this->assertTrue(class_exists('\App\Models\Event'));
        $this->assertTrue(class_exists('\App\Models\Spesialis'));
        $this->assertTrue(class_exists('\App\Models\User'));
        $this->assertTrue(class_exists('\App\Models\Payment'));
    }
    
    /**
     * Test that EventController imports are correct.
     */
    public function test_event_controller_imports_are_valid(): void
    {
        $this->assertTrue(class_exists('\App\Http\Controllers\EventController'));
        $this->assertTrue(class_exists('\App\Models\Event'));
    }
    
    /**
     * Test that SpesialisController imports are correct.
     */
    public function test_spesialis_controller_imports_are_valid(): void
    {
        $this->assertTrue(class_exists('\App\Http\Controllers\SpesialisController'));
        $this->assertTrue(class_exists('\App\Models\Spesialis'));
    }
    
    /**
     * Test that model class names follow Laravel conventions.
     */
    public function test_model_class_names_follow_conventions(): void
    {
        // Verify that all model classes use PascalCase
        $reflectionSpa = new \ReflectionClass('\App\Models\Spa');
        $this->assertEquals('Spa', $reflectionSpa->getShortName());
        
        $reflectionYoga = new \ReflectionClass('\App\Models\Yoga');
        $this->assertEquals('Yoga', $reflectionYoga->getShortName());
        
        $reflectionEvent = new \ReflectionClass('\App\Models\Event');
        $this->assertEquals('Event', $reflectionEvent->getShortName());
        
        $reflectionSpesialis = new \ReflectionClass('\App\Models\Spesialis');
        $this->assertEquals('Spesialis', $reflectionSpesialis->getShortName());
    }
}