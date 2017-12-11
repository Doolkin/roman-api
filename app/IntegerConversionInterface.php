<?php
namespace App;

interface IntegerConversionInterface
{
    public function toRomanNumerals($integer);
    public function findRecent();
    public function findTop();
    public function saveNew($integer);
}