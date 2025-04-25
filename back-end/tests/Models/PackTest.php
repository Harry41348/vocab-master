<?php

namespace Tests\Models;

use App\Models\Pack;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PackTest extends TestCase
{
  #[Test]
  #[Group('pack')]
  public function pack_can_be_created(): void
  {
    // Arrange
    $pack = Pack::factory()->create([
      'name' => 'Sample Pack',
      'description' => 'This is a sample pack.',
    ]);

    // Assert
    $this->assertDatabaseHas('packs', [
      'name' => 'Sample Pack',
      'description' => 'This is a sample pack.',
    ]);
  }

  #[Test]
  #[Group('pack')]
  public function pack_can_be_updated(): void
  {
    // Arrange
    $pack = Pack::factory()->create([
      'name' => 'Sample Pack',
      'description' => 'This is a sample pack.',
    ]);

    // Act
    $pack->update([
      'name' => 'Updated Pack',
      'description' => 'This is an updated pack.',
    ]);

    // Assert
    $this->assertDatabaseHas('packs', [
      'name' => 'Updated Pack',
      'description' => 'This is an updated pack.',
    ]);
  }

  #[Test]
  #[Group('pack')]
  public function pack_can_be_deleted(): void
  {
    // Arrange
    $pack = Pack::factory()->create([
      'name' => 'Sample Pack',
      'description' => 'This is a sample pack.',
    ]);

    // Act
    $pack->delete();

    // Assert
    $this->assertDatabaseMissing('packs', [
      'name' => 'Sample Pack',
      'description' => 'This is a sample pack.',
    ]);
  }
  
  #[Test]
  #[Group('pack')]
  public function pack_can_have_translations(): void
  {
    $pack = Pack::factory()->create([
      'name' => 'Sample Pack',
      'description' => 'This is a sample pack.',
    ]);

    $pack->translations()->create([
      'from_translation' => 'Hello',
      'to_translation' => 'Hola',
    ]);

    $this->assertDatabaseHas('translations', [
      'from_translation' => 'Hello',
      'to_translation' => 'Hola',
    ]);
    $this->assertEquals(1, $pack->translations()->count());
  }

  #[Test]
  #[Group('pack')]
  public function deleting_pack_removes_translations(): void
  {
    // Arrange
    $pack = Pack::factory()->create([
      'name' => 'Sample Pack',
      'description' => 'This is a sample pack.',
    ]);

    $pack->translations()->create([
      'from_translation' => 'Hello',
      'to_translation' => 'Hola',
    ]);

    // Act
    $pack->delete();

    // Assert
    $this->assertDatabaseMissing('translations', [
      'from_translation' => 'Hello',
      'to_translation' => 'Hola',
    ]);
  }
}