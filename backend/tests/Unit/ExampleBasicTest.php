<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleBasicTest extends TestCase
{
    /**
     * Test que verifica suma simple
     */
    public function test_suma_dos_numeros(): void
    {
        // Arrange (Preparar)
        $numero1 = 5;
        $numero2 = 3;

        // Act (Actuar)
        $resultado = $numero1 + $numero2;

        // Assert (Verificar)
        $this->assertEquals(8, $resultado);
    }

    /**
     * Test que verifica un array contiene un valor
     */
    public function test_array_contiene_valor(): void
    {
        // Arrange
        $frutas = ['manzana', 'pera', 'uva'];

        // Assert
        $this->assertContains('pera', $frutas);
        $this->assertCount(3, $frutas);
    }

    /**
     * Test que verifica string
     */
    public function test_string_contiene_texto(): void
    {
        // Arrange
        $mensaje = 'Hola mundo';

        // Assert
        $this->assertStringContainsString('mundo', $mensaje);
    }
}
