<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Menu;
use App\Models\User;
use App\Notifications\ItemDeleted;
use App\Notifications\ItemUpdated;
use App\Notifications\MenuCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;
    //test whether a menu can be created
    public function test_menu_creation(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $menu = Menu::factory()->make()->toArray();
        Notification::fake();
        $response = $this->postJson('/api/menu', $menu);
        Notification::assertSentTo($user, MenuCreated::class);
        $response->assertStatus(201);
    }
    //test whether an item can be added to a menu
    public function test_adding_item_to_existing_menu():void
    {
        $user = User::factory(['is_admin'=>true])->create();
        Sanctum::actingAs($user);
        $menu = Menu::factory()->create();
        $item = Item::factory()->create();
        Notification::fake();
        $response = $this->putJson("/api/item/{$item->url}", [
            'menu_id' => $menu->id,
        ]);
        Notification::assertSentTo([$user], ItemUpdated::class);
        $response->assertStatus(200);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'menu_id' => $menu->id,
        ]);
    }
    //updating an item in a menu
    public function test_updating_a_menu_item():void
    {
        $user = User::factory(['is_admin'=>true])->create();
        Sanctum::actingAs($user);
        $menu = Menu::factory()->create();
        $item = Item::factory([
            'label' => 'label',
            'menu_id' => $menu->id,
        ])->create();
        Notification::fake();
        $response = $this->putJson("api/item/{$item->url}", [
            'label' => 'a label',
        ]);
        Notification::assertSentTo([$user], ItemUpdated::class);
        $response->assertStatus(200);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'label' => 'a label',
        ]);       
    }
    //deleting a menu item
    public function test_deleting_a_menu_item():void
    {
        $user = User::factory(['is_admin'=>true])->create();
        Sanctum::actingAs($user);
        $menu = Menu::factory()->create();
        $item = Item::factory([
            'label' => 'label',
            'menu_id' => $menu->id,
        ])->create();
        Notification::fake();
        $response = $this->deleteJson("api/item/{$item->url}");
        Notification::assertSentTo([$user], ItemDeleted::class);
        $response->assertStatus(204);
    }
    //checking whether the menu items are returned in the correct order
    public function test_if_items_are_in_correct_order():void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $menu = Menu::factory()->create();
        $item1 = Item::factory([
            'order' => 1,
            'menu_id' => $menu->id,
        ])->create();
        $item2 = Item::factory([
            'order' => 3,
            'menu_id' => $menu->id,
        ])->create();
        $item3 = Item::factory([
            'order' => 2,
            'menu_id' => $menu->id,
        ])->create();

        $response = $this->getJson("/api/menu/{$menu->identifier}?items=1");
        $response->assertStatus(200);
        $response->assertJsonPath('data.items.data.0.label', $item1->label);
        $response->assertJsonPath('data.items.data.1.label', $item3->label);
        $response->assertJsonPath('data.items.data.2.label', $item2->label);
    }
    //checks whether public endpoint returns only the active menu
    public function test_if_only_active_menus_are_shown_in_public():void
    {
        $menu1 = Menu::factory(['active_status'=>true])->create();
        $menu2 = Menu::factory(['active_status'=>false])->create();

        $response = $this->getJson("/menu/{$menu1->identifier}");
        $response->assertStatus(200);
        $response = $this->getJson("/menu/{$menu2->identifier}");
        $response->assertStatus(404);

    }
}
