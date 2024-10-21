<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ProfileUpdateRequestTest extends TestCase
{
    use WithFaker;

    public function test_validation_rules()
    {
        $request = $this->createRequestWithUser();

        $rules = $request->rules();

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertEquals(['required', 'string', 'max:255'], $rules['name']);
        $this->assertTrue(in_array('required', $rules['email']));
    }

    public function test_valid_data_passes_validation()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
        ];

        $request = $this->createRequestWithUser();

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_invalid_data_fails_validation()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'name' => '',
            'email' => 'not-an-email',
        ];

        $request = $this->createRequestWithUser();
        $validator = Validator::make($data, $request->rules());

        $validator->validate();
    }

    protected function createRequestWithUser()
    {
        $request = new ProfileUpdateRequest();

        // Mock the user() method to return a fake user
        $user = User::factory()->make();  // Create a fake user (without persisting to DB)
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $request;
    }
}
