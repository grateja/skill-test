<?php

namespace App\Enums;

enum UnitEnum: String {
    case PCS = 'pcs.';
    case LOT = 'lot';
    case UNIT = 'unit';
    case BOX = 'box';
    case CARBOY = 'carboy';
    case LITER = 'liter';
    case MEGAGALLON = 'megagallon';
}
