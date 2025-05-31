<?php
require __DIR__ . '/../../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;

$data = $_GET['data'] ?? 'Halo dari KurbanKu!';

$result = Builder::create()
    ->data($data)
    ->size(300)
    ->margin(10)
    ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
    ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
    ->build();

header('Content-Type: ' . $result->getMimeType());
echo $result->getString();
