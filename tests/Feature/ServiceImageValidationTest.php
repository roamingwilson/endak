<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Department;
use App\Models\DepartmentField;

class ServiceImageValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_validation_works_for_multiple_images()
    {
        // Create a test user
        $user = User::factory()->create();

        // Create a test department
        $department = Department::create([
            'name_ar' => 'Test Department',
            'name_en' => 'Test Department',
            'status' => 1
        ]);

        // Create a test department field for multiple images
        $field = DepartmentField::create([
            'department_id' => $department->id,
            'name' => 'images',
            'name_ar' => 'الصور',
            'name_en' => 'Images',
            'type' => 'images[]',
            'is_required' => false,
            'is_repeatable' => false,
            'input_group' => null
        ]);

        // Create test image files
        $image1 = UploadedFile::fake()->image('test1.jpg');
        $image2 = UploadedFile::fake()->image('test2.jpg');

        $response = $this->actingAs($user)->post('/services', [
            'user_id' => $user->id,
            'department_id' => $department->id,
            'type' => 'test',
            'from_city' => 'test',
            'custom_fields' => [
                'images' => [$image1, $image2]
            ]
        ]);

        // The request should be successful
        $response->assertStatus(302); // Redirect after successful creation
    }

    public function test_image_validation_fails_for_invalid_files()
    {
        // Create a test user
        $user = User::factory()->create();

        // Create a test department
        $department = Department::create([
            'name_ar' => 'Test Department',
            'name_en' => 'Test Department',
            'status' => 1
        ]);

        // Create a test department field for multiple images
        $field = DepartmentField::create([
            'department_id' => $department->id,
            'name' => 'images',
            'name_ar' => 'الصور',
            'name_en' => 'Images',
            'type' => 'images[]',
            'is_required' => false,
            'is_repeatable' => false,
            'input_group' => null
        ]);

        // Create an invalid file (not an image)
        $invalidFile = UploadedFile::fake()->create('test.txt', 100);

        $response = $this->actingAs($user)->post('/services', [
            'user_id' => $user->id,
            'department_id' => $department->id,
            'type' => 'test',
            'from_city' => 'test',
            'custom_fields' => [
                'images' => [$invalidFile]
            ]
        ]);

        // The request should fail validation
        $response->assertSessionHasErrors('custom_fields.images.*');
    }
}
