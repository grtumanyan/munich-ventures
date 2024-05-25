<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    private const ROUTE_NAME = 'products.';

    protected Product $product;
    protected Category $category;
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->product = Product::factory()->create();
        $this->category = Category::factory()->create();
        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function index_is_reading_proper_route()
    {
        $this->assertEquals(
            '/api/products',
            route(self::ROUTE_NAME . 'index', false, false)
        );
    }

    /**
     * @test
     */
    public function update_is_reading_proper_route()
    {
        $this->assertEquals(
            '/api/products/' . $this->product->id ,
            route(self::ROUTE_NAME . 'update', ['product' => $this->product->id], false)
        );
    }

    /**
     * @test
     */
    public function index_is_not_reachable_without_auth()
    {
        $this->withHeaders([
            'Accept' => 'application/json'
        ])->get(
            route(self::ROUTE_NAME . 'index')
        )->assertUnauthorized();
    }

    /**
     * @test
     */
    public function it_works()
    {
        Sanctum::actingAs(
            $this->user
        );

        $this->withHeaders([
            'Accept' => 'application/json'
        ])->get(
            route(self::ROUTE_NAME . 'index')
        )->assertOk();
    }

    /**
     * @test
     */
    public function get_product_by_id_returns_categories_as_well()
    {
        Sanctum::actingAs(
            $this->user
        );

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get(
            route(self::ROUTE_NAME . 'show', ['product' => $this->product->id])
        );

        $response->assertOk();
        $response->assertJsonStructure([
            'category'
        ]);
    }

    /**
     * @test
     */
    public function it_can_create_product()
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post(
            route(self::ROUTE_NAME . 'store'),
            [
                'title' => 'Product Name',
                'description' => 'Product Description',
                'price' => 100,
                'sku' => 'TEST1234',
                'category_id' => $this->category->id
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseHas('products', [
            'title' => 'Product Name',
            'description' => 'Product Description',
            'price' => 100,
            'sku' => 'TEST1234',
            'category_id' => $this->category->id
        ]);
    }

    /**
     * @test
     */
    public function it_will_face_validation_issue_if_sku_is_not_8_digits()
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post(
            route(self::ROUTE_NAME . 'store'),
            [
                'title' => 'Product Name',
                'description' => 'Product Description',
                'price' => 100,
                'sku' => 'TEST123',
                'category_id' => $this->category->id
            ]
        );

        $response->assertStatus(422);
    }
}
