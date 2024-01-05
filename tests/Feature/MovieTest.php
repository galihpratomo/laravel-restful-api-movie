<?php

namespace Tests\Feature;

use App\Models\Movie;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    public function testList()
    {
        $data       = Movie::factory()->create();
        
        $response   = $this->get('/api/movie');

        $response->assertStatus(200)
                    ->assertJsonStructure([
                            'data' => [
                                'data' =>[[
                                    'id',
                                    'title',
                                    'description',
                                    'rating',
                                    'image',
                                ]]
                            ]
                        ]);
            
    }

    public function testStore()
    {
        Storage::fake('avatars');

        $data   =   [
                        'title'         => 'Belajar',
                        'description'   => 'tes belajar',
                        'rating'        => '1.3',
                        'image'         => UploadedFile::fake()->image('avatar.jpg'),
                    ];

        $response = $this->post('/api/movie', $data);

        $response->assertStatus(201)

                    ->assertJsonStructure([
                            'data' =>[
                                'id',
                                'title',
                                'description',
                                'rating',
                                'image',
                            ]
                        ]);
    }

   
    public function testUpdate()
    {
        $data   = Movie::factory()->create();

        $newData    =   [
                            'title'         => 'Belajar',
                            'description'   => 'tes belajar update',
                            'rating'        => '5.3',
                            'image'         => '',
                        ];

        $response = $this->put('/api/movie/' . $data->id, $newData);

        $response->assertStatus(200);
    }

  
    public function testDeleteApiUser()
    {
        $data = Movie::factory()->create();

        $response = $this->delete('/api/movie/' . $data->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('movies', [
            'id' => $data->id,
        ]);
    }
}
