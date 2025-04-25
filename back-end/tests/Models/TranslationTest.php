<?php

namespace Tests\Models;

use App\Models\Pack;
use App\Models\Translation;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TranslationTest extends TestCase
{
  public function setUp(): void
  {
    parent::setUp();

    Pack::factory()->create([
      'name' => 'Test Pack',
      'description' => 'This is a test pack.',
    ]);
  }

  #[Test]
  #[Group('translation')]
  public function translation_can_be_created(): void
  {
    // Arrange
    $pack_id = Pack::first()->id;
    Translation::factory()->create([
      'pack_id' => $pack_id,
      'from_translation' => 'Hello',
      'to_translation' => 'Hola',
    ]);

    // Assert
    $this->assertDatabaseHas('translations', [
      'pack_id' => $pack_id,
      'from_translation' => 'Hello',
      'to_translation' => 'Hola',
    ]);
  }

  #[Test]
  #[Group('translation')]
  public function translation_can_be_updated(): void
  {
    // Arrange
    $pack_id = Pack::first()->id;
    $translation = Translation::factory()->create([
      'pack_id' => $pack_id,
      'from_translation' => 'Hello',
      'to_translation' => 'Hola',
    ]);

    // Act
    $translation->update([
      'from_translation' => 'Hello',
      'to_translation' => 'Hallo',
    ]);

    // Assert
    $this->assertDatabaseHas('translations', [
      'pack_id' => $pack_id,
      'from_translation' => 'Hello',
      'to_translation' => 'Hallo',
    ]);
  }

  #[Test]
  #[Group('translation')]
  public function translation_can_be_deleted(): void
  {
    // Arrange
    $pack_id = Pack::first()->id;
    $translation = Translation::factory()->create([
      'pack_id' => $pack_id,
      'from_translation' => 'Hello',
      'to_translation' => 'Hola',
    ]);

    // Act
    $translation->delete();

    // Assert
    $this->assertDatabaseMissing('translations', [
      'pack_id' => $pack_id,
      'from_translation' => 'Hello',
      'to_translation' => 'Hola',
    ]);
  }
}