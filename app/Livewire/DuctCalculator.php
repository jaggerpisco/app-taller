<?php

namespace App\Livewire;

use Livewire\Component;

class DuctCalculator extends Component
{
    // Propiedades de fabricación (en pulgadas)
    public $ancho_w = 7;
    public $fondo_d = 7;
    public $desvio_o = 3;
    public $longitud_l = 7;

    public function getResultadosProperty()
    {
        // Protegemos las variables por si borras el input
        $w = floatval($this->ancho_w ?: 0);
        $d = floatval($this->fondo_d ?: 0);
        $o = floatval($this->desvio_o ?: 0);
        $l = floatval($this->longitud_l ?: 0);

        if ($w <= 0 || $d <= 0 || $o <= 0 || $l <= 0) {
            return [
                'radio_central' => 0,
                'radio_interior' => 0,
                'radio_exterior' => 0,
                'largo_wrapper' => 0
            ];
        }

        // 1. Radio Central (R) = (L^2 + O^2) / (4 * O)
        $radio_central = (pow($l, 2) + pow($o, 2)) / (4 * $o);

        // 2. Radio Interior / Garganta = R - (W / 2)
        $radio_interior = $radio_central - ($w / 2);

        // 3. Radio Exterior / Lomo = R + (W / 2)
        $radio_exterior = $radio_central + ($w / 2);

        // 4. Largo de la tira (Wrapper) = (4 * sqrt(O^2 + L^2) - L) / 3
        $largo_wrapper = ((4 * sqrt(pow($o, 2) + pow($l, 2))) - $l) / 3;

        return [
            'radio_central' => round($radio_central, 3),
            'radio_interior' => round($radio_interior, 3),
            'radio_exterior' => round($radio_exterior, 3),
            'largo_wrapper' => round($largo_wrapper, 3)
        ];
    }

    public function render()
    {
        return view('livewire.duct-calculator', [
            'resultados' => $this->resultados
        ]);
    }
}