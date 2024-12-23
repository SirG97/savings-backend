<?php

namespace Tests\Feature;



use App\Models\Branch;
use App\Models\SuperAdmin;
use App\Models\Admin;
use App\Models\Auditor;
use App\Models\Marketer;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\EmailNotification;

use Illuminate\Foundation\Testing\WithFaker;


use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    public function testCreateAdmin(): void
    {
        $branch = Branch::factory()->create();
//        User::where('email', 'admin@divineglobalgrowth.com')->update(['default_password' => '0']);
//        $user = User::where('email', 'admin@divineglobalgrowth.com')->first();
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'branch_id' => $branch->id,
            'name' => $this->faker->name(),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->email(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'default_password' => "1",
            'model' => 'admin'
        ];

        $response = $this->postJson(route('createUser'), $postData);
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testCreateAuditor(): void
    {
        $branch = Branch::factory()->create();
//        User::where('email', 'admin@divineglobalgrowth.com')->update(['default_password' => '0']);
//        $user = User::where('email', 'admin@divineglobalgrowth.com')->first();
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'branch_id' => $branch->id,
            'name' => $this->faker->name(),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->email(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'default_password' => "1",
            'model' => 'auditor'
        ];

        $response = $this->postJson(route('createUser'), $postData);
        $responseArray = $response->json();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testCreateMarketer(): void
    {
        $branch = Branch::factory()->create();
//        User::where('email', 'admin@divineglobalgrowth.com')->update(['default_password' => '0']);
//        $user = User::where('email', 'admin@divineglobalgrowth.com')->first();
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'branch_id' => $branch->id,
            'name' => $this->faker->name(),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->email(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'default_password' => "1",
            'model' => 'marketer'
        ];

        $response = $this->postJson(route('createUser'), $postData);
        $responseArray = $response->json();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
    }

    public function testReadSuperAdminUser(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $users = User::factory()->create([
            'model' => SuperAdmin::class,
        ]);

        $response = $this->getJson(route('readByUserModel', ['id' => 'all', 'model' => 'super_admin',]));
        $responseArray = $response->json();

        $response->assertOk();
        $this->assertTrue($responseArray['success']);
        $this->assertEquals($responseArray['data'][0]['model'], SuperAdmin::class);

        $response = $this->getJson(route('readByUserModel', ['model'=> 'super_admin']));
        $responseArray = $response->json();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
        $this->assertEquals($responseArray['data']['data'][0]['model'], SuperAdmin::class);

    }

    public function testReadAdminUser(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $users = User::factory()->create([
            'model' => Admin::class,
        ]);

        $response = $this->getJson(route('readByUserModel', ['id' => 'all', 'model' => 'admin',]));
        $responseArray = $response->json();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
        $this->assertEquals($responseArray['data'][0]['model'], Admin::class);

        $response = $this->getJson(route('readByUserModel', ['model'=> 'admin']));
        $responseArray = $response->json();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
        $this->assertEquals($responseArray['data']['data'][0]['model'], Admin::class);

    }

    public function testReadAuditorUser(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $users = User::factory()->create([
            'model' => Auditor::class,
        ]);

        $response = $this->getJson(route('readByUserModel', ['id' => 'all', 'model' => 'auditor',]));
        $responseArray = $response->json();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
        $this->assertEquals($responseArray['data'][0]['model'], Auditor::class);

        $response = $this->getJson(route('readByUserModel', ['model'=> 'auditor']));
        $responseArray = $response->json();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
        $this->assertEquals($responseArray['data']['data'][0]['model'], Auditor::class);

    }

    public function testReadMarketerUser(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $users = User::factory()->create([
            'model' => Marketer::class,
        ]);

        $response = $this->getJson(route('readByUserModel', ['id' => 'all', 'model' => 'marketer',]));
        $responseArray = $response->json();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
        $this->assertEquals($responseArray['data'][0]['model'], Marketer::class);

        $response = $this->getJson(route('readByUserModel', ['model'=> 'marketer']));
        $responseArray = $response->json();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);
        $this->assertEquals($responseArray['data']['data'][0]['model'], Marketer::class);

    }


    public function testReadAllUser(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $users = User::factory()->create([
            'model' => Marketer::class,
        ]);

        $response = $this->getJson(route('readUser', ['id' => 'all']));
        $responseArray = $response->json();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);


        $response = $this->getJson(route('readUser'));
        $responseArray = $response->json();
        $response->assertOk();
        $this->assertTrue($responseArray['success']);

    }

    public function testErrorValidationForUpdateUser()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $userToUpdate = User::factory()->create([

        ]);
        $postData = [
            'id' => 'abc',
            'name' => 'John Doe',
        ];

        $response = $this->putJson(route('updateUser'), $postData);
        $responseArray = $response->json();
        $this->assertFalse($responseArray['success']);
    }

    public function testUpdateUser()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $userToUpdate = User::factory()->create([
            'first_name' => 'Nasboi',
            'last_name' => 'Sabinus',
        ]);
        $postData = [
            'id' => $userToUpdate->id,
            'name' => 'John Doe',
            'first_name' => $this->faker->firstName(),
        ];

        $response = $this->putJson(route('updateUser'), $postData);
        $responseArray = $response->json();
        $this->assertTrue($responseArray['success']);
    }

    public function testSuspendOrActivateUser()
    {
        $user = User::factory()->create(['name' => 'Edu']);

        $this->actingAs($user);
        $userToSuspend = User::factory()->create([
            'first_name' => 'Nasboi',
            'last_name' => 'Sabinus',
        ]);
        $postData = [
            'id' => $userToSuspend->id,
            'active' => 0,
        ];

        $response = $this->putJson(route('suspendUser'), $postData);
        $responseArray = $response->json();
        $this->assertTrue($responseArray['success']);
        $this->assertNotNull($responseArray['data']['suspended_at']);

        $response = $this->getJson(route('suspendUser'), $postData);
        $postData = [
            'id' => $userToSuspend->id,
            'active' => 1,
        ];

        $response = $this->putJson(route('suspendUser'), $postData);
        $responseArray = $response->json();
        $this->assertTrue($responseArray['success']);
        $this->assertNull($responseArray['data']['suspended_at']);
    }

    public function testDeleteUser()
    {
        $user = User::factory()->create([
            'model' => Admin::class,
        ]);
        $user2 = User::factory()->create([
            'model' => Admin::class,
        ]);

        $this->actingAs($user);

        $postData = [
            'id' => $user2->id,
        ];

        $response = $this->deleteJson(route('deleteUser'), $postData);
        $response->assertStatus(422);

        $postData = [
            'id' => $user->id,
        ];

        $response = $this->deleteJson(route('deleteUser'), $postData);

        $responseArray = $response->json();
        $response->assertStatus(422);

        // try to login with the deleted user
//        $response = $this->postJson(route('login'), [
//            'email' => $user2->email,
//            'password' => 'password',
//        ]);
//
//        $responseArray = $response->json();
//
//        $response->assertBadRequest();
//        $this->assertSame(trans('auth.failed'), $responseArray['message']);
//        $this->assertFalse($responseArray['success']);
    }
    public function testSuspendUserIsBlocked(){
        $user = User::factory()->create([
            'suspended_at' => now(),
        ]);
        $this->actingAs($user);

        $response = $this->getJson('api/user/user');

        $responseArray = $response->json();
        $this->assertEquals($responseArray['status_code'], 423);
    }

    public function testAssignBranchToUser()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $userToUpdate = User::factory()->create([
            'first_name' => 'Nasboi',
            'last_name' => 'Sabinus',
        ]);
        $branch = Branch::factory()->create();
        $wallet = Wallet::factory()->create([
            'branch_id' => $branch->id,
        ]);
        $postData = [
            'id' => $userToUpdate->id,
            'branch_id' => $branch->id,
        ];

        $response = $this->putJson(route('assignBranch'), $postData);
        $responseArray = $response->json();
        $this->assertTrue($responseArray['success']);
    }

    public function testErrorAssignBranchToSuperAdmin()
    {
        $user = User::factory()->create([
            'model' => SuperAdmin::class
        ]);

        $this->actingAs($user);
        $userToUpdate = User::factory()->create([
            'first_name' => 'Nasboi',
            'last_name' => 'Sabinus',
            'model' => SuperAdmin::class
        ]);
        $branch = Branch::factory()->create();

        $postData = [
            'id' => $userToUpdate->id,
            'branch_id' => $branch->id,
        ];

        $response = $this->putJson(route('assignBranch'), $postData);
        $responseArray = $response->json();

        $this->assertFalse($responseArray['success']);
    }

}
